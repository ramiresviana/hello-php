<?php

require('functions.php');

if (!isLogged()) {
    redirect('login.php');
}

if ($_POST) {
    logout();
    redirect('login.php');
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
            <h3>You want to logout?</h3>
            <button name="logout">Submit</button>
        </form>
    </main>
</body>

</html>