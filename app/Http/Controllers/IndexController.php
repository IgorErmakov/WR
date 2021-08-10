<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 2019-02-09
 * Time: 21:57
 */

namespace App\Http\Controllers;


use App\Transformers\WeatherTransformer;
use Response;
use App\City;
use App\Weather;

use App\Repositories\CityRepository;
use App\Transformers\CityTransformer;

use App\Services\WeatherService;

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
     * @param CityRepository $repository
     * @param CityTransformer $transformer
     * @param string $name
     * @return mixed
     */
    function findCityByNameAction(CityRepository $repository, CityTransformer $transformer, $name)
    {
        $items = $repository->findByName($name);

        return Response::json(['items' => $transformer->transformCollection($items)]);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @param string $currentDay
     * @param string $direction
     * @return mixed
     */
    function getCityWeatherAction(WeatherService $weather,
                                  WeatherTransformer $transformer,
                                  $longitude,
                                  $latitude,
                                  $currentDay,
                                  $direction)
    {
        // validation
        $longitude   = (float)$longitude;
        $latitude    = (float)$latitude;
        $currentDay  = $currentDay;

        if (!in_array($direction, ['next', 'prev'])) {
            throw new Exception('Wrong direction');
        }

        if (!$longitude || !$latitude) {
            throw new Exception('Wrong coordinates');
        }

        if (empty($currentDay)) {
            $currentDate = null;
        } else {
            $currentDate = \DateTime::createFromFormat('Y-m-d', $currentDay);

            if (!$currentDate) {
                throw new Exception('Wrong date format');
            }
        }


        $items = $weather->getCityWeather(
            $longitude,
            $latitude,
            $currentDate,
            $direction
        );

        return Response::json(['items' => $transformer->transformCollection($items)]);
    }
}