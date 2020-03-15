<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JwtAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return User
     */
    private function createUser()
    {
        return factory(User::class)->create([
            'name' => 'admin', 'email' => 'admin@app', 'password' => 'admin',
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthenticableUser()
    {
        $this->createUser();
        $this->assertDatabaseHas('users', [
            'email' => 'admin@app',
        ]);
    }

    public function testJwtRestictedEndpoint()
    {
        $response = $this->json('POST', '/api/auth/me');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testAuthenticateAbilityWithWrongCredentials()
    {
        $this->createUser();
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'wrong@email', 'password' => 'wrong',
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testAuthenticateAbilityWithCorrectCredentials()
    {
        $this->createUser();
        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'admin@app', 'password' => 'admin',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testJwtTokenAccess()
    {
        $this->createUser();
        $jwtServiceResponse = $this->json('POST', '/api/auth/login', [
            'email' => 'admin@app', 'password' => 'admin',
        ]);
        $response = $this->json('POST', '/api/auth/me', [], [
            'Authentication: Bearer '.$jwtServiceResponse->json('access_token'),
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('admin@app', $response->json('email'));
    }
}
