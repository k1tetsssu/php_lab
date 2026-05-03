<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Обработка отправки формы (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        exit('Ошибка безопасности: невалидный CSRF-токен.');
    }
    
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'title'        => trim($_POST['title'] ?? ''),
        'release_date' => trim($_POST['release_date'] ?? ''),
        'type'         => trim($_POST['type'] ?? ''),
        'genre_id'     => (int)($_POST['genre_id'] ?? 0),
        'rating'       => trim($_POST['rating'] ?? ''),
        'description'  => trim($_POST['description'] ?? ''),
        'watched_at'   => trim($_POST['watched_at'] ?? ''),
        'status'       => trim($_POST['status'] ?? ''),
    ];
    
    $errors = validateMovieData($data);
    
    if (!empty($errors)) {
        render('form', [
            'title'  => 'Редактирование',
            'errors' => $errors,
            'movie'  => $data, // Возвращаем введённые данные
            'genres' => getAllGenres(),
            'isEdit' => true,
        ]);
        exit;
    }
    
    if (updateMovie($id, $data)) {
        regenerateCsrfToken();
        header('Location: list.php');
        exit;
    }
    
    http_response_code(500);
    render('errors', ['title' => 'Ошибка', 'errors' => ['Не удалось обновить запись в базе данных.']]);
    exit;
}

// Загрузка данных для формы (GET)
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('Некорректный ID записи.');
}

$movie = getMovieById($id);
if (!$movie) {
    http_response_code(404);
    render('errors', [
        'title'  => 'Не найдено',
        'errors' => ['Запись с указанным ID не существует.']
    ]);
    exit;
}

render('form', [
    'title'  => 'Редактирование записи',
    'movie'  => $movie,
    'genres' => getAllGenres(),
    'isEdit' => true,
]);