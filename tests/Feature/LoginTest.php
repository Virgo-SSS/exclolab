<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Test Login Validation
     *
     * @return void
     */
    public function test_validation(): void
    {
        $request = $this->test_cases();

        foreach($request as $key => $req) {
            $this->postJson(route('login'), $req['request'])->assertExactJson([
                'errors' => $req['errors']
            ]);
        }
    }

    /**
     * List of test case
     *
     * @return array[]
     */
    private function test_cases(): array
    {
        return [
            // Test email and password is required
            'test-case-1' => [
                'request' => [
                    'email' => '',
                    'password' => ''
                ],
                'errors' => [
                    'email' => [
                        "The email field is required."
                    ],
                    'password' => [
                        "The password field is required."
                    ]
                ]
            ],

            // Test email must exist at database and password must string
            'test-case-2' => [
                'request' => [
                    'email' => 'admin2@gmail.com',
                    'password' => 99999,
                    'password_confirmation' => 99999
                ],
                'errors' => [
                    'email' => [
                        "The selected email is invalid."
                    ],
                    'password' => [
                        "The password field must be a string."
                    ]
                ]
            ],
        ];
    }

    /**
     * Test User can't log in to the app if user email still not verified
     *
     * @return void
     */
    public function test_user_cant_login_if_not_verified_email(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertStatus(200)
            ->assertJson([
                "status" => "Error",
                'code' => "403",
                'message' => "Can't Login Must Verify Email First",
                "data" => [
                    'link' => 'https://www.google.com/'
                ]
            ]);

    }

    /**
     * Test User can log in to the app if already verified email
     *
     * @return void
     */
    public function test_user_can_login(): void
    {
        $user = User::Factory()->create();

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'remember_me' => true
        ])->assertStatus(200)
            ->assertJsonStructure([
                "status",
                'code',
                'message',
                "data"
            ]);
    }
}
