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
     * @param \DateTime $givenDay
     * @param string $direction
     * @return array
     */
    public function getCityWeather(float $longitude,
                                   float $latitude,
                                   \DateTime $givenDay = null,
                                   string $direction   = '') : array
    {
        $result = [];

        $client = new Client([
            'baseUrl'        => self::API_URI,
            'decode_content' => 'gzip'
        ]);

        $days = $givenDay ?
            $this->_getDays($givenDay, $direction) :
            [ '' ]; // today

        if (empty($days)) {
            return [];
        }

        $promises = [];

        foreach ($days as $day) {

            $uri = sprintf(
                'https://api.darksky.net/forecast/%s/%f,%f%s/?units=si&exclude=currently,minutely,hourly',
                env('DARKSKY_API_KEY'),
                $latitude,
                $longitude,
                $day ? ",{$day}T00:00:00" : '' // add "," before the day if the day is not empty
            );

            // special case for 'today'
            $promises[$day] = $client->getAsync($uri);
        }

        // Wait on all of the requests to complete. Throws a ConnectException
        // if any of the requests fail
        $reqResults = Promise\unwrap($promises);

        foreach ($reqResults as $day => $res) {

            $result = array_merge(
                $this->_getDataFromResponse($day, $res),
                $result
            );
        }

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

        // get 7 next/prev days
        for ($i = 0; $i < 7; $i++) {

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

        if ('next' == $direction) {
            $result = array_reverse($result);
        }

        return $result;
    }

    /**
     * @param string  $givenDay
     * @param \GuzzleHttp\Psr7\Response $response
     * @return array
     */
    private function _getDataFromResponse(string $givenDay, \GuzzleHttp\Psr7\Response $response) : array
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

        foreach ($jsonData->daily->data as $idx => $dayData) {

            $day = $givenDay;

            $day = $day ? : date('Y-m-d', $dayData->time);

            if (!$givenDay && !$idx) {
                // skip 'yesterday' for the default view (current day and next 7 days)
                continue;
            }

            $itm = new \stdClass;

            $itm->dateIso      = $day;
            $itm->isToday      = $todayDate == $day;
            $itm->dayLabel     = date('l, d F Y', strtotime($day));
            $itm->summary      = $dayData->summary;
            $itm->weatherImage = $dayData->icon;
            $itm->dayTemp      = round($dayData->temperatureHigh) . ' ℃';
            $itm->nightTemp    = round($dayData->temperatureLow) . ' ℃';

            $result[] = $itm;
        }

        return $result;
    }
}