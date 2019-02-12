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

class IndexController extends Controller
{
    /**
     * Main page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        return view('welcome');
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
     * @return mixed
     */
    function getCityWeatherAction($longitude, $latitude)
    {
        $longitude = float($longitude);
        $latitude  = float($latitude);

        $items = [
            'today' => '15C'
        ];

        return Response::json(['items' => $items]);
    }
}