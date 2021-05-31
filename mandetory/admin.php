<?php

session_start();

$authenticated = false;
if (array_key_exists('user', $_SESSION) && $_SESSION['user']['admin']) $authenticated = true;

// Connect to the database:
$dbc = mysqli_connect('localhost', 'root', 'root', 'phpmandetory');

$editId = ($_GET['editId']) ?? null;
$deleteId = ($_GET['deleteId']) ?? null;
$messageId = ($_GET['message']) ?? null;
$message = '';

if ($messageId === 'updated') $message = 'The product has been updated!';
if ($messageId === 'added') $message = 'The product has been added!';
if ($deleteId) $message = 'The product has been deleted!';

$editProduct = false;
if ($editId) $editProduct = true;

// ----- Adding product -----

$priceKr = $_POST["price-kr"] ?? '';
$priceOre = $_POST["price-ore"] ?? '';

$name = $_POST['name'] ?? '';
$price = $priceKr . $priceOre ?? '';
$category = $_POST['category'] ?? '';
$subCategory = $_POST['subCategory'] ?? '';

if (!$editProduct && !$deleteId && $_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add the task to the database.
    $q = "INSERT INTO products (id, name, price, category, subCategory) VALUES (null, '$name', '$price', '$category', '$subCategory')";
    $r = mysqli_query($dbc, $q);

    header("Location: admin.php?message=added");
}


// ----- Deleting product -----


if ($deleteId && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $q = "DELETE FROM products WHERE id = {$deleteId}";
    $r = mysqli_query($dbc, $q);

    echo "Deleting!";

    header("Location: admin.php?deleteId=$deleteId&message=deleted");
}


// ----- Viewing products -----
$query = 'SELECT id, name, price, category, subCategory FROM products';
$response = mysqli_query($dbc, $query);

$products = [];

while ($product = mysqli_fetch_array($response)) {
    $products[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => (int)$product['price'],
        'category' => $product['category'],
        'subCategory' => $product['subCategory'],
        'image' =>
            str_replace("'", '',
                str_replace([' '], '-',
                    str_replace(" - ", '-',
                        strtolower($product['name']))
                )
            )
    ];
}

function formatPrice(int $cents): string
{
    return number_format(($cents / 100), 2, ',', '.') . " kr";
}

// ----- Editing product -----

$editingProduct = null;

if ($editProduct) {
    foreach ($products as $product_id => $product) {
        if ($product['id'] === $editId) $editingProduct = $product;
    }
}

if ($editProduct && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $q = "UPDATE products 
          SET   name        = '{$name}', 
                price       = '{$price}', 
                category    = '{$category}', 
                subCategory = '{$subCategory}' 
          WHERE id          =  {$editId}";

    $r = mysqli_query($dbc, $q);
    header("Location: admin.php?message=updated");
    die();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h1>Admin area</h1>

        <a class="return-button" href="index.php">&larr; Return to front page</a>

        <?php if ($message): ?>
            <div class="message">
                <?php echo $message ?>
            </div>
        <?php endif; ?>

        <?php if ($authenticated): ?>

        <?php if (!$editProduct): ?>
            <h2>Add new product</h2>
        <?php endif; ?>

        <?php if ($editProduct): ?>
            <h2>Edit product</h2>
        <?php endif; ?>

        <form action="admin.php<?php if ($editingProduct) echo "?editId=" . $editingProduct['id'] ?>" method="POST">
            <div class="input-container">
                <label for="name">Name<span class="required-star">*</span></label>
                <input type="text" name="name" id="name" required value="<?php echo $editingProduct['name'] ?? '' ?>"/>
            </div>

            <div class="split-input">
                <div class="input-container">
                    <label for="price-kr">Price<span class="required-star">*</span></label>
                    <div class="split-input">
                        <input type="number" name="price-kr" id="price-kr" required value="<?php echo substr($editingProduct['price'], 0, -2) ?? '' ?>"/>
                        <p>kr</p>
                    </div>
                </div>

                <div class="input-container">
                    <label for="price-ore" style="color: transparent;">Price</label>
                    <div class="split-input">
                        <input type="number" name="price-ore" id="price-ore" required minlength="2" value="<?php echo substr($editingProduct['price'], -2) ?? '' ?>"/>
                        <p>øre</p>
                    </div>
                </div>
            </div>

            <div class="input-container">
                <label for="category">Category<span class="required-star">*</span></label>
                <input type="text" name="category" id="category" required value="<?php echo $editingProduct['category'] ?? '' ?>"/>
            </div>

            <div class="input-container">
                <label for="subcategory">Subcategory</label>
                <input type="text" name="subCategory" id="subCategory" value="<?php echo $editingProduct['subCategory'] ?? '' ?>"/>
            </div>

            <?php if (!$editProduct): ?>
                <button type="submit">Add product</button>
            <?php endif; ?>

            <?php if ($editProduct): ?>
                <button type="submit">Save product</button>
            <?php endif; ?>

        </form>

        <!--  Edit products  -->

        <h2>Products list</h2>

        <table class="products-administration">
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Price</th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach ($products as $product_id => $product): ?>
                <tr>
                    <td><?php echo $product['name'] ?></td>
                    <td><?php echo $product['category'] ?></td>
                    <td><?php echo $product['subCategory'] ?></td>
                    <td><?php echo formatPrice($product['price']) ?></td>
                    <td> <a href="admin.php?editId=<?php echo $product['id'] ?>">Edit</a></td>
                    <td> <form action="admin.php?deleteId=<?php echo $product['id'] ?>" method="POST"> <button type="submit">Delete</button></form></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total-products">Total number of products: <?php echo count($products) ?></div>

        <?php endif ?>

        <?php if (!$authenticated): ?>
            <p class="warning">Restricted Area: requires authentication</p>

            <?php if (!isset($_SESSION['user'])): ?>
                <a class="return-button" href="login.php">Go to login</a>
            <?php endif ?>
        <?php endif ?>
    </main>
</body>
</html>