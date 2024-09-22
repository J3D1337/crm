<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>

    <form action="/products/update/<?= $product['id']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($product['description']); ?></textarea><br><br>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="laptops" <?= $product['category'] == 'laptops' ? 'selected' : ''; ?>>Laptops</option>
            <option value="smartphones" <?= $product['category'] == 'smartphones' ? 'selected' : ''; ?>>Smartphones</option>
            <option value="tvs" <?= $product['category'] == 'tvs' ? 'selected' : ''; ?>>TVs</option>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($product['quantity']); ?>" required><br><br>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
