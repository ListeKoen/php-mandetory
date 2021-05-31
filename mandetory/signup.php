<?php

$signupSuccess = false;

//Validation
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$password = $_POST['password'] ?? '';

function check_name($name) {
    return (bool)preg_match("/^[a-zæøå]+$/ix", $name);
}

function check_email($email) {
    return (bool)preg_match("/^[a-z0-9._-]+@([a-z0-9-]+.)+[a-z]{2,6}$/ix", $email);
}

function check_phone($phone) {
    return (bool)preg_match("/^([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])$/x", $phone);
}

function check_address($address) {
    return (bool)preg_match("/^([a-zæøå,]+[ ])+[0-9]+[,][ ][0-9]+[ ][a-zæøå,]+$/ix", $address);
}

$dbc = mysqli_connect('localhost', 'root', 'root', 'phpmandetory');

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    check_name($name) && check_email($email) && check_phone($phone) && check_address($address)) {

    //Add the task to the database.
    $q = "INSERT INTO users (id, name, phone, address, email, password) VALUES (null, '$name', '$phone', '$address', '$email', '$password')";
    $r = mysqli_query($dbc, $q);

    $signupSuccess = true;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h1>Sign up</h1>
        <?php if (!$signupSuccess): ?>
            <a class="return-button" href="index.php">&larr; Return to front page</a>
            <br><br>
            <form action="signup.php" method="POST">
                <div class="input-container">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name"/>
                    <?php if ($_POST && !check_name($name)) echo '<div class="validation-error">Name must only contain letters</div>' ?>
                </div>
                <div class="input-container">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"/>
                    <?php if ($_POST && !check_email($email)) echo '<div class="validation-error">Email must be formated as:<br><b>mail@domain.code</b></div>' ?>
                </div>
                <div class="input-container">
                    <label for="phone">Phone number</label>
                    <input type="text" name="phone" id="phone"/>
                    <?php if ($_POST && !check_phone($phone)) echo '<div class="validation-error">Phone number must be 8 digits</div>' ?>
                </div>
                <div class="input-container">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address"/>
                    <?php if ($_POST && !check_address($address)) echo '<div class="validation-error">Address must be formatted as:<br><b>Road 101, 1234 City</b></div>' ?>
                </div>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"/>
                </div>
                <button type="submit">Sign up</button>
                <a href="login.php">Already have an account? Log in</a>
            </form>
        <?php endif; ?>
        <?php if ($signupSuccess): ?>
            <h2>Sign up successfull!</h2>
            <a href="login.php">Click here to go to log in</a>
        <?php endif; ?>
    </main>
</body>
</html>