<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? "Movie Tracker") ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <nav class="main-nav">
        <a href="index.php">Нативные шаблоны</a>
        <a href="twig/index.php">Twig</a>
    </nav>
</header>
<main>
    <?= $content ?>
</main>
<footer>
    <p>Movie Tracker &copy; <?= date('Y') ?></p>
</footer>
</body>
</html>
