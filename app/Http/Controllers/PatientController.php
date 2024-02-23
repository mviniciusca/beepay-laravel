<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'picture' => 'required',
            'full_name' => 'required',
            'mother_name' => 'required',
            'birth_date' => 'required',
            'CPF' => 'required',
            'CNS' => 'required',
            'address_id' => 'required',
        ]);

        $patient = Patient::create($data);

        return [
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ];
    }
}
