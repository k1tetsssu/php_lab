<?php
declare(strict_types=1);

require_once __DIR__ . '/validators/ValidatorInterface.php';
require_once __DIR__ . '/validators/RequiredValidator.php';
require_once __DIR__ . '/validators/LengthValidator.php';
require_once __DIR__ . '/validators/DateValidator.php';
require_once __DIR__ . '/validators/InArrayValidator.php';
require_once __DIR__ . '/validators/RatingValidator.php';
require_once __DIR__ . '/Database.php';      // ✅ Теперь путь верный
require_once __DIR__ . '/csrf.php';

function render(string $template, array $params = []): void
{
    extract($params, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../templates/layout.php';
}

function loadData(string $fileName): array
{
    if (!file_exists($fileName)) return [];
    if (!is_readable($fileName)) throw new RuntimeException("Не удалось прочитать файл: $fileName");
    $content = file_get_contents($fileName);
    if ($content === false) throw new RuntimeException("Ошибка чтения файла: $fileName");
    if (trim($content) === '') return [];
    $data = json_decode($content, true);
    if (!is_array($data)) throw new RuntimeException("Некорректный JSON в файле: $fileName");
    return $data;
}

function saveData(string $fileName, array $data): void
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) throw new RuntimeException("Не удалось сериализовать данные");
    if (file_put_contents($fileName, $json) === false) throw new RuntimeException("Не удалось записать файл");
}

// Функция e() теперь безопасно обрабатывает null и числа
function e(mixed $value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function isValidDate(string $date): bool {
    if ($date === '') return false;
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    return $dateTime !== false && $dateTime->format('Y-m-d') === $date;
}

function createMovie(array $data): int
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        INSERT INTO movies (title, release_date, type, genre_id, rating, description, watched_at, status, created_at)
        VALUES (:title, :release_date, :type, :genre_id, :rating, :description, :watched_at, :status, :created_at)
    ");
    $stmt->execute([
        ':title' => $data['title'],
        ':release_date' => $data['release_date'],
        ':type' => $data['type'],
        ':genre_id' => (int)$data['genre_id'],
        ':rating' => (int)$data['rating'],
        ':description' => $data['description'],
        ':watched_at' => $data['watched_at'] ?: null,
        ':status' => $data['status'],
        ':created_at' => date('Y-m-d'),
    ]);
    return (int)$pdo->lastInsertId();
}

function getAllMovies(string $sortBy = 'created_at', string $order = 'DESC'): array
{
    $pdo = Database::getConnection();
    $allowedSort = ['title', 'release_date', 'rating', 'created_at', 'genre'];
    $sortField = in_array($sortBy, $allowedSort, true) ? $sortBy : 'created_at';
    
    // LEFT JOIN гарантирует, что фильмы не пропадут, если жанр удалён или не указан
    if ($sortField === 'genre') {
        $sql = "SELECT m.*, g.name as genre_name 
                FROM movies m 
                LEFT JOIN genres g ON m.genre_id = g.id 
                ORDER BY g.name $order";
    } else {
        $sql = "SELECT m.*, g.name as genre_name 
                FROM movies m 
                LEFT JOIN genres g ON m.genre_id = g.id 
                ORDER BY m.$sortField $order";
    }
    
    return $pdo->query($sql)->fetchAll();
}

function getMovieById(int $id): ?array
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT m.*, g.name as genre_name FROM movies m JOIN genres g ON m.genre_id = g.id WHERE m.id = :id");
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

function updateMovie(int $id, array $data): bool
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        UPDATE movies SET title = :title, release_date = :release_date, type = :type, genre_id = :genre_id, 
        rating = :rating, description = :description, watched_at = :watched_at, status = :status WHERE id = :id
    ");
    return $stmt->execute([
        ':id' => $id, ':title' => $data['title'], ':release_date' => $data['release_date'],
        ':type' => $data['type'], ':genre_id' => (int)$data['genre_id'], ':rating' => (int)$data['rating'],
        ':description' => $data['description'], ':watched_at' => $data['watched_at'] ?: null, ':status' => $data['status']
    ]);
}

function deleteMovie(int $id): bool
{
    $pdo = Database::getConnection();
    return $pdo->prepare("DELETE FROM movies WHERE id = :id")->execute([':id' => $id]);
}

function searchMovies(string $query): array
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT m.*, g.name as genre_name FROM movies m JOIN genres g ON m.genre_id = g.id WHERE m.title LIKE :q OR m.description LIKE :q");
    $stmt->execute([':q' => "%$query%"]);
    return $stmt->fetchAll();
}

function getAllGenres(): array
{
    $pdo = Database::getConnection();
    return $pdo->query("SELECT id, name FROM genres ORDER BY name")->fetchAll();
}

function validateMovieData(array $input): array
{
    $genres = getAllGenres();
    $genreIds = array_column($genres, 'id');
    
    $validators = [
        new RequiredValidator('title', 'Название'),
        new LengthValidator('title', 'Название', 2, 100),
        new DateValidator('release_date', 'Дата выхода', true),
        new InArrayValidator('type', 'Тип', ALLOWED_TYPES),
        new InArrayValidator('genre_id', 'Жанр', $genreIds),
        new RatingValidator('rating', 'Оценка', 1, 10),
        new RequiredValidator('description', 'Описание'),
        new LengthValidator('description', 'Описание', 10, 1000),
        new DateValidator('watched_at', 'Дата просмотра', false),
        new InArrayValidator('status', 'Статус', ALLOWED_STATUSES),
    ];
    
    $errors = [];
    foreach ($validators as $validator) {
        $error = $validator->validate($input);
        if ($error !== null) $errors[] = $error;
    }
    return $errors;
}