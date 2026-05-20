<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class LibelulaService
{
    public static function registrarDeuda(array $datos): array
    {
        $response = Http::post(config('services.libelula.url') . '/rest/deuda/registrar', array_merge([
            'appkey' => config('services.libelula.appkey'),
        ], $datos));

        return $response->json();
    }

    public static function consultarDeuda(string $identificador): array
    {
        $response = Http::post(config('services.libelula.url') . '/rest/deuda/consultar_deudas/por_identificador', [
            'appkey'        => config('services.libelula.appkey'),
            'identificador' => $identificador,
        ]);

        return $response->json();
    }

    public static function verificarPago(string $identificador): bool
    {
        try {
            $response = Http::post(config('services.libelula.url') . '/rest/deuda/consultar_deudas/por_identificador', [
                'appkey'        => config('services.libelula.appkey'),
                'identificador' => $identificador,
            ]);

            $data = $response->json();

            return isset($data['datos']['pagado']) && $data['datos']['pagado'] === true;
        } catch (\Exception $e) {
            return false;
        }
    }
}