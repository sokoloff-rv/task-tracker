<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая задача успешно создана!</title>
</head>
<body>
    <h1>Новая задача успешно создана!</h1>
    <p>Заголовок: {{ $task->title }}</p>
    <p>Статус: {{ $task->status }}</p>
    @if($task->description)
        <p>Описание: {{ $task->description }}</p>
    @endif
    @if($task->due_date)
        <p>Дата завершения: {{ $task->due_date->format('Y-m-d') }}</p>
    @endif
</body>
</html>
