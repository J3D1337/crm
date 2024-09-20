<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
</head>
<body>
    <h1>Create New Product</h1>

    <form action="/products/store" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="laptops">Laptops</option>
            <option value="smartphones">Smartphones</option>
            <option value="tvs">TVs</option>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required><br><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
