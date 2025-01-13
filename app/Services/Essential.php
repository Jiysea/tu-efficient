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

    /**
     * It filters the contact number of the beneficiary based on PH Locale
     * @param string $number The contact number value.
     * @return mixed Returns the contact number with `09` prefix. Otherwise it would return false if it's not a valid number.
     */
    public static function filter_contact_num(string $number)
    {
        if ((substr($number, 0, 2) === '09') && strlen($number) === 11) {
            return $number;
        } elseif (substr($number, 0, 4) === '+639' && strlen($number) === 13) {
            return '0' . substr($number, 3);
        } else {
            return false;
        }
    }

    /**
     * It extracts the date to check if it's a valid date among the listed formats
     * and also returns the converted date value or its date format.
     * 
     * These are the list of valid formats:
     * 
     * [Dashed]
     * • `Y-m-d`
     * • `Y-d-m`
     * • `m-d-Y`
     * • `d-m-Y` (only works if `d` is over 12)
     * • `m-Y-d`
     * • `d-Y-m` (only works if `d` is over 12)
     * 
     * [Slashed]
     * • `Y/m/d`
     * • `Y/d/m`
     * • `m/d/Y`
     * • `d/m/Y` (only works if `d` is over 12)
     * • `m/Y/d`
     * • `d/Y/m` (only works if `d` is over 12)
     * 
     * [Dotted]
     * • `Y.m.d`
     * • `Y.d.m`
     * • `m.d.Y`
     * • `d.m.Y` (only works if `d` is over 12)
     * • `m.Y.d`
     * • `d.Y.m` (only works if `d` is over 12)
     * 
     * @param string|null $value The string value of the date you want to extract. If it's `null`, it will return `null`.
     * @param bool $changeFormat If `true`, it will convert the date to another format. Otherwise `false`
     * to return its date format.
     * @param string $returnFormat The format used to convert or being returned
     * @return string|null Returns a `string` converted date value or its format, otherwise returns
     * `null` if the date value extracted is not among the listed valid formats.
     * 
     * 
     */
    public static function extract_date(string|null $value, bool $changeFormat = true, string $returnFormat = 'Y/m/d')
    {
        if (!isset($value) || empty($value)) {
            return null;
        }

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
            if (preg_match($date_pattern, $value, $matches)) {
                $dateTimeFormat = $format;
                $dateTimeStr = $matches[0];
                break;
            }
        }

        if ($dateTimeStr == null || $dateTimeFormat == null) {
            return null;
        }

        if ($changeFormat) {
            $d = Carbon::createFromFormat($dateTimeFormat, $dateTimeStr);
            return $d->format($returnFormat);
        }

        return $dateTimeFormat;
    }
}