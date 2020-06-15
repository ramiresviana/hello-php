<?php

require('functions.php');

$method = $_SERVER['REQUEST_METHOD'];
$params = explode('/', $_SERVER['REQUEST_URI']);
$item = (isset($params[2]) && $params[2] != '') ? $params[2] : null;
$id = (isset($params[3]) && $params[3] != '') ? $params[3] : null;

header('Content-Type: application/json');

// Returns articles
if ($item == 'articles' && $method == 'GET') {
    // Pagination
    $itemsPerPage = 5;
    $articleCount = countArticles();
    $numberOfPages = ceil($articleCount / $itemsPerPage);

    $page = 1;

    // Checks get parameter
    if (isset($params[3])) {
        $page = $params[3];
    }

    // Redirects on invalid page number
    if ($page > $numberOfPages) {
        echo 'not_found';
        http_response_code(404);
        return;
    }

    $offset = $itemsPerPage * ($page - 1);
    $articles = getArticles($itemsPerPage, $offset);

    $result = new stdClass;
    $result->numberOfPages = $numberOfPages;
    $result->articles = $articles;

    echo json_encode($result);

    return;
}

// Returns an article
if ($item == 'article' && $method == 'GET' && $id != null) {
    $article = getArticle($id);

    // Checks for an valid article
    if (!$article) {
        echo 'not_found';
        http_response_code(404);
        return;
    }

    echo json_encode($article);

    return;
}

// Creates new article
if ($item == 'article' && $method == 'POST' && $id == null) {
    // Checks basic authentication fields
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        echo 'auth_required';
        http_response_code(401);
        return;
    }

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    // Checks credentials
    $authenticated = authenticate($username, $password);

    if (!$authenticated) {
        echo 'invalid_credentials';
        http_response_code(403);
        return;
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    $article = array(
        'title' => $title,
        'content' => $content,
        'image' => $image
    );

    $result = createArticle($article);

    echo $result;

    return;
}

// Updates an article
if ($item == 'article' && $method == 'POST' && $id != null) {
    // Checks basic authentication fields
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        echo 'auth_required';
        http_response_code(401);
        return;
    }

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    // Checks credentials
    $authenticated = authenticate($username, $password);

    if (!$authenticated) {
        echo 'invalid_credentials';
        http_response_code(403);
        return;
    }

    $article = getArticle($id);

    // Checks for an valid article
    if ($article == null) {
        echo 'not_found';
        http_response_code(404);
        return;
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    $newData = array(
        'title' => $title,
        'content' => $content,
        'image' => $image
    );

    $result = updateArticle($id, $newData);

    echo $result;

    return;
}

// Deletes an article
if ($item == 'article' && $method == 'DELETE' && $id != null) {
    // Checks basic authentication fields
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        echo 'auth_required';
        http_response_code(403);
        return;
    }

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    // Checks credentials
    $authenticated = authenticate($username, $password);

    if (!$authenticated) {
        echo 'invalid_credentials';
        http_response_code(401);
        return;
    }

    $article = getArticle($id);

    // Checks for an valid article
    if ($article == null) {
        echo 'not_found';
        http_response_code(404);
        return;
    }

    removeArticle($id);

    echo 'article_removed';

    return;
}

// Default action
echo 'not_found';
http_response_code(404);