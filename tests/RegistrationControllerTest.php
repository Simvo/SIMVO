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
    public function testUserRegistrationValid()
    {
         $this->visit('/auth/registration')
              ->see("First, Let's Get Your McGill E-mail and Password for Your New Account.")
              ->type('thomas.karatzas@mail.mcgill.ca', 'Email')
              ->type('Thomas', 'First_Name')
              ->type('Karatzas', 'Last_Name')
              ->type('8characterPass', 'Password')
              ->type('8characterPass', 'Confirm_Password')
              ->select(2,'Faculty')
              ->select(7, 'Semester')
              ->press('Submit')
              ->seePageIs('/flowchart');
    }

    // public function testUserRegistrationEmpty()
    // {
    //      $this->visit('/auth/userRegistration')
    //           ->see('New User Registration')
    //           ->type('', 'email')
    //           ->type('', 'firstname')
    //           ->type('', 'lastname')
    //           ->type('', 'password')
    //           ->type('', 'confirmPassword')
    //           ->select('','faculties')
    //           ->select('', 'majors')
    //           ->select('', 'enteringSemester');
    // }
    //
    // public function testUserRegistrationNull()
    // {
    //      $this->visit('/auth/userRegistration')
    //           ->see('New User Registration')
    //           ->type(null, 'email')
    //           ->type(null, 'firstname')
    //           ->type(null, 'lastname')
    //           ->type(null, 'password')
    //           ->type(null, 'confirmPassword')
    //           ->select(null,'faculties')
    //           ->select(null, 'majors');
    //           ->select(null, 'enteringSemester');
    // }
    //
    // public function testUserRegistrationInvalidEmail()
    // {
    //     $this->visit('/auth/userRegistration')
    //          ->see('New User Registration')
    //          ->type('thomas.karatzas@gmail.com', 'email')
    //          ->type('Thomas', 'firstname')
    //          ->type('Karatzas', 'lastname')
    //          ->type('8characterPass', 'password')
    //          ->type('8characterPass', 'confirmPassword')
    //          ->select('Engineering','faculties')
    //          ->select('Software Engineering', 'majors');
    //          ->select('Fall 2014', 'enteringSemester');
    // }
    //
    // public function testUserLogin()
    // {
    //   $this->visit('/auth')
    //        ->see('Login');
    // }
}
