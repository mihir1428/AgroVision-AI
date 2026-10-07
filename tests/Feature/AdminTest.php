<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminTest extends TestCase
{
    use RefreshDatabase;
    public function test_normal_user_cannot_open_admin(): void
    {
        $user=User::factory()->create(); $this->actingAs($user)->get('/admin')->assertForbidden();
    }
}
