<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'mother_name' => $this->mother_name,
            'birth_date' => $this->birth_date,
            'cpf' => $this->cpf,
            'cns' => $this->cns,
            'picture' => $this->picture,
            'addresses' => $this->when($request->get('with_address'), $this->addresses->toArray()),
        ];
    }
}
