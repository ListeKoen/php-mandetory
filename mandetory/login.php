<?php

session_start();

if ($_POST) {

    $dbc = mysqli_connect('localhost', 'root', 'root', 'phpmandetory');

    $query = 'SELECT id, name, email, phone, address, password, admin FROM users';
    $response = mysqli_query($dbc, $query);

    $users = [];

    while ($user = mysqli_fetch_array($response)) {
        $users[] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'address' => $user['address'],
            'password' => $user['password'],
            'admin' => (bool) $user['admin'],
        ];
    }

    foreach ($users as $user_id => $user) {
        if ($user['email'] === $_POST['email'] && $user['password'] === $_POST['password']) {

            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['name'] = $user['name'];
            $_SESSION['user']['phone'] = $user['phone'];
            $_SESSION['user']['address'] = $user['address'];
            $_SESSION['user']['email'] = $user['email'];
            $_SESSION['user']['admin'] = $user['admin'];
        }
    }

    print_r($_SESSION);
    header("Location: index.php");
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
        <h1>Log in</h1>
        <a class="return-button" href="index.php">&larr; Return to front page</a>
        <form id="login" action="login.php" method="POST" style="padding-top: 16px;">
            <div class="input-container">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"/>
            </div>
            <div class="input-container">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"/>
            </div>
            <button type="submit">Log in</button>
            <a href="signup.php">Do you need an account? Sign up</a>
        </form>
        <div class="information">
            <p>User login: notadmin@mail.com | 123</p>
            <p>Admin: admin@mail.com | 123</p>
        </div>
    </main>
</body>
</html>