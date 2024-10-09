<?php

namespace App\Services;

class MoneyFormat
{
    /**
     * 
     *  @param string $value A numeric value that contains `.` and `,`
     *  @return int Returns an integer without other symbols
     */
    public static function unmask(string $value)
    {
        $number = floatval(str_replace(',', '', $value));

        # Check if there's a decimal point
        if (strpos($number, '.') !== false) {
            # Convert to float, format to 2 decimal places, then remove the decimal
            $number = intval(str_replace('.', '', number_format($number, 2, '.', '')));
        } else {
            # Append '00' if there's no decimal point
            $number = intval(($number . '00'));
        }

        return $number;
    }

    /**
     * 
     *  @param int $value An integer value
     *  @return string Returns a string that contains `.` and `,`
     */
    public static function mask(int $value)
    {
        # Convert integer back to float by dividing by 100
        $text = $value / 100;

        # Format the float to 2 decimal places and add commas as thousands separators
        $text = number_format($text, 2, '.', ',');

        return $text;
    }

    /**
     * 
     *  @param string $value A numeric value that contains `.` and `,`
     *  @return bool Returns true if it's a float value, otherwise false
     */
    public static function isMaskInt(string $value)
    {
        $value = self::unmask($value);
        $check = filter_var($value, FILTER_VALIDATE_INT);

        return $check;
    }

    /**
     * 
     *  @param string $value A numeric value that contains `.` and `,`
     *  @return bool Returns true if it's a negative value, otherwise false
     */
    public static function isNegative(string $value)
    {
        $value = self::unmask($value);
        $check = $value <= 0;
        return $check;
    }
}