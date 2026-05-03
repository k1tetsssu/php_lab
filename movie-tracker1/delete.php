<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Разрешён только метод POST.');
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Ошибка безопасности: невалидный CSRF-токен.');
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('Некорректный ID записи.');
}

$deleted = deleteMovie($id);
if ($deleted) {
    regenerateCsrfToken();
    header('Location: list.php');
    exit;
}

http_response_code(500);
render('errors', [
    'title'  => 'Ошибка удаления',
    'errors' => ['Не удалось удалить запись. Возможно, она уже удалена или заблокирована связями.']
]);
exit;