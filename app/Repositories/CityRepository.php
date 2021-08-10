<?php
namespace App\Repositories;

use App\City;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{
    /**
     * @var City
     */
    protected City $_model;

    /**
     * Contructor
     */
    public function __construct()
    {
        $this->_model = new City;
    }

    /**
     * @param string $name
     * @return Collection
     */
    function findByName(string $name) : Collection
    {
        $fields = [
            'accent_city AS name',
            'country',
            'latitude',
            'longitude'
        ];

        $items = $this->_model::select($fields)
            ->where('city', $name)
            ->whereNotNull('longitude')
            ->whereNotNull('latitude')
            ->limit(15)
            ->orderBy('country')
            ->get();

        return $items;
    }
}