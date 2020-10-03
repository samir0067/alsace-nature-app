<?php


namespace App\Service;

class Geocoding
{
    /**
     * Service de Geocoding
     * @return bool|string
     */
    public function geocoding($street, $city, $postalcode)
    {
            $city = str_replace(" ", "%20", $city);
            $street = str_replace(" ", "%20", $street);
            $curl = curl_init(
                'https://us1.locationiq.com/v1/search.php?key=0cea14ae1c2a17&street='.$street
                .'&city='.$city.'&postalcode='.$postalcode.'&country=France&format=json'
            );

        if ($curl != false) {
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER    =>  true,
                CURLOPT_FOLLOWLOCATION    =>  true,
                CURLOPT_MAXREDIRS         =>  10,
                CURLOPT_TIMEOUT           =>  30,
                CURLOPT_CUSTOMREQUEST     =>  'GET',
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo 'cURL Error #:' . $err;
            } else {
                return $response;
            }
        }
    }
}
