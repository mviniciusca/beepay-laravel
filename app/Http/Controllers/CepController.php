<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CepController extends Controller
{
    public function show($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) != 8 || !is_numeric($cep)) {
            return response()->json([
                'message' => 'CEP invalid. Check the CEP and try again.'
            ], 400);
        }

        // try to reach api
        try {
            $response = file_get_contents(env('VIA_CEP_URL') . $cep . '/' . env('VIA_CEP_FORMAT'));
            $address = json_decode($response);
            if (isset($address->erro)) {
                return response()->json([
                    'message' => 'CEP not found. Check the CEP and try again.'
                ], 404);
            }
            return response()->json(['data' => $address]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error to reach API and find CEP.'
            ], 500);
        }
    }
}
