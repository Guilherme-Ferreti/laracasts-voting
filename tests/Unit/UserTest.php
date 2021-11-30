<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_test_if_user_is_admin()
    {
        $user = User::factory()->make([
            'name' => 'Guilherme',
            'email' => 'guilherme@gmail.com',
        ]);

        $userB = User::factory()->make([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
        ]);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($userB->isAdmin());
    }
}
