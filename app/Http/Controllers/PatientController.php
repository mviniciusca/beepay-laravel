<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Address;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{


    /**
     * Display a listing of the patients.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $patients = Patient::query()
            ->select()
            ->paginate(10);
        return PatientResource::collection($patients);
    }

    /**
     * Create a new patient on database
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $patient_data = Validator::make(request()->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'cpf' => ['required', 'string', 'max:11', 'unique:patients'],
            'cns' => ['required', 'string', 'max:15', 'unique:patients'],
            'picture' => ['nullable', 'string', 'max:255'],
        ]);

        if ($patient_data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $patient_data->errors()
            ], 400);
        }

        $patient = Patient::create($patient_data->validated());

        $patient_address_data = Validator::make(request()->all(), [
            'zip_code' => ['required', 'string', 'max:8'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:2'],
        ]);

        if ($patient_address_data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $patient_address_data->errors()
            ], 400);
        }

        $patient_address = $patient_address_data->validated();
        $patient_address['patient_id'] = $patient->id;

        Address::create($patient_address);

        return response()->json([
            'message' => 'Patient created successfully!',
        ], 201);
    }

    /**
     * Remove the specified patient from database
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $data = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', 'exists:patients,id'],
        ]);
        if ($data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $data->errors()
            ], 400);
        }
        Patient::destroy($data->validated());
        return response()->json([
            'message' => 'Patient deleted successfully!',
        ], 200);
    }



    public function update($id)
    {
        $patient_data = request()->only(['full_name', 'mother_name', 'birth_date', 'cpf', 'cns', 'picture']);
        $patient_address = request()->only(['zip_code', 'street', 'number', 'complement', 'district', 'city', 'state']);

        Patient::query()->where('id', $id)->update($patient_data);

        Address::query()->where('patient_id', $id)->update($patient_address);

        return response()->json([
            'message' => 'Patient updated successfully!',
        ], 200);
    }

    /**
     * Display the specified patient
     * @return PatientResource
     */
    public function show($id)
    {
        return new PatientResource(Patient::query()->where('id', $id)->first());
    }
}
