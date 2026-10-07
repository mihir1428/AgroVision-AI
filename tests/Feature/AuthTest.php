<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_register(): void
    {
        $response=$this->post('/register',['name'=>'Student','email'=>'student@example.com','password'=>'Password123!','password_confirmation'=>'Password123!']);
        $response->assertRedirect('/dashboard'); $this->assertAuthenticated();
    }
}
