<?php
// Путь к папке
$dir =  'image/';


// Проверяем существование директории
if (!is_dir($dir)) {
    die('Папка image не найдена');
}

// Сканируем папку
$files = scandir($dir);

if ($files === false) {
    die('Ошибка чтения директории');
}

// Фильтруем только JPG
$images = array_filter(
    $files,
    fn($file) =>
        strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'jpg'
);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Галерея</title>
        <style>
        body { font-family: Arial, sans-serif; padding: 20px; }

        header, footer { text-align: center; padding: 10px; background: #ddd; }

        nav { text-align: center; margin: 10px 0; }
        nav a { margin: 0 10px; text-decoration: none; }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border: 1px solid #aaa;
        }
    </style>
</head>
<body>

<header>
    <h1>Моя Галерея</h1>
</header>

<nav>
    <a href="#">Главная</a> |
    <a href="#">Галерея</a> |
    <a href="#">Контакты</a>
</nav>

<hr>

<div>
    <h2>Изображения</h2>

    <?php
    for ($i = 0; $i < count($files); $i++) {

        if ($files[$i] != "." && $files[$i] != "..") {

            $path = $dir . $files[$i];

            // Проверка расширения
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            if (strtolower($ext) == "jpg") {
                echo '<img src="' . $path . '" width="200" style="margin:5px;">';
            }
        }
    }
    ?>

</div>

<hr>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Галерея</p>
</footer>

</body>
</html>