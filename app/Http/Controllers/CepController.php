<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CepController extends Controller
{
    public function show($cep)
    {

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            return response()->json([
                'message' => 'Invalid CEP. The CEP must have 8 digits.'
            ], 400);
        }

        $cachedAddress = Redis::get($cep);
        if ($cachedAddress) {
            return response()->json(['data' => json_decode($cachedAddress)]);
        }
        try {
            $response = file_get_contents(env('VIA_CEP_URL') . $cep . '/' . env('VIA_CEP_FORMAT'));
            $address = json_decode($response);

            if (isset($address->erro)) {
                return response()->json([
                    'message' => 'CEP not found. Check the CEP and try again.'
                ], 404);
            }

            Redis::setex($cep, 3600, $response);

            return response()->json(['data' => $address]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error to reach API and find CEP.'
            ], 500);
        }
    }
}
