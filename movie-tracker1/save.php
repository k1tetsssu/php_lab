<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Ошибка: разрешён только метод POST.';
    exit;
}

// Получение данных
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

// Валидация
$errors = validateMovieData($formData);

if (!empty($errors)) {
    render('errors', [
        'title' => 'Ошибка',
        'errors' => $errors
    ]);
    exit;
}

// Создание записи
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

    render('success', [
        'title' => 'Успешно'
    ]);
    exit;

} catch (RuntimeException $exception) {
    http_response_code(500);

    render('errors', [
        'title' => 'Ошибка сервера',
        'errors' => [$exception->getMessage()]
    ]);
    exit;
}