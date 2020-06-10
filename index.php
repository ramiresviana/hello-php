<?php

require('functions.php');

// Pagination
$itemsPerPage = 5;
$articleCount = countArticles();
$numberOfPages = ceil($articleCount / $itemsPerPage);

$page = 1;

// Checks get parameter
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// Redirects on invalid page number
if ($page > $numberOfPages) {
    redirect();
}

$offset = $itemsPerPage * ($page - 1);
$articles = getArticles($itemsPerPage, $offset);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello HTML - Index</title>
    <link rel="stylesheet" href="styles.css"/>
</head>

<body>
    <header>
        <h1>Hello HTML</h1>
<?php if (isLogged()): ?>
        Logged as admin <div class="admin-actions"><a href="logout.php"><button class="red">Logout</button></a></div>
        There are <?= countArticles() ?> posts <div class="admin-actions"><a href="/new.php"><button>Add new</button></a></div>
<?php endif ?>
    </header>
    <main>
<?php foreach($articles as $key => $article): ?>
        <a href="/article.php?id=<?= $article['id'] ?>">
            <article>
                <img src="/data/upload/<?= $article['image'] ?>">
                <div>
                    <h2><?= $article['id'] ?></h2>
                    <p><?= $article['content'] ?></p>
                </div>
            </article>
        </a>
<?php endforeach ?>
    </main>
<?php if ($numberOfPages > 1) : ?>
    <footer>
<?php if ($page > 1) : ?>
        <a href="?page=<?= $page - 1 ?>"><button><-- Previous Page</button></a>
<?php endif ?>
<?php if ($page < $numberOfPages) : ?>
        <a href="?page=<?= $page + 1 ?>"><button>Next Page --></button></a>
<?php endif ?>
    </footer>
<?php endif ?>
</body>

</html>