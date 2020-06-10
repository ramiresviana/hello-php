<?php

require('functions.php');

$articles = getArticles();

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
        <a href="/article.php?id=<?= $key + 1 ?>">
            <article>
                <img src="/data/upload/<?= $article['image'] ?>">
                <div>
                    <h2><?= $article['title'] ?></h2>
                    <p><?= $article['content'] ?></p>
                </div>
            </article>
        </a>
<?php endforeach ?>
    </main>
</body>

</html>