<?php

namespace App\Services;

use Carbon\Carbon;

class Essential
{
    /**
     * It checks whether a string has illegal characters or not.
     * Ideal use for this function is for `names`
     * @param null|string $name
     * @param bool $is_ext
     * @param mixed $is_full
     * @return bool|string
     */
    public static function hasIllegal(null|string $name, bool $is_ext = false, $is_full = false)
    {
        # Check if it's for the $extension_name
        if ($is_ext) {

            # All the illegal characters to remove from the $extension_name
            $illegal = "!@#$%^&*()+=-[]';,/{}|:<>?~\"`\\";

            # Returns true if it finds any listed illegal characters on the name, otherwise false.
            return $name ? strpbrk($name, $illegal) : false;

        } elseif ($is_full) {

            # All the illegal characters to remove from the $name
            $illegal = "!@#$%^&*()+=[]';,/{}|:<>?~\"`\\";

            # Returns true if it finds any listed illegal characters on the name, otherwise false.
            return $name ? strpbrk($name, $illegal) : false;

        } else {

            # All the illegal characters to remove from the $name
            $illegal = ".!@#$%^&*()+=[]';,/{}|:<>?~\"`\\";

            # Returns true if it finds any listed illegal characters on the name, otherwise false.
            return $name ? strpbrk($name, $illegal) : false;

        }
    }

    /**
     * It checks whether the string `value` is a number or not.
     * @param null|string $value Any string value.
     * @return bool|int Returns 1 if it has a number, otherwise 0 or false if it fails.
     */
    public static function hasNumber(null|string $value)
    {
        # Check if a string is a number
        return preg_match('~[0-9]+~', $value);
    }

    /**
     * It trims whitespaces and newlines in between letters.
     * @param string $value Any string value can be assigned here
     * @return string Returns the trimmed string
     */
    public static function trimmer(string|null $value)
    {
        if (!isset($value) || empty($value)) {
            return null;
        }
        return trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $value)));
    }

    public static function extract_date($message, $returnFormat = 'Y/m/d')
    {
        $date_patterns = [
            '/\b\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y-m-d',
            '/\b\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])\b/' => 'Y-d-m',
            '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}\b/' => 'm-d-Y',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}\b/' => 'd-m-Y',
            '/\b(0[1-9]|1[0-2])-\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'm-Y-d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}-(0[1-9]|1[0-2])\b/' => 'd-Y-m',

            '/\b\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y/m/d',
            '/\b\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\b/' => 'Y/d/m',
            '/\b(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\b/' => 'm/d/Y',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\b/' => 'd/m/Y',
            '/\b(0[1-9]|1[0-2])\/\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'm/Y/d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\/(0[1-9]|1[0-2])\b/' => 'd/Y/m',

            '/\b\d{4}\.(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y.m.d',
            '/\b\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\b/' => 'Y.d.m',
            '/\b(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\b/' => 'm.d.Y',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.\d{4}\b/' => 'd.m.Y',
            '/\b(0[1-9]|1[0-2])\.\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'm.Y.d',
            '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\.(0[1-9]|1[0-2])\b/' => 'd.Y.m',
        ];

        $dateTimeStr = null;
        $dateTimeFormat = null;

        foreach ($date_patterns as $date_pattern => $format) {
            if (preg_match($date_pattern, $message, $matches)) {
                $dateTimeFormat = $format;
                $dateTimeStr = $matches[0];
                break;
            }
        }

        if ($dateTimeStr == null || $dateTimeFormat == null) {
            return null;
        }
        $d = Carbon::createFromFormat($dateTimeFormat, $dateTimeStr);
        return $d->format($returnFormat);
    }
}