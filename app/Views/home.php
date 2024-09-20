
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-sm bg-light">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <?php if (isset($_SESSION['user'])): ?>
          <!-- Show logout button and user's name if logged in -->
          <a class="nav-link" href="#">
            <?= htmlspecialchars($_SESSION['user']['name']) ?>  <!-- User's name -->
          </a>
          <a class="nav-link" href="/logout">Logout</a>
        <?php else: ?>
          <!-- Default state when not logged in -->
          <a class="nav-link" href="/login">Login</a>
          <a class="nav-link" href="/register">Register</a>
        <?php endif; ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link 2</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link 3</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid mt-3">
  <h3>MVC CRM</h3>
  <p>Welcome Home!</p>
</div>

</body>
</html>
