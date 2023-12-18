<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove formatação do CPF
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            $fail('O CPF deve ter 11 dígitos.');
            return;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('CPF inválido.');
            return;
        }

        // Calcula e confere primeiro dígito verificador
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $fail('CPF inválido.');
                return;
            }
        }
    }
}
