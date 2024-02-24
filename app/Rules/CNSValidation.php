<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CNSValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cns = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cns) !== 15) {
            $fail("The CNS must have 15 digits.");
            return;
        }

        $inicio = (int) $cns[0];

        if ($inicio === 1 || $inicio === 2) { // Rotina para números que iniciam com 1 ou 2
            $soma = 0;
            for ($i = 0; $i < 11; $i++) {
                $soma += (int) $cns[$i] * (15 - $i);
            }

            $resto = $soma % 11;
            $dv = $resto < 2 ? 0 : 11 - $resto;

            if ((int) substr($cns, -2) !== $dv) {
                $fail("Invalid CNS.");
                return;
            }
        } elseif ($inicio === 7 || $inicio === 8 || $inicio === 9) { // Rotina para números que iniciam com 7, 8 ou 9
            $soma = 0;
            for ($i = 0; $i < 15; $i++) {
                $soma += (int) $cns[$i] * (15 - $i);
            }

            if ($soma % 11 !== 0) {
                $fail("Invalid CNS.");
                return;
            }
        } else {
            $fail("The CNS must start with the digits 1, 2, 7, 8, or 9.");
            return;
        }
    }
}
