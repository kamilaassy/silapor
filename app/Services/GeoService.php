<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoService
{
    private string $baseUrl = 'https://nominatim.openstreetmap.org/reverse';

    /**
     * Reverse geocode koordinat ke alamat menggunakan Nominatim (OpenStreetMap).
     * Gratis tanpa API key, tapi wajib pakai User-Agent dan rate limit 1 req/detik.
     */
    public function reverse(float $lat, float $lng): array
    {
        $cacheKey = 'geocode_' . round($lat, 5) . '_' . round($lng, 5);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($lat, $lng) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'SiLapor-App/1.0 (silapor.test)',
                ])->timeout(5)->get($this->baseUrl, [
                    'lat'            => $lat,
                    'lon'            => $lng,
                    'format'         => 'json',
                    'accept-language' => 'id',
                    'zoom'           => 18,
                ]);

                if (! $response->successful()) {
                    Log::warning('Nominatim API gagal', ['status' => $response->status()]);
                    return $this->empty();
                }

                $data    = $response->json();
                $address = $data['address'] ?? [];

                return [
                    'address'   => $data['display_name'] ?? null,
                    'kelurahan' => $address['village'] ?? $address['suburb'] ?? $address['neighbourhood'] ?? null,
                    'kecamatan' => $address['city_district'] ?? $address['county'] ?? null,
                ];
            } catch (\Throwable $e) {
                Log::error('GeoService error: ' . $e->getMessage());
                return $this->empty();
            }
        });
    }

    private function empty(): array
    {
        return ['address' => null, 'kelurahan' => null, 'kecamatan' => null];
    }
}
