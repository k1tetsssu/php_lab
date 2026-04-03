<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Ошибка: разрешён только метод POST.';
    exit;
}

$title = trim($_POST['title'] ?? '');
$releaseDate = trim($_POST['release_date'] ?? '');
$type = trim($_POST['type'] ?? '');
$genre = trim($_POST['genre'] ?? '');
$rating = trim($_POST['rating'] ?? '');
$description = trim($_POST['description'] ?? '');
$watchedAt = trim($_POST['watched_at'] ?? '');
$status = trim($_POST['status'] ?? '');

$formData = [
    'title' => $title,
    'release_date' => $releaseDate,
    'type' => $type,
    'genre' => $genre,
    'rating' => $rating,
    'description' => $description,
    'watched_at' => $watchedAt,
    'status' => $status,
];

$errors = validateMovieData($formData);

if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибки валидации</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body>
    <div class="container">
        <h1>Ошибка при сохранении</h1>

        <div class="error-list">
            <h2>Найдены ошибки:</h2>
            <ul>
                <?php 
                foreach ($errors as $error) {
                    echo '<li>' . e($error) . '</li>';
                }    
                ?>
            </ul>
        </div>

        <div class="actions">
            <a href="index.php">Вернуться к форме</a>
            <a href="list.php">Перейти к списку</a>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

$newMovie = [
    'title' => $title,
    'release_date' => $releaseDate,
    'type' => $type,
    'genre' => $genre,
    'rating' => (int)$rating,
    'description' => $description,
    'watched_at' => $watchedAt,
    'status' => $status,
    'created_at' => date('Y-m-d'),
];

try {
    $movies = loadData(DATA_FILE);
    $movies[] = $newMovie;
    saveData(DATA_FILE, $movies);
} catch (RuntimeException $exception) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ошибка сохранения</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body>
    <div class="container">
        <h1>Внутренняя ошибка</h1>

        <div class="error-list">
            <p><?= e($exception->getMessage()) ?></p>
        </div>

        <div class="actions">
            <a href="index.php">Вернуться к форме</a>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успешное сохранение</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Запись успешно сохранена</h1>

    <div class="success-message">
        <p>Фильм или сериал был успешно добавлен в коллекцию.</p>
    </div>

    <div class="actions">
        <a href="index.php">Добавить ещё одну запись</a>
        <a href="list.php">Посмотреть список</a>
    </div>
</div>
</body>
</html>