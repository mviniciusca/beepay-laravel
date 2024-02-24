<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CNSValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-numeric characters from the CNS
        $cns = preg_replace('/[^0-9]/', '', $value);

        // CNS must be 15 digits long
        if (strlen($cns) !== 15) {
            $fail('The CNS must be 15 digits long.');
            return;
        }

        // The first digit of the CNS must be 1, 2, 7, 8, or 9
        $firstDigit = (int) $cns[0];

        // Routine for numbers starting with 1 or 2
        if ($firstDigit === 1 || $firstDigit === 2) { // Routine for numbers starting with 1 or 2
            $sum = 0;
            for ($i = 0; $i < 11; $i++) {
                $sum += (int) $cns[$i] * (15 - $i);
            }

            $remainder = $sum % 11;
            $dv = $remainder < 2 ? 0 : 11 - $remainder;

            if ((int) substr($cns, -2) !== $dv) {
                $fail('Invalid CNS number.');
                return;
            }
        } elseif ($firstDigit === 7 || $firstDigit === 8 || $firstDigit === 9) {

            // Routine for numbers starting with 7, 8 or 9
            $sum = 0;
            for ($i = 0; $i < 15; $i++) {
                $sum += (int) $cns[$i] * (15 - $i);
            }

            // The sum must be divisible by 11
            if ($sum % 11 !== 0) {
                $fail('Invalid CNS number.');
                return;
            }
        } else {
            $fail('The CNS must start with the digits 1, 2, 7, 8, or 9.');
            return;
        }
    }
}
