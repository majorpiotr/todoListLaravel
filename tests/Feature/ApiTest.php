<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Response;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_redirectGET()
    {
        $response = $this->call('GET','/api/todo');
        $response->assertRedirect('/api/login');
    }
    public function test_redirectPOST()
    {
        $response = $this->call('POST','/api/todo');
        $response->assertRedirect('/api/login');
    }
    public function test_redirectPUT()
    {
        $response = $this->call('PUT','/api/todo/1');
        $response->assertRedirect('/api/login');
    }
    public function test_redirectDELETE()
    {
        $response = $this->call('DELETE','/api/todo/1');
        $response->assertRedirect('/api/login');
    }
    public function test_createUserNoData()
    {
        $response = $this->call('post','/api/register');
        $response->assertStatus(422);
    }
    public function test_loginUserNoData()
    {
        $response = $this->call('post','/api/login');
        $response->assertStatus(422);
    }
    public function test_registerUserOK()
    {
        $response = $this->call('post','/api/register',['email' => 'testUser@testy.com','name'=>'testUser@testy.com','password'=>'test1111','password_confirmation'=>'test1111']);
        $response->assertStatus(200);
        $response->assertJson(['token' => true,]);
    }
    public function test_loginUserOK()
    {
        $response = $this->call('post','/api/login',['email' => 'testUser@testy.com','name'=>'testUser@testy.com','password'=>'test1111','password_confirmation'=>'test1111']);
        $response->assertStatus(200);
        $response->assertJson(['token' => true,]);
    }

    //$response = $this->call('POST', '/user', ['name' => 'Taylor']);
}
