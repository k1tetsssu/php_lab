<?php
declare(strict_types=1);

require_once __DIR__ . '/ValidatorInterface.php';

class InArrayValidator implements ValidatorInterface
{
    private string $field;
    private string $label;
    private array $allowedValues;

    /**
     * @param string $field Ключ поля.
     * @param string $label Название поля.
     * @param array $allowedValues Допустимые значения.
     */
    public function __construct(string $field, string $label, array $allowedValues)
    {
        $this->field = $field;
        $this->label = $label;
        $this->allowedValues = $allowedValues;
    }
    
    /**
     * Проверяет, входит ли значение поля в список допустимых.
     *
     * @param array $data Ассоциативный массив данных формы.
     * @return string|null Сообщение об ошибке или null.
     */
    public function validate(array $data): ?string
    {
        $value = trim((string)($data[$this->field] ?? ''));

        if (!in_array($value, $this->allowedValues, true)) {
            return "Поле \"{$this->label}\" содержит недопустимое значение.";
        }

        return null;
    }
}