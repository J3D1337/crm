<!DOCTYPE html>
<html lang="en">
<head>
  <title>MVC CRM</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Prevent back navigation from showing cached pages -->
  <script type="text/javascript">
    // Force a page reload when navigating back
    window.onpageshow = function(event) {
      if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        // If the page is loaded from cache, reload it
        window.location.reload();
      }
    };
  </script>

</head>
<body>

<nav class="navbar navbar-expand-sm bg-light">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <?php if (isset($jwtUser)): ?>
          <!-- Show logout button and user's name if logged in -->
          <a class="nav-link" href="#">
            <?= htmlspecialchars($jwtUser['name']) ?>  <!-- Display username -->
          </a>
          <a class="nav-link" href="/logout">Logout</a>  <!-- Logout link -->
        <?php else: ?>
          <!-- Default state when not logged in -->
          <a class="nav-link" href="/login">Login</a>
          <a class="nav-link" href="/register">Register</a>
        <?php endif; ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/products">Products</a>
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
