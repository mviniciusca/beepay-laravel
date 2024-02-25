<?php

namespace App\Jobs;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportPatientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->data;

        $patient = Patient::create([
            'full_name' => $data['full_name'],
            'mother_name' => $data['mother_name'],
            'birth_date' => $data['birth_date'],
            'cpf' => $data['cpf'],
            'cns' => $data['cns'],
            'picture' => $data['picture'],
        ]);
        Address::create([
            'zip_code' => $data['zip_code'],
            'street' => $data['street'],
            'number' => $data['number'],
            'complement' => $data['complement'],
            'district' => $data['district'],
            'city' => $data['city'],
            'state' => $data['state'],
            'patient_id' => $patient->id,
        ]);
    }
}
