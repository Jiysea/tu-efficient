<?php

namespace App\Services;

class Districts
{
    /**
     *  @param string $city_municipality The selected city/municipality by the user
     *  @param string $province The selected province by the user
     *  @return string[]|null Returns an array of districts if true, returns null if false
     */
    public static function getDistricts($city_municipality, $province): array|null
    {
        $districts = null;

        # Davao del Sur
        if ($city_municipality === 'City of Davao') {
            $districts = [
                '1st District',
                '2nd District',
                '3rd District',
            ];
        } else {
            $districts = [
                'N/A'
            ];
        }

        # Davao de Oro
        if ($province === 'Davao de Oro') {
            $districts = [
                '1st District',
                '2nd District',
            ];
        }
        # Davao del Norte
        // ...
        # Davao Oriental
        // ...
        # Davao Occidental
        // ...
        return $districts;
    }
}