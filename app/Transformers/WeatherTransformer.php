<?php

namespace App\Transformers;

class WeatherTransformer extends AbstractTransformer
{
    /**
     * @param array $entry
     * @return array
     */
    function transform(array $entry): array
    {
        $result = [];

        $result['dateIso']      = $entry['day'];
        $result['isToday']      = date('Y-m-d') == $entry['day'];
        $result['dayLabel']     = date('l, d F Y', strtotime($entry['day']));
        $result['summary']      = $entry['summary'] ?? '';
        $result['weatherImage'] = $entry['icon'] ?? '';
        $result['dayTemp']      = round($entry['temperatureHigh']) . ' ℃';
        $result['nightTemp']    = round($entry['temperatureLow']) . ' ℃';

        return $result;
    }
}
