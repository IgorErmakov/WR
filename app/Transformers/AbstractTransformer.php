<?php

namespace App\Transformers;

use \Illuminate\Support\Collection;
use \Illuminate\Database\Eloquent\Model;
use ArrayAccess;

class AbstractTransformer
{
    /**
     * @param Collection $collection
     * @return Collection
     */
    public function transformCollection(Collection $collection) : Collection
    {
        return $collection->transform(function($entry) {

            $entryArr = is_array($entry) ? $entry : $entry->toArray();

            return $this->transform($entryArr);
        });
    }

    /**
     * @param array $entry
     * @return array
     */
    function transform(array $entry) : array
    {
        return $entry;
    }
}