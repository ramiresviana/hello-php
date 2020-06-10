<?php

require('functions.php');

if (!isLogged()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect();
}

$id = $_GET['id'];

$article = getArticle($id);

if ($article == null) {
    redirect();
}

if ($_POST) {
    removeArticle($id);
    redirect();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello HTML - Remove</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header class="text-center">
        <h1>Hello HTML</h1>
    </header>
    <main>
        <form class="form-center" method="POST">
            <h3>You want to remove?</h3>
            <button name="remove">Submit</button>
        </form>
    </main>
</body>

</html>