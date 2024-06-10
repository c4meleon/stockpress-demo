<?php

declare(strict_types=1);

namespace App\Services;

class WeatherService
{
    public function getTemperature(float $latitude, float $longitude): ?float
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&hourly=temperature_2m&timezone=Europe%2FWarsaw&forecast_days=1";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data['hourly']['temperature_2m'][0] ?? null;
    }
}
