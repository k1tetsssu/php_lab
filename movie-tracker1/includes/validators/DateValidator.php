<?php
declare(strict_types=1);

require_once __DIR__ . '/ValidatorInterface.php';

class DateValidator implements ValidatorInterface
{
    private string $field;
    private string $label;
    private bool $required;

    /**
     * @param string $field Ключ поля.
     * @param string $label Название поля.
     * @param bool $required Обязательность поля.
     */
    public function __construct(string $field, string $label, bool $required = true)
    {
        $this->field = $field;
        $this->label = $label;
        $this->required = $required;
    }

    /**
     * @param string $field Ключ поля.
     * @param string $label Название поля.
     * @param bool $required Обязательность поля.
     */    
    public function validate(array $data): ?string
    {
        $value = trim((string)($data[$this->field] ?? ''));

        if ($value === '') {
            return $this->required
                ? "Поле \"{$this->label}\" обязательно для заполнения."
                : null;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);

        if ($date === false || $date->format('Y-m-d') !== $value) {
            return "Поле \"{$this->label}\" заполнено неверно.";
        }

        return null;
    }
}