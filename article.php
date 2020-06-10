<?php

require('functions.php');

$host = getHost();

if (!isset($_GET['id'])) {
    redirect();
}

$article = getArticle($_GET['id']);

if ($article == null) {
    redirect();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello HTML - Article</title>
    <link rel="stylesheet" href="styles.css"/>
</head>

<body>
    <header>
        <h1>Hello HTML</h1>
    </header>
    <main>
        <img class="full-img" src="data/upload/<?= $article['image'] ?>">
        <h2><?= $article['title'] ?></h2>
        <p><?= $article['content'] ?></p>

        <div class="admin-actions">
            <hr>
            <a href="#"><button>Edit</button></a>
            <a href="#"><button class="red">Remove</button></a>
        </div>
    </main>
</body>

</html>