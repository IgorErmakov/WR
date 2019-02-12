<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 2019-02-09
 * Time: 21:57
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Response;
use App\City;
use App\Weather;

class IndexController extends Controller
{
    /**
     * Main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        return view('index');
    }

    /**
     * @param string $name
     * @return mixed
     */
    function findCityByNameAction($name)
    {
        $items = City::findByName($name);

        return Response::json(['items' => $items]);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @param string $currentDay
     * @param string $direction
     * @return mixed
     */
    function getCityWeatherAction($longitude, $latitude, $currentDay, $direction)
    {
        $longitude   = (float)$longitude;
        $latitude    = (float)$latitude;
        $$currentDay = (int)$currentDay;

        if (!in_array($direction, ['0', 'next', 'prev'])) {
            throw new Exception('Wrong direction');
        }

        if (!$longitude || !$latitude) {
            throw new Exception('Wrong coordinates');
        }

        // @todo validate longitude

        $items = (new Weather)->getCityWeather(
            $longitude,
            $latitude,
            $currentDay,
            $direction
        );

        return Response::json(['items' => $items]);
    }
}