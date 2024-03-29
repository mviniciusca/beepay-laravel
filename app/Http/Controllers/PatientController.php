<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Patient;
use App\Rules\CNSValidation;
use App\Rules\CPFValidation;
use Illuminate\Http\Request;
use App\Http\Resources\PatientResource;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients
     * @return \Illuminate\Http\JsonResponse
     * @return PatientResource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $data = Validator::make(request()->all(), [
                'search' => ['required', 'string', 'max:255'],
            ]);

            if ($data->fails()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $data->errors()
                ], 400);
            }

            $search = $data->validated()['search'];

            $patients = Patient::query()
                ->where('full_name', 'like', '%' . $search . '%')
                ->paginate(10);

            if ($patients->isEmpty()) {
                return response()->json([
                    'message' => 'No patients found.',
                ], 404);
            }
            return PatientResource::collection($patients);
        }

        $patients = Patient::
            query()->paginate(10);

        if ($patients->isEmpty()) {
            return response()->json([
                'message' => 'No patients found.',
            ], 404);
        }
        return PatientResource::collection($patients);
    }

    /**
     * Create and save a new patient on database
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $patient_data = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'cpf' => ['required', 'integer', 'unique:patients', new CPFValidation],
            'cns' => ['required', 'integer', 'unique:patients', new CNSValidation],
            'picture' => ['nullable', 'string', 'max:255'],
        ]);

        $patient_address_data = Validator::make($request->all(), [
            'zip_code' => ['required', 'string', 'max:8'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:2'],
        ]);

        if ($patient_data->fails() || $patient_address_data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $patient_data->errors()->merge($patient_address_data->errors())
            ], 400);
        }

        $patient = Patient::create($patient_data->validated());

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

    public function update(Request $request, $id)
    {
        $patient_data = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'cpf' => ['required', 'string', 'max:11', new CPFValidation, 'unique:patients,cpf,' . $id],
            'cns' => ['required', 'string', 'max:15', new CNSValidation, 'unique:patients,cns,' . $id],
            'picture' => ['nullable', 'string', 'max:255'],
        ]);

        $patient_address_data = Validator::make($request->all(), [
            'zip_code' => ['required', 'string', 'max:8'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:2'],
        ]);

        if ($patient_data->fails() || $patient_address_data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $patient_data->errors()->merge($patient_address_data->errors())
            ], 400);
        }

        Patient::query()
            ->where('id', $id)
            ->update($patient_data->validated());

        Address::query()
            ->where('patient_id', $id)
            ->update($patient_address_data->validated());

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
        $data = Validator::make(['id' => $id], [
            'id' => ['required', 'integer', 'exists:patients,id'],
        ]);
        if ($data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $data->errors()
            ], 400);
        }
        return new PatientResource(
            Patient::query()
                ->where('id', $data->validated())->first()
        );
    }
}
