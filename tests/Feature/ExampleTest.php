<?php

namespace Tests\Feature;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_tela_registro()
    {
        $response = $this->get('/register');
        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function test_tela_login()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_registrar_usuario()
    {
        $response = $this->post('/register', [
            'name' => 'felipe',
            'email' => 'felipe@email.com',
            'password' => '12345678',
            'confirm-password' => '12345678'
        ]);
        $response->assertSuccessful();
    }
    
    public function test_usuario_logar()
    {
        $user = factory(User::class)->create([
            'name' => 'felipe',
            'email' => 'felipe@email.com',
            'password' => '12345678',
            'confirm-password' => '12345678'
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_usuario_logar_com_dados_errados()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_usuario_logado_nao_vizualizar_tela_login()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/login');
    }
   
}
