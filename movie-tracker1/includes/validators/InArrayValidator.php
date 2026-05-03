<?php
declare(strict_types=1);

require_once __DIR__ . '/ValidatorInterface.php';

class InArrayValidator implements ValidatorInterface
{
    private string $field;
    private string $label;
    private array $allowedValues;

    public function __construct(string $field, string $label, array $allowedValues)
    {
        $this->field = $field;
        $this->label = $label;
        $this->allowedValues = $allowedValues;
    }
    
    public function validate(array $data): ?string
    {
        $value = trim((string)($data[$this->field] ?? ''));

        if ($value === '') {
            return "Поле \"{$this->label}\" обязательно для заполнения.";
        }

        // Приводим допустимые значения к строкам, т.к. данные из формы всегда приходят строками.
        // Это решает проблему строгого сравнения с числами (genre_id).
        $allowedStrings = array_map(fn($v) => (string)$v, $this->allowedValues);
        
        if (!in_array($value, $allowedStrings, true)) {
            return "Поле \"{$this->label}\" содержит недопустимое значение.";
        }

        return null;
    }
}