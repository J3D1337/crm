<?php 

use PHPUnit\Framework\TestCase;
use App\Controllers\HomeController;


class HomeControllerTest extends TestCase
{
    /**
     * @covers \App\Controllers\HomeController::index
     */
    public function testIndexRendersHomeViewWithCorrectData()
    {
        // Create a mock for the HomeController and mock the render method
        $controller = $this->getMockBuilder(HomeController::class)
                           ->onlyMethods(['render']) // Only mock the render method
                           ->getMock();

        // Define the expected data that will be passed to the render method
        $expectedData = [
            'title' => 'Welcome to the Home Page',
            'message' => 'This is the home page of your MVC application!',
        ];

        // Expect the render method to be called once with 'home' and the expected data
        $controller->expects($this->once())
                   ->method('render')
                   ->with('home', $expectedData);

        // Call the index method
        $controller->index();
    }
}