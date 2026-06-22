<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private ?string $apiKey;
    private string $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    /**
     * Ambil kondisi cuaca saat ini berdasarkan koordinat.
     * Hasil di-cache 10 menit per lokasi (dibulatkan 2 desimal) supaya hemat quota API.
     */
    public function current(float $lat, float $lng): array
    {
        // Kalau API key belum diisi di .env, langsung kembalikan kosong tanpa request
        if (empty($this->apiKey)) {
            return $this->empty();
        }

        $cacheKey = 'weather_' . round($lat, 2) . '_' . round($lng, 2);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($lat, $lng) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl, [
                    'lat'   => $lat,
                    'lon'   => $lng,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang'  => 'id',
                ]);

                if (! $response->successful()) {
                    Log::warning('OpenWeather API gagal', ['status' => $response->status()]);
                    return $this->empty();
                }

                $data = $response->json();

                return [
                    'condition' => $data['weather'][0]['description'] ?? null,
                    'temp'      => $data['main']['temp'] ?? null,
                    'icon'      => $data['weather'][0]['icon'] ?? null,
                ];
            } catch (\Throwable $e) {
                Log::error('WeatherService error: ' . $e->getMessage());
                return $this->empty();
            }
        });
    }

    private function empty(): array
    {
        return ['condition' => null, 'temp' => null, 'icon' => null];
    }
}