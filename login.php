<?php

require('functions.php');

$error = null;

// Checks authentication
if (isLogged()) {
    redirect();
}

// Authenticate user credentials on form submit
if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $authenticated = authenticate($username, $password);

    if ($authenticated) {
        login();
        redirect();
    } else {
        $error = 'Invalid credentials';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello HTML - Login</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header class="text-center">
        <h1>Hello HTML</h1>
    </header>
    <main>
        <form class="form-center" method="POST">
            <label>Username</label>
            <input name="username" />
            <label>Password</label>
            <input name="password" type="password" />
            <button>Submit</button>
            <p><?= $error ?></p>
        </form>
    </main>
</body>

</html>