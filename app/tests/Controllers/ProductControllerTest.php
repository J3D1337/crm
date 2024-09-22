<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ProductController;
use App\Models\Product;
use PDO;
use PDOStatement;

class ProductControllerTest extends TestCase
{
    /**
     * @covers \App\Controllers\ProductController::create
     */
    public function testCreateRedirectsIfNotAdmin()
    {
        // Mock the database connection object (if needed)
        $mockDb = $this->createMock(PDO::class);

        // Create a mock for the ProductController and mock the isAdmin, redirect, and render methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect', 'render']) // Mock the methods we need
                           ->getMock();

        // Mock the isAdmin method to return false (not an admin)
        $controller->method('isAdmin')->willReturn(false);

        // Expect the redirect method to be called with '/login'
        $controller->expects($this->once())
                   ->method('redirect')
                   ->with('/login');

        // Call the create method
        $controller->create();
    }

    /**
     * @covers \App\Controllers\ProductController::create
     */
    public function testCreateRendersCreateViewIfAdmin()
    {
        // Mock the database connection object (if needed)
        $mockDb = $this->createMock(PDO::class);

        // Create a mock for the ProductController and mock the isAdmin, redirect, and render methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect', 'render']) // Mock the methods we need
                           ->getMock();

        // Mock the isAdmin method to return true (user is an admin)
        $controller->method('isAdmin')->willReturn(true);

        // Expect the render method to be called with 'products/create'
        $controller->expects($this->once())
                   ->method('render')
                   ->with('products/create');

        // Call the create method
        $controller->create();
    }



    /**
     * @covers \App\Controllers\ProductController::store
     */
    public function testStoreAddsProductAndRedirectsIfAdmin()
    {
        // Mock the PDOStatement object
        $mockStmt = $this->createMock(PDOStatement::class);

        // Mock the PDO object and make the prepare method return the mocked statement
        $mockDb = $this->createMock(PDO::class);
        $mockDb->method('prepare')->willReturn($mockStmt);

        // Mock the ProductModel to use the mocked PDO object
        $mockProductModel = new Product($mockDb);

        // Expect the bindParam and execute methods to be called on the statement
        $mockStmt->expects($this->exactly(4))
                 ->method('bindParam');

        $mockStmt->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        // Create a mock for the ProductController and mock the isAdmin, redirect, and getInput methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect', 'getInput']) // Mock the methods we need
                           ->getMock();

        // Inject the mock product model into the controller
        $controller->productModel = $mockProductModel;

        // Mock the isAdmin method to return true (user is an admin)
        $controller->method('isAdmin')->willReturn(true);

        // Mock the getInput method to return specific values for each input
        $controller->method('getInput')
                   ->will($this->returnValueMap([
                       ['name', 'Test Product'],
                       ['description', 'Test Description'],
                       ['category', 'Test Category'],
                       ['quantity', 10]
                   ]));

        // Expect the redirect method to be called with '/products'
        $controller->expects($this->once())
                   ->method('redirect')
                   ->with('/products');

        // Call the store method
        $controller->store();
    }

    /**
     * @covers \App\Controllers\ProductController::edit
     */
    public function testEditRedirectsIfNotAdmin()
    {
        // Mock the database connection object (if needed)
        $mockDb = $this->createMock(PDO::class);

        // Create a mock for the ProductController and mock the isAdmin, redirect, and render methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect', 'render']) // Mock the methods we need
                           ->getMock();

        // Mock the isAdmin method to return false (not an admin)
        $controller->method('isAdmin')->willReturn(false);

        // Expect the redirect method to be called with '/login'
        $controller->expects($this->once())
                   ->method('redirect')
                   ->with('/login');

        // Call the edit method with a product ID
        $controller->edit(1);
    }

    /**
     * @covers \App\Controllers\ProductController::edit
     */
    public function testEditRendersEditViewIfAdmin()
    {
        // Mock the PDOStatement object
        $mockStmt = $this->createMock(PDOStatement::class);

        // Mock the PDO object and make the prepare method return the mocked statement
        $mockDb = $this->createMock(PDO::class);
        $mockDb->method('prepare')->willReturn($mockStmt);

        // Mock the ProductModel to use the mocked PDO object
        $mockProductModel = new Product($mockDb);

        // Mock the getProductById method to return a sample product
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('fetch')->willReturn([
            'id' => 1,
            'name' => 'Test Product',
            'description' => 'Test Description',
            'category' => 'Test Category',
            'quantity' => 10
        ]);

        // Create a mock for the ProductController and mock the isAdmin, redirect, and render methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect', 'render']) // Mock the methods we need
                           ->getMock();

        // Inject the mock product model into the controller
        $controller->productModel = $mockProductModel;

        // Mock the isAdmin method to return true (user is an admin)
        $controller->method('isAdmin')->willReturn(true);

        // Expect the render method to be called with 'products/edit' and the sample product
        $controller->expects($this->once())
                   ->method('render')
                   ->with('products/edit', [
                       'product' => [
                           'id' => 1,
                           'name' => 'Test Product',
                           'description' => 'Test Description',
                           'category' => 'Test Category',
                           'quantity' => 10
                       ]
                   ]);

        // Call the edit method with a product ID
        $controller->edit(1);
    }

    /**
     * @covers \App\Controllers\ProductController::update
     */
    public function testUpdateProductAndRedirectsIfAdmin()
    {
        // Mock the PDOStatement object
        $mockStmt = $this->createMock(PDOStatement::class);

        // Ensure that bindParam() and execute() can be called on the statement
        $mockStmt->expects($this->atLeast(4)) // Expect at least 4 calls to bindParam
                 ->method('bindParam');

        $mockStmt->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        // Mock the PDO object and make the prepare method return the mocked statement
        $mockDb = $this->createMock(PDO::class);
        $mockDb->method('prepare')->willReturn($mockStmt);

        // Mock the ProductModel to use the mocked PDO object
        $mockProductModel = $this->createMock(Product::class);

        // Expect the updateProduct method to be called once with the correct parameters
        $mockProductModel->expects($this->once())
                         ->method('updateProduct')
                         ->with(
                             $this->equalTo(1), // Product ID
                             $this->equalTo('Updated Product Name'),
                             $this->equalTo('Updated Description'),
                             $this->equalTo('Updated Category'),
                             $this->equalTo(5)
                         )
                         ->willReturn(true);

        // Create a mock for the ProductController and mock the isAdmin, redirect, and getInput methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect', 'getInput']) // Mock the methods we need
                           ->getMock();

        // Inject the mock product model into the controller
        $controller->productModel = $mockProductModel;

        // Mock the isAdmin method to return true (user is an admin)
        $controller->method('isAdmin')->willReturn(true);

        // Mock the getInput method to return specific values for each input
        $controller->method('getInput')
                   ->will($this->returnValueMap([
                       ['name', 'Updated Product Name'],
                       ['description', 'Updated Description'],
                       ['category', 'Updated Category'],
                       ['quantity', 5]
                   ]));

        // Expect the redirect method to be called with '/products'
        $controller->expects($this->once())
                   ->method('redirect')
                   ->with('/products');

        // Call the update method with a product ID
        $controller->update(1);
    }

    /**
     * @covers \App\Controllers\ProductController::delete
     */
    public function testDeleteRedirectsIfNotAdmin()
    {
        // Mock the database connection object
        $mockDb = $this->createMock(PDO::class);

        // Create a mock for the ProductController and mock the isAdmin and redirect methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect']) // Mock the methods we need
                           ->getMock();

        // Mock the isAdmin method to return false (not an admin)
        $controller->method('isAdmin')->willReturn(false);

        // Expect the redirect method to be called with '/login'
        $controller->expects($this->once())
                   ->method('redirect')
                   ->with('/login');

        // Call the delete method with a product ID
        $controller->delete(1);
    }

    /**
     * @covers \App\Controllers\ProductController::delete
     */
    public function testDeleteProductAndRedirectsIfAdmin()
    {
        // Mock the PDO object
        $mockDb = $this->createMock(PDO::class);

        // Mock the Product model
        $mockProductModel = $this->createMock(Product::class);

        // Expect the deleteProduct method to be called once with the correct product ID
        $mockProductModel->expects($this->once())
                         ->method('deleteProduct')
                         ->with($this->equalTo(1)) // The product ID being passed
                         ->willReturn(true);

        // Create a mock for the ProductController and mock the isAdmin, redirect methods
        $controller = $this->getMockBuilder(ProductController::class)
                           ->setConstructorArgs([$mockDb]) // Pass the mock database to the constructor
                           ->onlyMethods(['isAdmin', 'redirect']) // Mock the methods we need
                           ->getMock();

        // Inject the mock product model into the controller (ensure this is correct)
        $controller->productModel = $mockProductModel;

        // Mock the isAdmin method to return true (user is an admin)
        $controller->method('isAdmin')->willReturn(true);

        // Expect the redirect method to be called with '/products'
        $controller->expects($this->once())
                   ->method('redirect')
                   ->with('/products');

        // Call the delete method with a product ID
        $controller->delete(1);
    }
}