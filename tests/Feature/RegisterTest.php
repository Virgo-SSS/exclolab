<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Test Register Validation
     *
     * @return void
     */
    public function test_validation(): void
    {
        $request = $this->test_cases();

        foreach($request as $key => $req) {
            if($key == 'test-case-2') {
                User::factory()->create([
                    'email' => 'admin2@gmail.com',
                ]);
            }
            $response = $this->postJson(route('register'), $req['request'])->assertExactJson([
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
            // if email and password is required
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

            // if email must be unique and password must string
            'test-case-2' => [
                'request' => [
                    'email' => 'admin2@gmail.com',
                    'password' => 99999,
                    'password_confirmation' => 99999
                ],
                'errors' => [
                    'email' => [
                        "The email has already been taken."
                    ],
                    'password' => [
                        "The password field must be a string."
                    ]
                ]
            ],

            // test if password must have confirmation password
            'test-case-3' => [
                'request' => [
                    'email' => 'test@admin.com',
                    'password' => 'test password confirmed'
                ],
                'errors' => [
                    'password' => [
                        "The password field confirmation does not match."
                    ]
                ]
            ]
        ];
    }

    /**
     * Test user can register or not
     *
     * @return void
     */
    public function test_user_can_register(): void
    {
        $this->postJson(route('register'), [
            'email' => 'admintest@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ])->assertStatus(200)
            ->assertJson([
                "status" => "Success",
                'code' => 200,
                'message' => "Successfully Register",
                "data" => null
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admintest@gmail.com',
        ]);
    }
}
