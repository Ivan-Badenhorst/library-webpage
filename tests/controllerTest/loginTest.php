<?php

namespace App\Tests\controllerTest;

use ErrorResponse;
use LoginPage;
use PHPUnit\Framework\TestCase;
use RedirectResponse;
use Request;
use Router;
use Session;
use UserRepository;

class loginTest extends TestCase
{
    public function testLoginSuccess()
    {
        // create a mock user object with a valid email and password
        $user = new stdClass();
        $user->email = 'test@example.com';
        $user->password = password_hash('password123', PASSWORD_DEFAULT);

        // create a mock UserRepository object with the user object
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->getMock();
        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with('test@example.com')
            ->willReturn($user);

        // create a mock Request object with the email and password
        $request = $this->getMockBuilder(Request::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getPost')
            ->with('email')
            ->willReturn('test@example.com');
        $request->expects($this->once())
            ->method('getPost')
            ->with('password')
            ->willReturn('password123');

        // create a mock Session object
        $session = $this->getMockBuilder(Session::class)
            ->getMock();

        // create a mock Router object
        $router = $this->getMockBuilder(Router::class)
            ->getMock();

        // create a new LoginPage object with the mock objects
        $loginPage = new LoginPage($userRepository, $session, $router);

        // call the login method with the mock request object
        $response = $loginPage->login($request);

        // assert that the response is a redirect to the home page
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getUrl());
    }

    public function testLoginFailure()
    {
        // create a mock UserRepository object with no users
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->getMock();
        $userRepository->expects($this->once())
            ->method('findByEmail')
            ->with('test@example.com')
            ->willReturn(null);

        // create a mock Request object with an invalid email and password
        $request = $this->getMockBuilder(Request::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getPost')
            ->with('email')
            ->willReturn('test@example.com');
        $request->expects($this->once())
            ->method('getPost')
            ->with('password')
            ->willReturn('password123');

        // create a mock Session object
        $session = $this->getMockBuilder(Session::class)
            ->getMock();

        // create a mock Router object
        $router = $this->getMockBuilder(Router::class)
            ->getMock();

        // create a new LoginPage object with the mock objects
        $loginPage = new LoginPage($userRepository, $session, $router);

        // call the login method with the mock request object
        $response = $loginPage->login($request);

        // assert that the response is an error message
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->assertEquals('Invalid email or password', $response->getMessage());
    }
}
