<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/twig.php';

renderTwig('form.html.twig', [
    'title' => 'Movie Tracker (Twig)',
    'types' => ALLOWED_TYPES,
    'genres' => ALLOWED_GENRES,
    'statuses' => ALLOWED_STATUSES,
]);
