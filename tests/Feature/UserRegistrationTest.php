<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    
    public function testRequiresValidEmail()
    {
        $response = $this->post('api/register', [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'invalidEmail'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testRequiresPhoneIsTooLong()
    {
        $response = $this->post('api/register', [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => '0801234567834653476'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('phone');
        $this->assertGuest();
    }

    public function testRequiresPasswordConfirmation()
    {
        $response = $this->post('api/register', [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password' => 'password'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
    
    public function testEmailMustNotExistInDatabase()
    {
        $user = User::factory()->create();

        $response = $this->post('api/register', [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => $user->email
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testUserIsSuccessfullyCreated()
    {   
        $response = $this->post('api/register', [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => '08047638464',
            'email' => "email@gmail.com",
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($response->message);
    }

    public function testAccountNumberWasCreatedForTheNewUser()
    {   
        $email = "myemail@gmail.com";

        $response = $this->post('api/register', [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => '08027638467',
            'email' => $email,
        ]);

        $user = User::where('email', $email);
        $accountExists = Account::where('user_id', $user->id)->exists();

        $response->assertStatus(200);
        $this->assertNotNull($response->message);
        $this->assertTrue((bool) $accountExists);
    }
}
