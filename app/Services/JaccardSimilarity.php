<?php

namespace App\Services;

class JaccardSimilarity
{
    protected $n; // N-gram size

    public function __construct($n = 2)
    {
        $this->n = $n;
    }

    /**
     * Preprocess the string by tokenizing and sorting.
     *
     * @param string $string
     * @return string
     */
    public function preprocessString($string)
    {
        // Split the string into words (tokens)
        $tokens = explode(' ', $string);

        // Sort the tokens to neutralize order differences
        sort($tokens);

        // Join the tokens back into a single string
        return implode(' ', $tokens);
    }

    /**
     * Generate N-grams from a string.
     *
     * @param string $string
     * @return array
     */
    protected function generateNGrams($string)
    {
        $ngrams = [];
        $length = strlen($string) - $this->n + 1;

        for ($i = 0; $i < $length; $i++) {
            $ngrams[] = substr($string, $i, $this->n);
        }

        return $ngrams;
    }

    /**
     * Calculate the Jaccard Similarity between two strings.
     *
     * @param string $beneficiaryFromDatabase
     * @param string $inputtedBeneficiary
     * @return float
     */
    public function calculateSimilarity($beneficiaryFromDatabase, $inputtedBeneficiary)
    {
        # Preprocess the strings
        $beneficiaryFromDatabase = $this->preprocessString($beneficiaryFromDatabase);
        $inputtedBeneficiary = $this->preprocessString($inputtedBeneficiary);

        # Generate n-grams
        $ngrams1 = array_unique($this->generateNGrams($beneficiaryFromDatabase));
        $ngrams2 = array_unique($this->generateNGrams($inputtedBeneficiary));

        # Calculate intersection and union
        $intersection = array_intersect($ngrams1, $ngrams2);
        $union = array_unique(array_merge($ngrams1, $ngrams2));

        $jaccard = count($intersection) / count($union);

        // Returns the Jaccard Similarity as float
        return $jaccard;
    }
}