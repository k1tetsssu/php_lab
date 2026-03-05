<?php

declare(strict_types=1);

/**
 * Массив банковских транзакций.
 *
 * @var array<int, array{
 *     id: int,
 *     date: string,
 *     amount: float,
 *     description: string,
 *     merchant: string
 * }>
 */

$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "pivo",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2025-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
    [
        "id" => 3,
        "date" => "2021-06-10",
        "amount" => 250.00,
        "description" => "Electronics purchase",
        "merchant" => "TechStore",
    ],
];



/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array<int, array<string, mixed>> $transactions
 * @return float
 */
function calculateTotalAmount(array $transactions): float {
    $total = 0.0;
    foreach ($transactions as $transaction) {
        $total += $transaction['amount'];
    }
    return $total;
}

/**
 * Ищет транзакцию по ID.
 *
 * @param int $id
 * @return array<string, mixed>|null
 */

function findTransactionById(int $id): ?array {
    global $transactions;

    foreach ($transactions as $transaction) {
        if ($transaction['id'] === $id) {
            return $transaction;
        }
    }

    return null;
}

/**
 * Ищет транзакцию по ID с использованием array_filter.
 *
 * @param int $id
 * @return array<string, mixed>|null
 */

function findTransactionByIdFilter(int $id): ?array {
    global $transactions;

    $filtered = array_filter(
        $transactions,
        fn(array $t): bool => $t['id'] === $id
    );

    return $filtered ? array_values($filtered)[0] : null;
}

/**
 * Поиск транзакций по части описания.
 *
 * @param string $descriptionPart
 * @return array<int, array<string, mixed>>
 */
function findTransactionByDescription(string $descriptionPart): array {
    global $transactions;

    $result = [];

    foreach ($transactions as $transaction) {
        if (stripos($transaction['description'], $descriptionPart) !== false) {
            $result[] = $transaction;
        }
    }

    return $result;
}

/**
 * Возвращает количество дней с момента транзакции.
 *
 * @param string $date
 * @return int
 */
function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime();

    $interval = $transactionDate->diff($currentDate);

    return (int)$interval->format('%a');
}

/**
 * Добавляет новую транзакцию.
 *
 * @param int $id
 * @param string $date
 * @param float $amount
 * @param string $description
 * @param string $merchant
 * @return void
 */
function addTransaction(
    int $id,
    string $date,
    float $amount,
    string $description,
    string $merchant
): void {
    global $transactions;

    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}

/**
 * Удаляет транзакцию по ID.
 *
 * @param int $id
 * @return void
 */
function deleteTransaction(int $id): void {
    global $transactions;

    foreach ($transactions as $key => $transaction) {
        if ($transaction['id'] === $id) {
            unset($transactions[$key]);
        }
    }
}


/* Сортировка по дате */
usort(
    $transactions,
    fn(array $a, array $b): int =>
        strtotime($a['date']) <=> strtotime($b['date'])
);

/* Сортировка по сумме (по убыванию) */

usort(
    $transactions,
    fn(array $a, array $b): int =>
        $b['amount'] <=> $a['amount']
);



?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Bank Transactions</title>
</head>
<body>

<h2>Список транзакций</h2>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Описание</th>
            <th>Получатель</th>
            <th>Дней с момента</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?= htmlspecialchars((string)$transaction['id']) ?></td>
            <td><?= htmlspecialchars($transaction['date']) ?></td>
            <td><?= number_format($transaction['amount'], 2) ?></td>
            <td><?= htmlspecialchars($transaction['description']) ?></td>
            <td><?= htmlspecialchars($transaction['merchant']) ?></td>
            <td><?= daysSinceTransaction($transaction['date']) ?></td>
        </tr>
    <?php endforeach; ?>

        <tr>
            <td colspan="2"><strong>Общая сумма:</strong></td>
            <td colspan="4">
                <strong><?= number_format(calculateTotalAmount($transactions), 2) ?></strong>
            </td>
        </tr>

    </tbody>
</table>

</body>
</html>


