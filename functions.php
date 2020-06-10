<?php

function getArticles() {
    $articles = array();

    $count = countArticles();

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
    $count = countArticles();

    if ($id == 0 || $id > $count) {
        return null;
    }

    $title = getLine('titles', $id);
    $content = getLine('contents', $id);
    $image = getLine('images', $id);

    $article = array(
        'title' => $title,
        'content' => $content,
        'image' => $image
    );

    return $article;
}

function countArticles() {
    return countLines('titles');
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
    $hasError = !uploadImage($image);

    if ($hasError) {
        return 'An error ocurred';
    }

    $content = str_replace("\r\n", "<br>", $content);

    file_put_contents('data/titles', PHP_EOL . $title, FILE_APPEND);
    file_put_contents('data/contents', PHP_EOL . $content, FILE_APPEND);

    return 'Article created';
}

function updateArticle($id, $newData) {
    $title = $newData['title'];
    $content = $newData['content'];
    $image = $newData['image'];

    $hasTitle = $title != '';
    $hasContent = $content != '';
    $hasImage = $image != null & !$image['error'];

    $hasError = !($hasTitle && $hasContent);

    if ($hasError) {
        return 'An error ocurred';
    }

    $hasError = !uploadImage($image, $id);

    if ($hasError) {
        return 'An error ocurred';
    }

    $content = str_replace("\r\n", "<br>", $content);

    updateLine('titles', $id, $title);
    updateLine('contents', $id, $content);

    return 'Article updated';
}

function removeArticle($id) {
    removeLine('titles', $id);
    removeLine('contents', $id);
    removeLine('images', $id);
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
        return $data[0];
    } else {
        return null;
    }
}

function getLines($filename, $start, $end) {
    $result = array();
    $count = 1;
    $handle = fopen("data/$filename", "r");

    while($count <= $end && !feof($handle)) {
        $data = fgets($handle);

        if ($count >= $start && $count <= $end) {
            $result[] = $data;
        }

        $count++;
    }

    fclose($handle);

    return $result;
}

function uploadImage($image) {
    $allowed_ext = array('png', 'jpg');

    $basename = basename($image['name']);
    $parts = explode('.', $basename);
    $extension_part = count($parts)-1;
    $extension = $parts[$extension_part];

    if (!in_array($extension, $allowed_ext)) {
        return false;
    }

    $filename = countArticles() + 1 . ".$extension";
    $destination = 'data/upload/' . $filename;

    file_put_contents('data/images', PHP_EOL . $filename, FILE_APPEND);

    return move_uploaded_file($image['tmp_name'], $destination);
}

function redirect($path = '') {
    $host = getHost();

    header("Location: http://$host/$path");
    exit;
}

function login() {
    $_SESSION['logged'] = true;
}

function logout() {
    session_destroy();
}

function isLogged() {
    return $_SESSION['logged'] == true;
}

function updateLine($filename, $line, $newData) {
    $count = 1;
    $handle = fopen("data/$filename", "r");

    $tmp = "data/$filename-tmp";
    $firstline = ($line == 1 && $newData == null) ? 2 : 1;

    while(!feof($handle)) {
        $data = trim(fgets($handle));

        if ($count != $line) {
            if ($count > $firstline) {
                file_put_contents($tmp, PHP_EOL, FILE_APPEND);
            }

            file_put_contents($tmp, $data, FILE_APPEND);
        } else {
            if ($newData != null) {
                if ($count > $firstline) {
                    file_put_contents($tmp, PHP_EOL, FILE_APPEND);
                }

                file_put_contents($tmp, $newData, FILE_APPEND);
            }
        }

        $count++;
    }

    fclose($handle);

    unlink("data/$filename");
    rename($tmp, "data/$filename");
}

function removeLine($filename, $line) {
    updateLine($filename, $line, null);
}

session_start();