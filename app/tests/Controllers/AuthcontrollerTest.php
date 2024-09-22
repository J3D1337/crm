<?php

use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;
use App\Controllers\AuthController;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    private $mockDb;
    private $mockStmt;
    private $mockUserModel;
    private $authController;
    private $mockJwtHandler;


    protected function setUp(): void
{
    // Mock the PDOStatement
    $this->mockStmt = $this->createMock(PDOStatement::class);
    $this->mockStmt->method('bindParam')->willReturn(true);
    $this->mockStmt->method('execute')->willReturn(true);
    $this->mockStmt->method('fetch')->willReturn(false); // Simulate user not found for registration tests

    // Mock the PDO object
    $this->mockDb = $this->createMock(PDO::class);
    $this->mockDb->method('prepare')->willReturn($this->mockStmt); // Ensure it returns PDOStatement mock

    // Mock the User model
    $this->mockUserModel = $this->createMock(User::class); // Mock User model instead of instantiating it

    // Mock the JWTHandler for token creation
    $this->mockJwtHandler = $this->createMock(JWTHandler::class);
    $this->mockJwtHandler->method('createToken')->willReturn('mocked-jwt-token');

    // Mock the AuthController and inject the mocked dependencies
    $this->authController = $this->getMockBuilder(AuthController::class)
                                 ->setConstructorArgs([$this->mockDb])
                                 ->onlyMethods(['isGet', 'isPost', 'getInput', 'render', 'redirect'])
                                 ->getMock();

    // Inject the mocked User model and JWT handler into the AuthController
    $this->authController->userModel = $this->mockUserModel;
    $this->authController->jwt = $this->mockJwtHandler;  // Fix the undefined jwt property
}

    /**
     * @covers \App\Controllers\AuthController::register
     */
    public function testRegisterRendersViewIfGetRequest()
    {
        // Mock the isGet method to return true
        $this->authController->method('isGet')->willReturn(true);

        // Expect the render method to be called with 'register'
        $this->authController->expects($this->once())
                             ->method('render')
                             ->with('register');

        // Call the register method
        $this->authController->register();
    }

    /**
     * @covers \App\Controllers\AuthController::register
     */
    public function testRegisterHandlesExistingEmail()
{
    // Mock the isPost method to return true
    $this->authController->method('isPost')->willReturn(true);

    // Mock the input fields
    $this->authController->method('getInput')
                         ->will($this->returnValueMap([
                             ['name', 'John Doe'],
                             ['email', 'john@example.com'],
                             ['password', 'password123']
                         ]));

    // Simulate the email already being registered
    $this->mockUserModel->method('findByEmail')->willReturn(['id' => 1, 'email' => 'john@example.com']);

    // Expect the render method to be called with an error for existing email
    $this->authController->expects($this->once())
                         ->method('render')
                         ->with('register', ['error' => 'Email already registered']);

    // Call the register method
    $this->authController->register();
}

    /**
     * @covers \App\Controllers\AuthController::register
     */
    public function testRegisterCreatesNewUser()
{
    // Mock the isPost method to return true
    $this->authController->method('isPost')->willReturn(true);

    // Mock the input fields
    $this->authController->method('getInput')
                         ->will($this->returnValueMap([
                             ['name', 'John Doe'],
                             ['email', 'john@example.com'],
                             ['password', 'password123']
                         ]));

    // Simulate the email not being registered
    $this->mockUserModel->method('findByEmail')->willReturn(false);

    // Expect the userModel to register the new user
    $this->mockUserModel->expects($this->once())
                        ->method('register')
                        ->with('John Doe', 'john@example.com', 'password123');

    // Expect the redirect method to be called to login page
    $this->authController->expects($this->once())
                         ->method('redirect')
                         ->with('/login');

    // Call the register method
    $this->authController->register();
}

    /**
     * @covers \App\Controllers\AuthController::login
     */
    public function testLoginRendersViewIfGetRequest()
    {
        // Mock the isGet method to return true
        $this->authController->method('isGet')->willReturn(true);

        // Expect the render method to be called with 'login'
        $this->authController->expects($this->once())
                             ->method('render')
                             ->with('login');

        // Call the login method
        $this->authController->login();
    }

    /**
     * @covers \App\Controllers\AuthController::login
     */
    public function testLoginInvalidCredentials()
    {
        // Mock the isPost method to return true
        $this->authController->method('isPost')->willReturn(true);

        // Mock the input fields
        $this->authController->method('getInput')
                             ->will($this->returnValueMap([
                                 ['email', 'john@example.com'],
                                 ['password', 'wrongpassword']
                             ]));

        // Simulate user found by email
        $user = ['id' => 1, 'email' => 'john@example.com', 'password' => 'hashedpassword'];
        $this->mockUserModel->method('findByEmail')->willReturn($user);

        // Simulate password verification failing
        $this->mockUserModel->method('verifyPassword')->willReturn(false);

        // Expect the render method to be called with an error
        $this->authController->expects($this->once())
                             ->method('render')
                             ->with('login', ['error' => 'Invalid credentials']);

        // Call the login method
        $this->authController->login();
    }

    /**
     * @covers \App\Controllers\AuthController::login
     */
    public function testLoginSuccessAndRedirect()
{
    // Mock the isPost method to return true
    $this->authController->method('isPost')->willReturn(true);

    // Mock the input fields
    $this->authController->method('getInput')
                         ->will($this->returnValueMap([
                             ['email', 'john@example.com'],
                             ['password', 'password123']
                         ]));

    // Simulate user found by email
    $user = ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'hashedpassword', 'role' => 'admin'];
    $this->mockStmt->method('fetch')->willReturn($user);  // Simulate user found

    // Simulate password verification succeeding
    $this->mockUserModel->method('verifyPassword')->willReturn(true);

    // Simulate creating JWT token
    $this->authController->jwt->method('createToken')
                              ->willReturn('jwt-token');

    // Expect the cookie to be set for the token
    $this->expectOutputRegex('/^Set-Cookie: token=/');

    // Expect the redirect method to be called with '/'
    $this->authController->expects($this->once())
                         ->method('redirect')
                         ->with('/');

    // Call the login method
    $this->authController->login();
}

    /**
     * @covers \App\Controllers\AuthController::logout
     */
    public function testLogoutAndRedirect()
    {
        // Start output buffering to prevent "headers already sent" error
        ob_start();
    
        // Expect the cookie to be set to expire in the past
        $this->expectOutputRegex('/^Set-Cookie: token=/');
    
        // Expect the redirect method to be called with '/login'
        $this->authController->expects($this->once())
                             ->method('redirect')
                             ->with('/login');
    
        // Call the logout method
        $this->authController->logout();
    
        // Clean the output buffer to prevent "headers already sent" error
        ob_end_clean();
    }
}