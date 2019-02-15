<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class Weather extends Model
{
    const API_URI = 'https://api.darksky.net';

    /**
     * @param float $longitude
     * @param float $latitude
     * @param \DateTime $currentDay
     * @param string $direction
     * @return array
     */
    public function getCityWeather(float $longitude, float $latitude, \DateTime $currentDay = null, string $direction = '') : array
    {
        $currentDayStr = $currentDay ? $currentDay->format('Y-m-d') : '';

        $result = $this->_getCached($longitude, $latitude, $currentDayStr, $direction);
//
        $result=false;

        if (!empty($result)) {
            return $result;
        }


        $result = [];
        $client = new Client([
            'baseUrl' => self::API_URI,
            'decode_content' => 'gzip'
        ]);

        $apiKey = env('DARKSKY_API_KEY');

        $days = $currentDay ? $this->_getDays($currentDay, $direction) : [''];

        if (empty($days)) {
            return [];
        }

        $promises = [];

        foreach ($days as $day) {

            $uri = sprintf(
                'https://api.darksky.net/forecast/%s/%f,%f%s/?units=si&exclude=currently,minutely,hourly',
                $apiKey,
                $longitude,
                $latitude,
                $day ? ',' . $day . 'T23:59:59+02:00' : '' // add "," before the day if the day is not empty
            );

            $promises[] = $client->getAsync($uri);
        }

        // Wait on all of the requests to complete. Throws a ConnectException
        // if any of the requests fail
        $reqResults = Promise\unwrap($promises);

        foreach ($reqResults as $res) {

            $result = array_merge(
                $this->_getDataFromResponse($res),
                $result
            );

        }

        if ('next' == $direction) {
            $result = array_reverse($result);
        }

        $this->_putCache($result, $longitude, $latitude, $currentDayStr, $direction);


        return $result;
    }

    /**
     * @param \DateTime $givenDay
     * @param string $direction
     * @return array
     * @throws \Exception
     */
    private function _getDays(\DateTime $givenDay, string $direction) : array
    {
        $result = [];

        $todayDate = new \DateTime();

        $interval = new \DateInterval('P1D');

        // get 8 next/prev days
        for ($i = 0; $i < 8; $i++) {

            if ('next' == $direction) {

                $givenDay->add($interval);

            } else {

                $givenDay->sub($interval);
            }

            // get only prev 30 days OR next 30 days
            $daysDiff = $givenDay->diff($todayDate)->days;

            if ($daysDiff < 30) {
                $result[] = $givenDay->format('Y-m-d');
            }
        }

        return $result;
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     * @return array
     */
    private function _getDataFromResponse(\GuzzleHttp\Psr7\Response $response) : array
    {
        // parse JSON
        $resultJson = (string)$response->getBody();

        $jsonData = json_decode($resultJson);

        if (false === $jsonData) {

            $err = json_last_error_msg();

            if (!empty($err)) {

                // Log should be here
                throw new Exception('API error');
            }
        }

        // Perform result entry
        $result = [];

        $todayDate = date('Y-m-d');

        foreach ($jsonData->daily->data as $dayData) {

            $itm = new \stdClass;

            $itm->dateIso      = date('Y-m-d', $dayData->time);
            $itm->isToday      = $todayDate == $itm->dateIso;
            $itm->dayLabel     = date('l, d F Y', $dayData->time);
            $itm->summary      = $dayData->summary;
            $itm->weatherImage = $dayData->icon;
            $itm->dayTemp      = round($dayData->temperatureHigh) . ' ℃';
            $itm->nightTemp    = round($dayData->temperatureLow) . ' ℃';

            $result[] = $itm;
        }

        return $result;
    }

    function _getCached($longitude, $latitude, $currentDay, $direction)
    {
        $name = "{$longitude}__{$latitude}__{$currentDay}__{$direction}";
        $name=str_replace([',', '.'], '_', $name);

        $file = storage_path('framework') . "/cache/$name";

        if (file_exists($file)) {
            return json_decode(file_get_contents($file));
        }
    }

    function _putCache($result, $longitude, $latitude, $currentDay, $direction)
    {
        $name = "{$longitude}__{$latitude}__{$currentDay}__{$direction}";
        $name=str_replace([',', '.'], '_', $name);

        $file = storage_path('framework') . "/cache/$name";

        file_put_contents(
            $file,
            json_encode($result)
        );
    }
}
