<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationControllerTest extends TestCase
{
    /**
     * Test User Registration functionality
     *
     * @return void
     */
    
    public function migrate()
    {
        exec("php artisan migrate");
    }

    public function testUserRegistrationValid()
    {
        $this->visit('/auth/registration')
            ->see("First, Let's Get Your McGill E-mail and Password for Your New Account.")
            ->type('thomas.karatzas@mail.mcgill.ca', 'Email')
            ->type('Thomas', 'First_Name')
            ->type('Karatzas', 'Last_Name')
            ->type('8characterPass', 'Password')
            ->type('8characterPass', 'Confirm_Password')
            ->press('Submit')
            ->seePageIs('/flowchart');
    }
}
