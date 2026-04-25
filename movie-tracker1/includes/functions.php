<?php
declare(strict_types=1);

require_once __DIR__ . '/validators/ValidatorInterface.php';
require_once __DIR__ . '/validators/RequiredValidator.php';
require_once __DIR__ . '/validators/LengthValidator.php';
require_once __DIR__ . '/validators/DateValidator.php';
require_once __DIR__ . '/validators/InArrayValidator.php';
require_once __DIR__ . '/validators/RatingValidator.php';


function render(string $template, array $params = []): void
{
    // превращаем массив в переменные
    extract($params, EXTR_SKIP);

    // буферизация вывода
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    $content = ob_get_clean();

    // подключаем layout
    require __DIR__ . '/../templates/layout.php';
}

/**
 * Загружает данные из JSON-файла и преобразует их в массив.
 *
 * Если файл отсутствует или пуст, функция возвращает пустой массив.
 *
 * @param string $fileName Полный путь к файлу с данными.
 * @return array Массив записей, считанных из файла.
 * @throws RuntimeException Если файл недоступен для чтения или содержит некорректный JSON.
 */
function loadData(string $fileName): array
{
    if (!file_exists($fileName)) {
        return [];
    }

    if (!is_readable($fileName)) {
        throw new RuntimeException("Не удалось прочитать файл: $fileName");
    }

    $content = file_get_contents($fileName);

    if ($content === false) {
        throw new RuntimeException("Ошибка чтения файла: $fileName");
    }

    if (trim($content) === '') {
        return [];
    }

    $data = json_decode($content, true);

    if (!is_array($data)) {
        throw new RuntimeException("Некорректный JSON в файле: $fileName");
    }

    return $data;
}

/**
 * Сохраняет массив данных в файл в формате JSON.
 *
 * Данные сериализуются в читаемый JSON с сохранением Unicode-символов.
 *
 * @param string $fileName Полный путь к файлу для сохранения.
 * @param array $data Массив данных, который необходимо сохранить.
 * @return void
 * @throws RuntimeException Если не удалось сериализовать данные или записать их в файл.
 */
function saveData(string $fileName, array $data): void
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        throw new RuntimeException("Не удалось сериализовать данные для сохранения в файл: $fileName");
    }

    if (file_put_contents($fileName, $json) === false) {
        throw new RuntimeException("Не удалось записать данные в файл: $fileName");
    }
}

/**
 * Экранирует строку для безопасного вывода в HTML.
 *
 * @param string $value Исходная строка.
 * @return string Безопасная строка для вывода в HTML.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Проверяет, является ли строка допустимой датой в формате Y-m-d.
 *
 * @param string $date Строка с датой.
 * @return bool True, если дата допустима, иначе False.
 */
function isValidDate(string $date): bool
{
    if ($date === '') {
        return false;
    }

    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    return $dateTime !== false && $dateTime->format('Y-m-d') === $date;
}

/**
 * Выполняет валидацию данных формы фильма или сериала.
 *
 * Функция создаёт набор валидаторов и поочерёдно запускает их.
 * Все найденные ошибки собираются в массив и возвращаются вызывающему коду.
 *
 * @param array $input Ассоциативный массив данных формы.
 * @return array Массив сообщений об ошибках. Если ошибок нет, возвращается пустой массив.
 */
function validateMovieData(array $input): array
{
    $validators = [
        new RequiredValidator('title', 'Название'),
        new LengthValidator('title', 'Название', 2, 100),

        new DateValidator('release_date', 'Дата выхода', true),

        new InArrayValidator('type', 'Тип', ALLOWED_TYPES),
        new InArrayValidator('genre', 'Жанр', ALLOWED_GENRES),

        new RatingValidator('rating', 'Оценка', 1, 10),

        new RequiredValidator('description', 'Описание'),
        new LengthValidator('description', 'Описание', 10, 1000),

        new DateValidator('watched_at', 'Дата просмотра', false),

        new InArrayValidator('status', 'Статус', ALLOWED_STATUSES),
    ];

    $errors = [];

    foreach ($validators as $validator) {
        $error = $validator->validate($input);

        if ($error !== null) {
            $errors[] = $error;
        }
    }

    return $errors;
}

