<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * Test user can't log out if user do not have header token
     *
     * @return void
     */
    public function test_user_cant_logout_if_dont_have_token()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('basic-token')->plainTextToken;
        $this->actingAs($user);

        $this->withHeaders([
            'Authorization' => '',
            'Accept' => 'application/json'
        ]);

        $this->getJson(route('logout'))
            ->assertStatus(401)
            ->assertJson([
                "message" => "Unauthenticated."
            ]);
    }


    /**
     * Test User can log out if user has header with token
     *
     * @return void
     */
    public function test_user_can_logout_if_have_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('basic-token')->plainTextToken;
        $this->actingAs($user);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $this->getJson(route('logout'))
            ->assertStatus(200)
            ->assertJson([
                "status" => "Success",
                'code' => "200",
                'message' => "Success Logout",
                "data" => null
            ]);
    }
}
