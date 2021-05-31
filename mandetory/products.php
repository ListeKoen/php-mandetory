<?php

session_start();

$category = ($_GET['category']);
$subCategory = null;
if (isset($_GET['subCategory'])) $subCategory = $_GET['subCategory'];

    $messageId = ($_GET['message']) ?? null;
    $message = '';

if ($messageId === 'added') $message = 'The product has added to your cart!';

    function formatPrice(int $cents): string
    {
       return number_format(($cents / 100), 2, ',', '.') . " kr";
    }

// Connect to the database:
$dbc = mysqli_connect('localhost', 'root', 'root', 'phpmandetory');

$query = 'SELECT id, name, price, category, subCategory FROM products';
$response = mysqli_query($dbc, $query);

$products = [];

while ($product = mysqli_fetch_array($response)) {
    if ($product['category'] === $category && $product['subCategory'] === $subCategory ||
        $product['category'] === $category && !$subCategory) {
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
                ),
            'quantity' => $_SESSION['cart'][$product['id']]['quantity'] ?? ''
        ];
    }
}


// ----- Add products to cart -----

if ($_POST) {
    $adding_product['id'] = $_POST['product_id'];
    $adding_product['quantity'] = $_POST['quantity'];

    $_SESSION['cart'][$adding_product['id']] = $adding_product;

    $location = 'products.php?category=' . $category;
    if ($subCategory) {
        $location = $location . '&subCategory=' . $subCategory;
    };
    header("Location: $location&message=added");
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
        <h1>Products</h1>

        <div class="split">
            <a class="return-button" href="index.php">&larr; Return to front page</a>
            <a class="return-button" href="cart.php">Go to cart &rarr;</a>
        </div>

        <?php if ($message): ?>
            <div class="message">
                <?php echo $message ?>
            </div>
        <?php endif; ?>

        <h2>
            <?php echo $category ?>
            <?php if ($subCategory) echo '> ' . $subCategory ?>
        </h2>

        <div class="products">
            <?php foreach ($products as $product_id => $product): ?>
                <div class="product">
                    <h3><?php echo $product['name'] ?></h3>

                    <img src="./images/<?php echo $product['image'] ?>.jpg">

                    <div>
                        <div class="price">
                            <?php echo formatPrice($product['price']) ?>
                        </div>

                        <form action="products.php?category=<?php echo $category ?><?php if ($subCategory) echo "&subCategory=" . $subCategory ?>" method="POST">

                            <input type="hidden" value="<?php echo $product['id'] ?>" name="product_id" id="product_id">

                            <label for="quantity">Select amount</label>

                            <div class="split-input">
                                <input type="number" style="margin-right: 8px;" name="quantity" id="quantity" placeholder="Write the amount" value="<?php echo $product['quantity'] ?>">

                                <button type="submit">Add to cart</button>
                            </div>
                        </form>
                    </div> 
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>