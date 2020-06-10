<?php

session_start();

//
// ARTICLE MANIPULATION
//

// Returns an array with article data
function getArticle($id) {
    $count = countArticles();

    // Checks if is a valid id
    if ($id == 0 || $id > $count) {
        return null;
    }

    // Get data from files
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

// Returns an array with all articles
function getArticles($limit = -1, $offset = 0, $desc = true) {
    $articles = array();

    $articleCount = countArticles();

    // Returns all articles
    if ($limit < 1) {
        $limit = $articleCount;
    }

    if ($desc) {
        $end = $articleCount - $offset;
        $start = $end - $limit + 1;

        // getLines wont work with negative values
        if ($start <= 0) {
            $start = 1;
        }
    } else {
        // Number of lines
        $start = $offset + 1;
        $limit = $limit - 1;

        // Ending on line
        $end = $start + $limit;
    }

    // Get data from files
    $titles = getLines('titles', $start, $end);
    $contents = getLines('contents', $start, $end);
    $images = getLines('images', $start, $end);

    // Creates array structure
    for ($i=0; $i < count($titles); $i++) {
        $articles[] = array(
            'id' => $start + $i,
            'title' => $titles[$i],
            'content' => $contents[$i],
            'image' => $images[$i]
        );
    }

    if ($desc) {
        $articles = array_reverse($articles);
    }

    return $articles;
}

// Inserts new article data in the files
function createArticle($article) {
    $title = $article['title'];
    $content = $article['content'];
    $image = $article['image'];

    $hasTitle = $title != '';
    $hasContent = $content != '';
    $hasImage = $image != null & !$image['error'];

    // Check if the fields are not empty
    $hasError = !($hasTitle && $hasContent && $hasImage);

    if ($hasError) {
        return 'An error ocurred';
    }

    $imageFilename = uploadImage($image);

    // Check if image upload has successful
    $hasError = $imageFilename == false;

    if ($hasError) {
        return 'An error ocurred';
    }

    // Replace line breaks with html tag
    $content = str_replace("\r\n", "<br>", $content);

    $fileSeparator = PHP_EOL;

    // Do not add line break if is the first line of the file
    if (countArticles() == 0) {
        $fileSeparator = '';
    }

    file_put_contents('data/titles', $fileSeparator . $title, FILE_APPEND);
    file_put_contents('data/contents', $fileSeparator . $content, FILE_APPEND);
    file_put_contents('data/images', $fileSeparator . $imageFilename, FILE_APPEND);

    return 'Article created';
}

// Updates an existing article from files by its id
function updateArticle($id, $newData) {
    $title = $newData['title'];
    $content = $newData['content'];
    $image = $newData['image'];

    // Check if the fields are not empty
    $hasTitle = $title != '';
    $hasContent = $content != '';
    $hasImage = $image != null & !$image['error'];

    $hasError = !($hasTitle && $hasContent);

    if ($hasError) {
        return 'An error ocurred';
    }

    $imageFilename = uploadImage($image, $id);

    // Check if image upload has successful
    $hasError = $imageFilename == false;

    if ($hasError) {
        return 'An error ocurred';
    }

    // Replace line breaks with html tag
    $content = str_replace("\r\n", "<br>", $content);

    // Remove old image
    removeImage($id);

    updateLine('titles', $id, $title);
    updateLine('contents', $id, $content);
    updateLine('images', $id, $imageFilename);

    return 'Article updated';
}

// Removes articles from files by its id
function removeArticle($id) {
    removeImage($id);

    removeLine('titles', $id);
    removeLine('contents', $id);
    removeLine('images', $id);
}

// Count all articles from titles file
function countArticles() {
    return countLines('titles');
}

// Checks and save images to upload folder
function uploadImage($image) {
    $allowed_ext = array('png', 'jpg');

    $basename = basename($image['name']);

    // Extract original extension
    $parts = explode('.', $basename);
    $extension_part = count($parts)-1;
    $extension = $parts[$extension_part];

    // Check if is a allowed extension
    if (!in_array($extension, $allowed_ext)) {
        return false;
    }

    // Generate filename based on timestamp
    $filename = time() . ".$extension";
    $destination = "data/upload/$filename";

    // Move file to upload folder
    if (move_uploaded_file($image['tmp_name'], $destination)) {
        return $filename;
    } else {
        return false;
    }
}

// Delete an image file based on article id
function removeImage($id) {
    $filename = trim(getLine('images', $id));
    unlink("data/upload/$filename");
}

//
// LOGIN FUNCTIONS
//

// Check username and password
function authenticate($username, $password) {
    return $username == 'admin' && $password == 'admin';
}

// Creates user session
function login() {
    $_SESSION['logged'] = true;
}

// Destroys user session
function logout() {
    session_destroy();
}

// Check session has logged attribute
function isLogged() {
    return isset($_SESSION['logged'] ) && $_SESSION['logged'] == true;
}

//
// DATA MANIPULATION
//

// Get line data from a file
function getLine($filename, $line) {
    $data = getLines($filename, $line, $line);

    if ($data != null) {
        return $data[0];
    } else {
        return null;
    }
}

// Returns an array with data of the specified lines range
function getLines($filename, $start, $end) {
    if (!file_exists("data/$filename")) {
        return false;
    }

    $result = array();

    // Starts at line 1
    $count = 1;

    $handle = fopen("data/$filename", "r");

    if (!$handle) {
        return null;
    }

    // Iterate over all the file until the end range or end of file
    while($count <= $end && !feof($handle)) {
        $data = fgets($handle);

        // Adds only specific line range to result array
        if ($count >= $start && $count <= $end) {
            $result[] = $data;
        }

        $count++;
    }

    fclose($handle);

    return $result;
}

// Updates data from a line in the files
function updateLine($filename, $line, $newData) {
    $count = 1;
    $handle = fopen("data/$filename", "r");

    // Working on a temporary file
    $tmp = "data/$filename-tmp";

    // First line to have a line break
    $firstline = ($line == 1 && $newData == null) ? 2 : 1;

    // Iterate over all the file until the end of file
    while(!feof($handle)) {
        $data = trim(fgets($handle));

        // Copy other lines that are not being updated
        if ($count != $line) {
            if ($count > $firstline) {
                file_put_contents($tmp, PHP_EOL, FILE_APPEND);
            }

            file_put_contents($tmp, $data, FILE_APPEND);
        } else {
            // Write new data for the specified line or not insert it if null
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

    // Rename temporary file to data file
    unlink("data/$filename");
    rename($tmp, "data/$filename");
}

// Removes a line in the files
function removeLine($filename, $line) {
    updateLine($filename, $line, null);
}

// Counts lines on a file
function countLines($filename) {
    if (!file_exists("data/$filename")) {
        return false;
    }

    $count = 0;
    $handle = fopen("data/$filename", "r");

    if (!$handle) {
        return false;
    }

    while(!feof($handle)){
        $line = fgets($handle);
        $count++;
    }

    fclose($handle);

    return $count;
}

//
// OTHER FUNCTIONS
//

// Redirects user to specified path in the host
function redirect($path = '') {
    $host = $_SERVER['HTTP_HOST'];

    header("Location: http://$host/$path");
    exit;
}