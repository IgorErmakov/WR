<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @param string $name
     * @return array
     */
    static function findByName(string $name) : array
    {
        $items = self::select(['accent_city AS name', 'country', 'latitude', 'longitude'])
                 ->where('city', $name)
                 ->where('longitude', '<>', null)
                 ->where('latitude', '<>', null)
                 ->limit(15)
                 ->orderBy('country')
                 ->get();

        $list = $items->toArray();

        foreach ($list as &$itm) {
            $itm['country'] = strtoupper($itm['country']);
        }

        return $list;
    }
}
