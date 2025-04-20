<?php
session_start(); // Почеток на сесијата. Ова овозможува чување на податоци за корисникот низ различни страници.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Подесување на карактерниот сет (charset) на страницата. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Подесување на видливоста на страницата на мобилни уреди. -->
    <title>Coffee Shop</title> <!-- Назив на страницата, кој ќе се прикаже на табот на прелистувачот. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Поврзување на Bootstrap за стилови. -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Навигациска лента со темна позадина -->
        <div class="container">
            <a class="navbar-brand" href="index.php">Coffee Shop</a> <!-- Логото кое води до почетната страница -->
            <div class="navbar-nav">
                <?php if(isset($_SESSION['user_id'])): ?> <!-- Проверка дали корисникот е најавен -->
                    <a class="nav-link" href="menu.php">Menu</a> <!-- Линк за мени (достапно само за најавени корисници) -->
                    <a class="nav-link" href="book-table.php">Book Table</a> <!-- Линк за резервација на маса -->
                    <a class="nav-link" href="my-orders.php">My Orders</a> <!-- Линк за преглед на нарачки -->
                    <a class="nav-link" href="logout.php">Logout</a> <!-- Линк за одјавување -->
                <?php else: ?> <!-- Ако корисникот не е најавен -->
                    <a class="nav-link" href="login.php">Login</a> <!-- Линк за најава -->
                    <a class="nav-link" href="register.php">Register</a> <!-- Линк за регистрација -->
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4 text-center">
        <h1>Welcome to Our Coffee Shop!</h1> <!-- Главен наслов на страницата -->
        <p class="lead">We serve the best coffee and pastries in town.</p> <!-- Поднаслов со краток опис на услугите -->
        <?php if(!isset($_SESSION['user_id'])): ?> <!-- Проверка дали корисникот не е најавен -->
            <p><a href="register.php" class="btn btn-primary">Register Now</a> or <a href="login.php" class="btn btn-secondary">Login</a> to explore our menu and place orders.</p> <!-- Покана за регистрација или најава -->
        <?php else: ?> <!-- Ако корисникот е најавен -->
            <p><a href="menu.php" class="btn btn-primary">View Menu</a> or <a href="my-orders.php" class="btn btn-secondary">View My Orders</a>.</p> <!-- Линкови за преглед на мени и нарачки -->
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Вклучување на Bootstrap JavaScript за функционалности на страницата -->
</body>
</html>
