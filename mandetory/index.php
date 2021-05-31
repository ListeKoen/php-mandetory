<?php

session_start();

if (isset($_GET['reset']) && $_GET['reset'] === 'hard') {
    session_destroy();
}

if (isset($_GET['reset']) && $_GET['reset'] === 'loggedout') {
    unset($_SESSION['user']);
}

$dbc = mysqli_connect('localhost', 'root', 'root', 'phpmandetory');

$query = 'SELECT id, name, price, category, subCategory FROM products';
$response = mysqli_query($dbc, $query);

$productCategories = [];

while ($product = mysqli_fetch_array($response)) {
    if (!isset($productCategories[$product['category']])) {
        $productCategories[$product['category']] = [];
    }

    if (!in_array($product['subCategory'], $productCategories[$product['category']]) && $product['subCategory'] !== '') {
        $productCategories[$product['category']][] = $product['subCategory'];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Landing page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div>
            <h2>Menu</h2>
            <ul>
                <?php if (!isset($_SESSION['user'])): ?>
                <li>
                    <a href="login.php"><b>Log in</b></a>
                </li>
                <?php endif ?>
                <li>
                    <a href="admin.php"><b>Admin area</b></a>
                </li>
                <li>
                    <a href="cart.php"><b>Cart</b></a>
                </li>
                <?php if (isset($_SESSION['user'])): ?>
                <li>
                    <a href="index.php?reset=loggedout">Log out</a>
                </li>
                <?php endif ?>
            </ul>
        </div>
    </header>
    <main>
        <h1>Web shop</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <?php echo "Welcome, " . $_SESSION['user']['name'] . "!" ?>
        <?php endif ?>

        <nav class="products-navigation">
            <h2>Product categories</h2>
            <?php foreach ($productCategories as $category => $productCategory): ?>
                <div class="nav-category">
                    <h3>
                        <a href='products.php?category=<?php echo $category ?>'>
                            <?php echo $category ?>
                        </a>
                    </h3>
                        <?php foreach ($productCategory as $subCategory_id => $subCategory): ?>
                        <h4>
                            <a href='products.php?category=<?php echo $category ?>&subCategory=<?php echo $subCategory ?>'>
                                <?php echo $subCategory ?>
                            </a>
                        </h4>
                        <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>
    </main>
</body>
</html>