<?php

require('functions.php');

if (!isLogged()) {
    redirect('login.php');
}

$result = null;

if ($_POST) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    $article = array(
        'title' => $title,
        'content' => $content,
        'image' => $image
    );

    $result = createArticle($article);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello HTML - New</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header>
        <h1>Hello HTML</h1>
    </header>
    <main>
        <form enctype="multipart/form-data" method="POST">
            <label>Title</label>
            <input name="title" />
            <label>Content</label>
            <textarea name="content" rows="6"></textarea>
            <label>Title</label>
            <input name="image" type="file" />
            <button>Submit</button>
        </form>
        <p><?= $result ?></p>
    </main>
</body>

</html>