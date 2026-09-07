<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_users_are_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/admin');

        $response->assertOk();
    }
}
