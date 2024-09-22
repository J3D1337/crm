## ABOUT

- A simple CRM app built using vanilla PHP following MVC practices
- User authentication
- Usage of JWT tokens
- Utilization of Docker for containerization
- PHPUnit used for testing

## CODE
 <strong>ROUTING AND CONFIGURATION</strong><br>

- <strong>.htaccess</strong> file enables URL rewriting, routing all requests that don't point to existing files or directories to index.php, and it also blocks access to sensitive files like .env, composer.json, and composer.lock to protect the application from exposure.<br>
- <strong>.env</strong> file contains database configuration settings, specifying that the MySQL server is hosted at db, uses port 3306, connects to the crm database with the root user, and authenticates using the password secret.<br>
- <strong>composer.json</strong> file specifies dependencies for the project, requiring the firebase/php-jwt package for JWT handling and phpunit/phpunit for testing in development. It also sets up PSR-4 autoloading for the App and Core namespaces, mapping them to the app/ and core/ directories.<br>
- <strong>index.php</strong> file is used for establishing a database connection, handling JWT authentication, and routing requests using the Router class. It defines routes for user authentication, product management, and a home view, and ensures that users are redirected to the login page if not authenticated.<br>
- <strong>Router</strong> class is responsible for registering and resolving GET and POST routes and matching incoming requests. It supports dynamic parameters in routes and executes the associated controller method, passing the necessary parameters, while also maintaining a database connection.<br>
- <strong>config.php</strong> file returns an array containing the database configuration, using environment variables for settings like host, port, database name, username, and password, with fallback default values. It also sets the database charset to utf8mb4 for better character encoding support.

<strong>CONTROLLERS</strong><br>

- <strong>Controller</strong> class provides common utility methods for handling HTTP responses, such as sending JSON responses, redirecting, sanitizing user input, checking request methods, retrieving GET/POST data, and rendering views. These methods are designed to simplify and standardize common tasks across various controllers in the application.<br>
- <strong>HomeController</strong> extends the base Controller class and defines an index method that prepares data, then renders the home view with that data, serving as the controller for the application's home page.<br>
- <strong>AuthController</strong> handles user authentication, including registration, login, and logout. It uses the User model to register and verify users, the JWTHandler to generate and manage JWT tokens for authentication, and redirects users to appropriate pages based on their actions.<br>
- <strong>ProductController</strong> manages product-related actions such as listing, creating, editing, and deleting products. It checks if the user is an admin using JWT authentication before allowing access to admin-only functions like creating, updating, or deleting products, and handles routing and rendering of product views.<br>
- <strong>ProtectedController</strong> provides access to routes that require authentication via a valid JWT token. It checks for the token in cookies, validates it using JWTHandler, and either grants access or returns an error message if the token is missing, invalid, or expired.<br>

<strong>MODELS</strong><br>

- <strong>User</strong> model handles database interactions related to user management, such as registering new users with a hashed password, finding a user by email, and verifying passwords. It uses prepared statements with PDO for secure database operations.<br>
- <strong>Product</strong> model handles CRUD operations for products in the database, including adding, retrieving, updating, and deleting products. It uses prepared statements with PDO for secure interactions with the database.<br>

<strong>DOCKER</strong><br>

- <strong>apache.conf</strong> file configures an Apache virtual host for a web application. It specifies the server to listen on port 80, sets the document root to /var/www/html/public, and grants full access to the specified directory. It enables URL rewriting to route all requests through index.php and logs errors and access to Apache's standard log directories. This configuration is used in a Docker container for serving the application.<br>
- <strong>Dockerfile</strong> sets up a PHP 8.1 Apache environment, installs necessary PHP extensions like pdo and pdo_mysql, enables Apache's mod_rewrite for clean URLs, and installs Composer for managing PHP dependencies and PHPUnit for testing. It sets the working directory to /var/www/html, where the application will be served.<br>
- <strong>docker-compose.yml</strong> file defines a multi-service Docker setup for an application. It includes two services: a PHP-Apache container (app) that maps a local XAMPP project directory to the container and uses a custom Apache config, and a MySQL database container (db). The two containers are connected via a custom network (mvc_network), and the database service stores data persistently in a Docker volume (db_data). The application is exposed on port 8080, and the database runs on port 3306.<br>

<strong>PHPUNIT</strong><br>

- <strong>phpunit.xml</strong> configuration file is for setting up PHPUnit, specifying test execution settings such as strict mode for annotations, handling of deprecations, and test coverage. It defines the test suite located in the app/tests directory, enables code coverage reporting for files in the src directory, and sets caching directories for test results and code coverage. It also enforces strict standards for risky tests and warnings, ensuring robust test practices.<br>
- <strongAuthControllerTest</strong> class is a PHPUnit test case that mocks the behavior of the AuthController to test its methods, such as user registration, login, and logout. It uses mock objects for the database, User model, and JWT handler to simulate different scenarios, including handling existing users, invalid credentials, successful login, and logout, ensuring the controller behaves as expected without relying on a real database or external services.
- <strong>HomeControllerTest</strong> class is a PHPUnit test case that verifies the index method of the HomeController. It mocks the render method and ensures that when index is called, the correct view (home) is rendered with the expected data, such as the title and message for the home page.
- <strong>ProductControllerTest</strong> class uses PHPUnit to test the ProductController, verifying its behavior when creating, editing, updating, and deleting products. It checks whether the controller correctly redirects non-admin users to the login page and whether it properly performs the expected actions (e.g., rendering views, adding products, or deleting products) when the user is an admin.
