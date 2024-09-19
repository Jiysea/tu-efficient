<?php

namespace App\Services;

class CitiesMunicipalities
{

    /**
     *  @param string $province The selected province by the user
     *  @return string[]|null Returns an array of cities/municipalities if true, returns null if false
     */
    public function getCitiesMunicipalities(string $province): array|null
    {
        $cities_municipalities = null;

        # Region XI
        if ($province === 'Davao del Sur') {
            $cities_municipalities = [
                'Digos City',
                'Bansalan',
                'Davao City',
                'Hagonoy',
                'Kiblawan',
                'Magsaysay',
                'Malalag',
                'Matanao',
                'Padada',
                'Santa Cruz',
            ];
        } else if ($province === 'Davao del Norte') {
            $cities_municipalities = [
                'Tagum',
                'Asuncion',
                'Braulio E. Dujali',
                'Carmen',
                'Kapalong',
                'New Corella',
                'Panabo',
                'Samal',
                'San Isidro',
                'Santo Tomas',
                'Talaingod'
            ];
        } else if ($province === 'Davao de Oro') {
            $cities_municipalities = [
                'Nabunturan',
                'Compostela',
                'Laak',
                'Mabini',
                'Maco',
                'Maragusan',
                'Mawab',
                'Monkayo',
                'Montevista',
                'New Bataan',
                'Pantukan'
            ];
        } else if ($province === 'Davao Occidental') {
            $cities_municipalities = [
                'Malita',
                'Don Marcelino',
                'Jose Abad Santos',
                'Santa Maria',
                'Sarangani'
            ];
        } else if ($province === 'Davao Oriental') {
            $cities_municipalities = [
                'Mati',
                'Baganga',
                'Banaybanay',
                'Boston',
                'Caraga',
                'Cateel',
                'Governor Generoso',
                'Lupon',
                'Manay',
                'San Isidro',
                'Tarragona'
            ];
        }

        # NCR Region
        // ...
        # Cordillera/Administrative Region
        // ...
        # Region I
        // ...
        # Region II
        // ...
        # Region III
        // ...
        # Region IV-A & Region IV-B
        // ...
        # Region V
        // ...
        # Region VI
        // ...
        # Region VII
        // ...
        # Region VIII
        // ...
        # Region IX
        // ...
        # Region X
        // ...
        # Region XII
        // ...
        # Caraga Region
        // ...
        return $cities_municipalities;
    }
}