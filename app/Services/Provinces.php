<?php

namespace App\Services;

class Provinces
{

    /**
     *  @param string $regionalOffice The regional office of the authenticated user
     *  @return string[]|null Returns an array of provinces if true, returns null if false
     */
    public function getProvinces(string $regionalOffice): array|null
    {
        $provinces = null;

        if ($regionalOffice === 'NCR') {
            //
        } else if ($regionalOffice === 'Cordillera/Administrative Region') {
            //
        } else if ($regionalOffice === 'Region I') {
            //
        } else if ($regionalOffice === 'Region II') {
            //
        } else if ($regionalOffice === 'Region III') {
            //
        } else if ($regionalOffice === 'Region IV-A' || $regionalOffice === 'Region IV-B') {
            //
        } else if ($regionalOffice === 'Region V') {
            //
        } else if ($regionalOffice === 'Region VI') {
            //
        } else if ($regionalOffice === 'Region VII') {
            //
        } else if ($regionalOffice === 'Region VIII') {
            //
        } else if ($regionalOffice === 'Region IX') {
            //
        } else if ($regionalOffice === 'Region X') {
            //
        } else if ($regionalOffice === 'Region XI') {
            $provinces = [
                'Davao del Sur',
                'Davao de Oro',
                'Davao del Norte',
                'Davao Oriental',
                'Davao Occidental',
            ];
        } else if ($regionalOffice === 'Caraga Region') {
            //
        }

        return $provinces;
    }
}