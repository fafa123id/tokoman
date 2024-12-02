<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\tokenRegister;

class TokenHashCheck implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tokenadmin = tokenRegister::where('role_id', 0)->first();
        $tokenemp = tokenRegister::where('role_id', 1)->first();

        return Hash::check($value, $tokenadmin->token) || Hash::check($value, $tokenemp->token);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided token is invalid.';
    }
}