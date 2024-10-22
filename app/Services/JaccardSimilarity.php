<?php

namespace App\Services;
use App\Models\Beneficiary;
use App\Models\Credential;
use Carbon\Carbon;

class JaccardSimilarity
{
    /**
     * Preprocess the string by tokenizing and sorting.
     *
     * @param string $string
     * @return string
     */
    protected static function preprocessString($string)
    {
        # Split the string into words (tokens)
        $tokens = explode(' ', $string);

        # Removes the birthdate (FOR TESTING)
        // unset($tokens[sizeOf($tokens) - 1]);

        # Sort the tokens to neutralize order differences
        sort($tokens);

        # Join the tokens back into a single string
        return implode(' ', $tokens);
    }

    /**
     * Generate N-grams from a string.
     *
     * @param string $string
     * @return array
     */
    protected static function generateNGrams($string)
    {
        # the n-grams amount
        $n = 2;
        $ngrams = [];
        $length = strlen($string) - $n + 1;

        for ($i = 0; $i < $length; $i++) {
            $ngrams[] = substr($string, $i, $n);
        }

        return $ngrams;
    }

    protected static function fullName($person, $middle, $ext)
    {
        $fullName = $person['first_name'];

        if ($middle) {
            $fullName .= ' ' . $person['middle_name'];
        }

        $fullName .= ' ' . $person['last_name'];
        if ($ext) {
            $fullName .= ' ' . $person['extension_name'];
        }

        return $fullName;
    }

    protected static function filterIllegalCharacters(string $name, bool $is_ext = false)
    {
        # Check if it's for the $extension_name
        if ($is_ext) {

            # All the illegal characters to remove from the $extension_name
            $illegal = [
                "!",
                "@",
                "#",
                "$",
                "%",
                "^",
                "&",
                "*",
                "(",
                ")",
                "+",
                "=",
                "-",
                "[",
                "]",
                "'",
                ";",
                ",",
                "/",
                "{",
                "}",
                "|",
                ":",
                "<",
                ">",
                "?",
                "~",
                "\"",
                "`",
                "\\"
            ];

            # It will keep looping until there is no more than 1 `.` left on the $name
            while (substr_count($name, '.') > 1) {
                $name = rtrim($name, '.') . '.';
            }

            # Replace all illegal characters with nothing, and also replace all numbers with nothing
            return str_replace($illegal, '', preg_replace('~[0-9]+~', '', $name));

        } else {

            # All the illegal characters to remove from the $name
            $illegal = [
                ".",
                "!",
                "@",
                "#",
                "$",
                "%",
                "^",
                "&",
                "*",
                "(",
                ")",
                "+",
                "=",
                "-",
                "[",
                "]",
                "'",
                ";",
                ",",
                "/",
                "{",
                "}",
                "|",
                ":",
                "<",
                ">",
                "?",
                "~",
                "\"",
                "`",
                "\\"
            ];

            # Replace all illegal characters with nothing, and also replace all numbers with nothing
            return str_replace($illegal, '', preg_replace('~[0-9]+~', '', $name));

        }
    }

    /**
     * Gets all of the indexed existing beneficiaries from the database.
     * @param string $filteredInputString: The full name of the given name of the beneficiary.
     * @return mixed Returns a Collection instance of the beneficiaries
     */
    protected static function prefetchNames(string $filteredInputString, ?string $middle_name, ?string $extension_name, ?string $ignoreId = null)
    {
        # if there's no beneficiaries from the database yet, return null
        $beneficiariesFromDatabase = null;

        # only take beneficiaries from the start of the year until today
        $startDate = now()->startOfYear();
        $endDate = now();

        # separate each word from all the name fields
        # and get the first letter of each word
        $namesToLetters = array_map(fn($word) => $word[0], explode(' ', $filteredInputString));

        $beneficiariesFromDatabase = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
            ->join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->whereBetween('implementations.created_at', [$startDate, $endDate])
            ->where(function ($query) use ($namesToLetters) {
                foreach ($namesToLetters as $letter) {
                    $query->orWhere('beneficiaries.first_name', 'LIKE', $letter . '%');
                }
            })
            ->where(function ($q) use ($namesToLetters, $middle_name, $extension_name) {

                foreach ($namesToLetters as $letter) {
                    $q->orWhere('beneficiaries.middle_name', 'LIKE', $letter . '%');
                }

                foreach ($namesToLetters as $letter) {
                    $q->orWhere('beneficiaries.last_name', 'LIKE', $letter . '%');
                }

                $q->when($extension_name, function ($q) use ($namesToLetters) {
                    foreach ($namesToLetters as $letter) {
                        $q->orWhere('beneficiaries.extension_name', 'LIKE', $letter . '%');
                    }
                });
            })
            ->select([
                'beneficiaries.*',
                'implementations.project_num',
                'batches.batch_num'
            ])
            ->when(!is_null($ignoreId), function ($query) use ($ignoreId) {
                $query->where('beneficiaries.id', '!=', decrypt($ignoreId));
            })
            ->get();

        return $beneficiariesFromDatabase;
    }

    /**
     * Calculate the Jaccard Similarity between two names.
     *
     * @param string $nameOne The first name for comparison
     * @param string $nameTwo The second name for comparison
     * @return float Returns a floating-point Jaccard index/co-efficient
     */
    public static function calculateSimilarity($nameOne, $nameTwo)
    {
        # Preprocess the strings
        $name1 = self::preprocessString($nameOne);
        $name2 = self::preprocessString($nameTwo);

        # Generate n-grams
        $ngrams1 = array_unique(self::generateNGrams($name1));
        $ngrams2 = array_unique(self::generateNGrams($name2));

        # Calculate intersection and union
        $intersection = array_intersect($ngrams1, $ngrams2);
        $union = array_unique(array_merge($ngrams1, $ngrams2));

        $jaccardIndex = count($intersection) / count($union);

        # Returns the Jaccard Similarity as float
        return floatval(number_format($jaccardIndex, 4));
    }

    /** 
     *  Get the results directly based on the inputted Beneficiary name
     *  
     *  @param string $first_name: The given first name of the beneficiary.
     *  @param ?string $middle_name: The given middle name of the beneficiary.
     *  @param string $last_name: The given middle name of the beneficiary.
     *  @param ?string $extension_name: The given extension name of the beneficiary.
     *  @param string $birthdate: The given birthdate of the beneficiary in YYYY/MM/DD format.
     *  @param int|float $threshold: The similarity threshold for returning only those who passed. (ex. 65, 65.0, 65.5).
     *                              Avoid assigning more than 100 or negative values or it will return unexpected results.
     *  @param string $ignoreId: A beneficiary ID to ignore. This parameter can only be used for EDITs
     *  @return array|null Returns the similarity results in an array, otherwise null.
     *  
     */
    public static function getResults(string $first_name, ?string $middle_name, string $last_name, ?string $extension_name, string $birthdate, int|float $threshold = 65)
    {
        # initialize the $results var so it would return null if there's no similarities
        $results = [];
        $first_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $first_name)));
        $filteredInputString = $first_name;

        if ($middle_name && $middle_name !== '-' && $middle_name !== '' && !is_null($middle_name)) {
            $middle_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $middle_name)));
            $filteredInputString .= ' ' . $middle_name;
        } else {
            $middle_name = null;
        }

        $last_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $last_name)));
        $filteredInputString .= ' ' . $last_name;

        # Filter the whole name (no extension_name) if in case there's illegal characters 
        $filteredInputString = self::filterIllegalCharacters($filteredInputString);

        if ($extension_name && $extension_name !== '-' && $extension_name !== '' && !is_null($extension_name)) {
            $extension_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $extension_name)));
            $filteredInputString .= ' ' . self::filterIllegalCharacters($extension_name, true);
        } else {
            $extension_name = null;
        }

        $filteredInputString = strtoupper($filteredInputString);

        # fetch all the potential duplicating names from the database
        $beneficiariesFromDatabase = self::prefetchNames($filteredInputString, $middle_name, $extension_name);

        if (ctype_digit((string) $threshold)) {

            if (floatval($threshold) > 100.0) {
                $threshold = 100;
            } else if (MoneyFormat::isNegative((string) $threshold)) {
                $threshold = 0;
            } else {
                $threshold = intval(config('settings.duplication_threshold', 65));
            }

        } else {
            $threshold = intval(config('settings.duplication_threshold', 65));
        }

        if (!is_null($beneficiariesFromDatabase)) {
            $count = 1;
            # this is where it checks the similarities
            foreach ($beneficiariesFromDatabase as $beneficiary) {
                $count++;
                # gets the full name of the beneficiary
                $beneficiaryFromDatabase = self::fullName($beneficiary, $middle_name, $extension_name);

                # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
                $coEfficient = self::calculateSimilarity($beneficiaryFromDatabase, $filteredInputString) * 100;

                # then check if it goes over the Threshold
                if ($coEfficient >= floatval($threshold)) {
                    $isPerfectDuplicate = false;
                    $identity_image_file_path = null;
                    $reason_image_file_path = null;
                    $image_description = null;
                    $credentials = Credential::where('beneficiaries_id', $beneficiary->id)
                        ->get();

                    foreach ($credentials as $credential) {
                        if ($credential->for_duplicates === 'yes') {
                            $reason_image_file_path = $credential->image_file_path;
                            $image_description = $credential->image_description;
                        } elseif ($credential->for_duplicates === 'no') {
                            $identity_image_file_path = $credential->image_file_path;
                        }
                    }

                    # If the name & birthdate are exactly the same, then it's considered a Perfect Duplicate
                    if (
                        intval($coEfficient) === 100
                        && Carbon::parse($birthdate)->format('Y-m-d') == Carbon::parse($beneficiary->birthdate)->format('Y-m-d')
                    ) {
                        $isPerfectDuplicate = true;
                    }

                    # if it does, then add them to the $results array
                    $results[] = [
                        'project_num' => $beneficiary->project_num,
                        'batch_num' => $beneficiary->batch_num,
                        'first_name' => $beneficiary->first_name,
                        'middle_name' => $beneficiary->middle_name,
                        'last_name' => $beneficiary->last_name,
                        'extension_name' => $beneficiary->extension_name,
                        'birthdate' => Carbon::parse($beneficiary->birthdate)->format('M d, Y'),
                        'barangay_name' => $beneficiary->barangay_name,
                        'contact_num' => $beneficiary->contact_num,
                        'occupation' => $beneficiary->occupation,
                        'avg_monthly_income' => $beneficiary->avg_monthly_income,
                        'city_municipality' => $beneficiary->city_municipality,
                        'province' => $beneficiary->province,
                        'district' => $beneficiary->district,
                        'type_of_id' => $beneficiary->type_of_id,
                        'id_number' => $beneficiary->id_number,
                        'e_payment_acc_num' => $beneficiary->e_payment_acc_num,
                        'beneficiary_type' => $beneficiary->beneficiary_type,
                        'sex' => $beneficiary->sex,
                        'civil_status' => $beneficiary->civil_status,
                        'age' => $beneficiary->age,
                        'dependent' => $beneficiary->dependent,
                        'self_employment' => $beneficiary->self_employment,
                        'skills_training' => $beneficiary->skills_training,
                        'is_pwd' => $beneficiary->is_pwd,
                        'is_senior_citizen' => $beneficiary->is_senior_citizen,
                        'spouse_first_name' => $beneficiary->spouse_first_name,
                        'spouse_middle_name' => $beneficiary->spouse_middle_name,
                        'spouse_last_name' => $beneficiary->spouse_last_name,
                        'spouse_extension_name' => $beneficiary->spouse_extension_name,
                        'created_at' => $beneficiary->created_at,
                        'coEfficient' => $coEfficient,
                        'is_perfect' => $isPerfectDuplicate,
                        'identity_image_file_path' => $identity_image_file_path,
                        'reason_image_file_path' => $reason_image_file_path,
                        'image_description' => $image_description,
                    ];
                }
            }
        }

        if (empty($results))
            return null;
        else
            return $results;
    }

    public static function getResultsFromEdit(string $first_name, ?string $middle_name, string $last_name, ?string $extension_name, string $birthdate, int|float $threshold = 65, ?string $ignoreId = null)
    {
        # initialize the $results var so it would return null if there's no similarities
        $results = [];
        $first_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $first_name)));
        $filteredInputString = $first_name;

        if ($middle_name && $middle_name !== '-' && $middle_name !== '' && !is_null($middle_name)) {
            $middle_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $middle_name)));
            $filteredInputString .= ' ' . $middle_name;
        } else {
            $middle_name = null;
        }

        $last_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $last_name)));
        $filteredInputString .= ' ' . $last_name;

        # Filter the whole name (no extension_name) if in case there's illegal characters 
        $filteredInputString = self::filterIllegalCharacters($filteredInputString);

        if ($extension_name && $extension_name !== '-' && $extension_name !== '' && !is_null($extension_name)) {
            $extension_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $extension_name)));
            $filteredInputString .= ' ' . self::filterIllegalCharacters($extension_name);
        } else {
            $extension_name = null;
        }

        # fetch all the potential duplicating names from the database
        $beneficiariesFromDatabase = self::prefetchNames($filteredInputString, $middle_name, $extension_name, $ignoreId);

        if (ctype_digit((string) $threshold)) {

            if (floatval($threshold) > 100.0) {
                $threshold = 100;
            } else if (MoneyFormat::isNegative((string) $threshold)) {
                $threshold = 0;
            } else {
                $threshold = intval(config('settings.duplication_threshold', 65));
            }

        } else {
            $threshold = intval(config('settings.duplication_threshold', 65));
        }

        if (!is_null($beneficiariesFromDatabase)) {
            $count = 1;
            # this is where it checks the similarities
            foreach ($beneficiariesFromDatabase as $beneficiary) {
                $count++;
                # gets the full name of the beneficiary
                $beneficiaryFromDatabase = self::fullName($beneficiary, $middle_name, $extension_name);

                # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
                $coEfficient = self::calculateSimilarity($beneficiaryFromDatabase, $filteredInputString) * 100;

                # then check if it goes over the Threshold
                if ($coEfficient >= floatval($threshold)) {

                    $isPerfectDuplicate = false;
                    $identity_image_file_path = null;
                    $reason_image_file_path = null;
                    $image_description = null;
                    $credentials = Credential::where('beneficiaries_id', $beneficiary->id)
                        ->get();

                    foreach ($credentials as $credential) {
                        if ($credential->for_duplicates === 'yes') {
                            $reason_image_file_path = $credential->image_file_path;
                            $image_description = $credential->image_description;
                        } elseif ($credential->for_duplicates === 'no') {
                            $identity_image_file_path = $credential->image_file_path;
                        }
                    }

                    # If the name & birthdate are exactly the same, then it's considered a Perfect Duplicate
                    if (
                        intval($coEfficient) === 100
                        && Carbon::parse($birthdate)->format('Y-m-d') == Carbon::parse($beneficiary->birthdate)->format('Y-m-d')
                    ) {
                        $isPerfectDuplicate = true;
                    }

                    # if it does, then add them to the $results array
                    $results[] = [
                        'project_num' => $beneficiary->project_num,
                        'batch_num' => $beneficiary->batch_num,
                        'first_name' => $beneficiary->first_name,
                        'middle_name' => $beneficiary->middle_name,
                        'last_name' => $beneficiary->last_name,
                        'extension_name' => $beneficiary->extension_name,
                        'birthdate' => Carbon::parse($beneficiary->birthdate)->format('M d, Y'),
                        'barangay_name' => $beneficiary->barangay_name,
                        'contact_num' => $beneficiary->contact_num,
                        'occupation' => $beneficiary->occupation,
                        'avg_monthly_income' => $beneficiary->avg_monthly_income,
                        'city_municipality' => $beneficiary->city_municipality,
                        'province' => $beneficiary->province,
                        'district' => $beneficiary->district,
                        'type_of_id' => $beneficiary->type_of_id,
                        'id_number' => $beneficiary->id_number,
                        'e_payment_acc_num' => $beneficiary->e_payment_acc_num,
                        'beneficiary_type' => $beneficiary->beneficiary_type,
                        'sex' => $beneficiary->sex,
                        'civil_status' => $beneficiary->civil_status,
                        'age' => $beneficiary->age,
                        'dependent' => $beneficiary->dependent,
                        'self_employment' => $beneficiary->self_employment,
                        'skills_training' => $beneficiary->skills_training,
                        'is_pwd' => $beneficiary->is_pwd,
                        'is_senior_citizen' => $beneficiary->is_senior_citizen,
                        'spouse_first_name' => $beneficiary->spouse_first_name,
                        'spouse_middle_name' => $beneficiary->spouse_middle_name,
                        'spouse_last_name' => $beneficiary->spouse_last_name,
                        'spouse_extension_name' => $beneficiary->spouse_extension_name,
                        'identity_image_file_path' => $identity_image_file_path,
                        'reason_image_file_path' => $reason_image_file_path,
                        'image_description' => $image_description,
                        'created_at' => $beneficiary->created_at,
                        'coEfficient' => $coEfficient,
                        'is_perfect' => $isPerfectDuplicate,
                    ];
                }
            }
        }

        if (empty($results))
            return null;
        else
            return $results;
    }

    public static function isPerfect(string $first_name, string $middle_name, string $last_name, string $extension_name, string $birthdate)
    {
        # clear out any previous similarity results / initialize
        $isPerfect = false;

        $first_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $first_name)));
        $filteredInputString = $first_name;

        if ($middle_name && $middle_name !== '-' || !is_null($middle_name)) {
            $middle_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $middle_name)));
            $filteredInputString .= ' ' . $middle_name;
        } else {
            $middle_name = null;
        }

        $last_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $last_name)));
        $filteredInputString = $last_name;

        # Filter the whole name (no extension_name) if in case there's illegal characters 
        $filteredInputString = self::filterIllegalCharacters($filteredInputString);

        if ($extension_name && $extension_name !== '-' || !is_null($extension_name)) {
            $extension_name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $extension_name)));
            $filteredInputString .= ' ' . $extension_name;
        } else {
            $extension_name = null;
        }

        # Filter again if the $extension_name is included
        $filteredInputString = self::filterIllegalCharacters($filteredInputString, true);

        # fetch all the potential duplicating names from the database
        $beneficiariesFromDatabase = self::prefetchNames($filteredInputString, $middle_name, $extension_name);

        # this is where it checks the similarities
        foreach ($beneficiariesFromDatabase as $beneficiary) {

            # gets the full name of the beneficiary
            $beneficiaryFromDatabase = self::fullName($beneficiary, $middle_name, $extension_name);

            # gets the co-efficient/jaccard index of the 2 names (without birthdate by default)
            $coEfficient = self::calculateSimilarity($beneficiaryFromDatabase, $filteredInputString) * 100;

            # check if it's a perfect duplicate
            if (
                intval($coEfficient) === 100
                && Carbon::parse($birthdate)->format('Y-m-d') == Carbon::parse($beneficiary->birthdate)->format('Y-m-d')
            ) {
                $isPerfect = true;
                break;
            }
        }

        return $isPerfect;
    }
}