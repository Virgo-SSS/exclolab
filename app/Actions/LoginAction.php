<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    /**
     * Function To handle user login
     *
     * @param array $data
     * @return bool
     */
    public function handle(array $data): bool
    {
        return Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember_me'] ?? false);
    }
}
