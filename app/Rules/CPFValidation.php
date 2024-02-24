<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CPFValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cpf) != 11) {
            $fail("CPF must have 11 digits. Please, check the number and try again.");
            return;
        }


        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail("CPF invalid. All digits are the same.");
            return;
        }


        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ($cpf[$i] * (10 - $i));
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : (11 - $remainder);


        if ($cpf[9] != $digit1) {
            $fail("CPF is invalid. Please, check the number and try again.");
            return;
        }


        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += ($cpf[$i] * (11 - $i));
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : (11 - $remainder);


        if ($cpf[10] != $digit2) {
            $fail("CPF invalid. Please, check the number and try again.");
            return;
        }
    }
}
