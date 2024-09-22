<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>

    <!-- Check if the user is logged in and is an admin -->
    <?php if (isset($jwtUser) && $jwtUser['role'] === 'admin'): ?>
        <a href="/products/create">Add New Product</a>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Quantity</th>
                <!-- Check if the user is logged in and is an admin to show actions -->
                <?php if (isset($jwtUser) && $jwtUser['role'] === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                    <!-- Only show edit and delete actions if the user is an admin -->
                    <?php if (isset($jwtUser) && $jwtUser['role'] === 'admin'): ?>
                        <td>
                            <a href="/products/edit/<?= $product['id'] ?>">Edit</a>
                            <a href="/products/delete/<?= $product['id'] ?>">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/">Home</a>

</body>
</html>
