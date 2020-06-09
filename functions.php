<?php

function getArticles() {
    $articles = array();

    $count = countLines('titles');

    $titles = getLines('titles', 1, $count);
    $contents = getLines('contents', 1, $count);
    $images = getLines('images', 1, $count);

    for ($i=0; $i < $count; $i++) {
        $articles[] = array(
            'title' => $titles[$i],
            'content' => $contents[$i],
            'image' => $images[$i]
        );
    }

    return $articles;
}

function getArticle($id) {
    global $articles;

    return $articles[$id];
}

function getHost() {
    return $_SERVER['HTTP_HOST'];
}

function authenticate($username, $password) {
    return $username == 'admin' && $password == 'admin';
}

function createArticle($article) {
    $title = $article['title'];
    $content = $article['content'];
    $image = $article['image'];

    $hasTitle = $title != '';
    $hasContent = $content != '';
    $hasImage = $image != '';

    $hasError = !($hasTitle && $hasContent && $hasImage);

    if ($hasError) {
        return 'An error ocurred';
    }

    $content = str_replace("\r\n", "<br>", $content);

    file_put_contents('data/titles', PHP_EOL . $title, FILE_APPEND);
    file_put_contents('data/contents', PHP_EOL . $content, FILE_APPEND);

    return 'Article created';
}

function countLines($filename) {
    $count = 0;
    $handle = fopen("data/$filename", "r");

    while(!feof($handle)){
        $line = fgets($handle);
        $count++;
    }

    fclose($handle);

    return $count;
}

function getLine($filename, $line) {
    $data = getLines($filename, $line, $line);

    if ($data != null) {
        return $data;
    } else {
        return null;
    }
}

function getLines($filename, $start, $end) {
    $data = null;
    $count = 1;
    $handle = fopen("data/$filename", "r");

    while($count <= $end && !feof($handle)) {
        if ($count >= $start && $count <= $end) {
            $data[] = fgets($handle);
        }

        $count++;
    }

    fclose($handle);

    return $data;
}