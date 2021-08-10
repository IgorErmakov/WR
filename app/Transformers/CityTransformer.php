<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;

class CityTransformer extends AbstractTransformer
{
    /**
     * @param array $entry
     * @return array
     */
    function transform(array $entry): array
    {
        $entry['country'] = strtoupper($entry['country']);

        return $entry;
    }
}
