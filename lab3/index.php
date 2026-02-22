<?php
// Получаем текущий день недели (1-7)
$day = date("N");

// John Styles
if ($day == 1 || $day == 3 || $day == 5) {
    $work = "8:00-12:00";
} else {
    $work = "Нерабочий день";
}

// Jane Doe
if ($day == 2 || $day == 4 || $day == 6) {
    $work= "12:00-16:00";
} else {
    $work = "Нерабочий день";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Расписание сотрудников</title>
</head>
<body>

<h2>Расписание на сегодня (<?php echo date("l"); ?>)</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>№</th>
        <th>Фамилия Имя</th>
        <th>График работы</th>
    </tr>
    <tr>
        <td>1</td>
        <td>John Styles</td>
        <td><?php echo $work; ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Jane Doe</td>
        <td><?php echo $work; ?></td>
    </tr>
</table>

</body>
</html>