<?php

$articles = array(
    array(
        'title' => 'Vivamus euismod a tellus eget interdum. Aenean ac.',
        'content' => 'Aliquam vulputate mi in vulputate aliquam. Mauris ultrices vel felis eget tempus. Morbi a est at lacus malesuada ultrices ac quis turpis. Curabitur ante metus, malesuada eget neque eu, ornare suscipit ligula. Aliquam suscipit cursus eros, ut tincidunt nulla laoreet a. Donec aliquam urna vel pellentesque sodales.',
        'image' => 'img.jpg'
    ),
    array(
        'title' => 'Vivamus euismod a tellus eget interdum. Aenean ac.',
        'content' => 'Aliquam vulputate mi in vulputate aliquam. Mauris ultrices vel felis eget tempus. Morbi a est at lacus malesuada ultrices ac quis turpis. Curabitur ante metus, malesuada eget neque eu, ornare suscipit ligula. Aliquam suscipit cursus eros, ut tincidunt nulla laoreet a. Donec aliquam urna vel pellentesque sodales.',
        'image' => 'img.jpg'
    ),
    array(
        'title' => 'Vivamus euismod a tellus eget interdum. Aenean ac.',
        'content' => 'Aliquam vulputate mi in vulputate aliquam. Mauris ultrices vel felis eget tempus. Morbi a est at lacus malesuada ultrices ac quis turpis. Curabitur ante metus, malesuada eget neque eu, ornare suscipit ligula. Aliquam suscipit cursus eros, ut tincidunt nulla laoreet a. Donec aliquam urna vel pellentesque sodales.',
        'image' => 'img.jpg'
    )
);

function getArticles() {
    global $articles;

    return $articles;
}

function getArticle($id) {
    global $articles;

    return $articles[$id];
}

function getHost() {
    return $_SERVER[HTTP_HOST];
}

function authenticate($username, $password) {
    return $username == 'admin' && $password == 'admin';
}

function createArticle($article) {
    $hasTitle = $article['title'] != '';
    $hasContent = $article['content'] != '';
    $hasImage = $article['image'] != '';

    if ($hasTitle && $hasContent && $hasImage) {
        return 'Article created';
    } else {
        return 'An error ocurred';
    }
}