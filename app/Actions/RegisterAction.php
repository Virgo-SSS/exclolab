<?php

namespace App\Actions;

use App\Models\User;

class RegisterAction
{
    public function handle(array $data): void
    {
        User::query()->create($data);
    }
}
