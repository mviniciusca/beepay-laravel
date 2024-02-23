<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    /**
     * Create a new patient on database
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = Validator::make(request()->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'cpf' => ['required', 'string', 'max:11', 'unique:patients'],
            'cns' => ['required', 'string', 'max:15', 'unique:patients'],
            'picture' => ['nullable', 'string', 'max:255'],
            'address_id' => ['required', 'integer']
        ]);

        if ($data->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $data->errors()
            ], 400);
        }

        $data = $data->validated();

        Patient::create($data);

        return response()->json([
            'message' => 'Patient created successfully',
        ], 201);


    }
}
