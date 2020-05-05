<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class checkBalance implements Rule
{
    public $user;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= $this->user->balance;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $msg = 'Insufficient Fund, please fund your wallet';
        //request()->session()->flash('error', $msg);
        return $msg;
    }
}