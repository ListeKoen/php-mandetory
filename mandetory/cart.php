<?php

session_start();

$messageId = ($_GET['message']) ?? null;
$message = '';

if ($messageId === 'added') $message = 'The product has been updated in your cart!';

$productsInCart = [];

if (isset($_SESSION['cart'])) {

    function formatPrice(int $cents): string
    {
        return number_format(($cents / 100), 2, ',', '.') . " kr";
    }

    $dbc = mysqli_connect('localhost', 'root', 'root', 'phpmandetory');

    $query = 'SELECT id, name, price, category, subCategory FROM products';
    $response = mysqli_query($dbc, $query);

    while ($product = mysqli_fetch_array($response)) {
        foreach ($_SESSION['cart'] as $item_index => $item) {
            if ($product['id'] === $item['id']) {
                $productsInCart[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => (int)$product['price'],
                    'image' =>
                        str_replace("'", '',
                            str_replace([' '], '-',
                                str_replace(" - ", '-',
                                    strtolower($product['name']))
                            )
                        ),
                    'quantity' => $item['quantity']
                ];
            }
        }
    }

    $cartTotal = 0;

    foreach ($productsInCart as $product_index => $product) {
        $cartTotal += $product['price'] * $product['quantity'];
    }
}

//Update quantity of product in shopping cart

if ($_POST) {

    if ($_POST['quantity'] !== '0') {

        $adding_product['id'] = $_POST['product_id'];
        $adding_product['quantity'] = $_POST['quantity'];

        $_SESSION['cart'][$adding_product['id']] = $adding_product;
    }

    if ($_POST['quantity'] === '0') {
        unset ($_SESSION['cart'][$_POST['product_id']]);
    }

    header("Location: cart.php?message=added");
};
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
        <h1>Cart</h1>

        <a class="return-button" href="index.php">&larr; Return to front page</a>

        <?php if ($message): ?>
            <div class="message">
                <?php echo $message ?>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user']) && $productsInCart): ?>
            <p>Your cart is saved in your current session. To log in, <a href="login.php">click here</a>.</p>
        <?php endif ?>

        <?php if (isset($_SESSION['user'])): ?>
            <p>You are currently logged in as <?php echo $_SESSION['user']['name'] ?></p>
        <?php endif ?>

        <?php if ($productsInCart): ?>
            <div class="products">
                <?php foreach ($productsInCart as $product_index => $product): ?>
                    <div class="product">
                        <h3><?php echo $product['name'] ?></h3>

                        <img src="./images/<?php echo $product['image'] ?>.jpg">

                        <div class="cart-details">
                            <div class="cart-detail">
                                Unit price:<?php echo formatPrice($product['price']) ?>
                            </div>

                            <form action="cart.php?>" method="POST">
                                <input type="hidden" value="<?php echo $product['id'] ?>" name="product_id" id="product_id">
                                <label for="quantity">Quantity</label>
                                <div class="split-input">
                                    <input type="number" style="margin-right: 8px;" name="quantity" id="quantity" value="<?php echo $product['quantity'] ?>">
                                    <button type="submit">Update quantity</button>
                                </div>
                            </form>
                            <div class="cart-detail cart-product-total">
                                Total price:<?php echo formatPrice($product['price'] * $product['quantity']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif ?>

        <div class="cart-total">
            Total price:<?php echo formatPrice($cartTotal) ?>
        </div>

        <?php if (!$productsInCart): ?>
            <p>Your cart is empty! Go to the front page to add products.</p>
        <?php endif ?>
    </main>
</body>
</html>