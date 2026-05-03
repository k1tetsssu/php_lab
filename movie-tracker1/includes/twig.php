<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

function getTwigEnvironment(): Environment
{
    static $twig = null;
    
    if ($twig !== null) {
        return $twig;
    }
    
    $loader = new FilesystemLoader(__DIR__ . '/../twig/templates');
    $twig = new Environment($loader, [
        'cache' => false,
        'autoescape' => 'html',
        'debug' => true,
    ]);
    
    // Фильтр status_icon
    $twig->addFilter(new TwigFilter('status_icon', function (string $status): string {
        $icons = [
            'Смотрю' => '👀',
            'Хочу посмотреть' => '📌',
            'Просмотрено' => '✔️',
        ];
        return $icons[$status] ?? '🎬';
    }, ['is_safe' => ['html']]));
    
    // Функция для CSRF-токена в Twig
    $twig->addFunction(new TwigFunction('csrf_token', function (): string {
        return generateCsrfToken();
    }));
    
    return $twig;
}

function renderTwig(string $template, array $params = []): void
{
    echo getTwigEnvironment()->render($template, $params);
}