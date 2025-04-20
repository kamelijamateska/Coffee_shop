<?php
session_start(); // Започнување на сесијата за корисникот.

require_once 'config/database.php'; // Вклучување на конфигурацијата за врската со базата на податоци.
require_once 'classes/TableBooking.php'; // Вклучување на класата TableBooking која се користи за резервирање на маси.

if(!isset($_SESSION['user_id'])) { // Ако корисникот не е најавен, го пренасочуваме на страницата за логирање.
    header('Location: login.php');
    exit();
}

$db = connectDB(); // Поврзување со базата на податоци.
$booking = new TableBooking($db); // Креирање на објект за резервирање на маса.

if($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверка дали е направено POST барање (кога се испраќа формата).
    // Ги земаме податоците од формата
    $date = $_POST['date'];
    $time = $_POST['time'];
    $numGuests = $_POST['num_guests'];
    
    // Се обидуваме да резервираме маса со повикување на методот bookTable од класата TableBooking.
    if($booking->bookTable($_SESSION['user_id'], $date, $time, $numGuests)) {
        $success = "Table booked successfully!"; // Ако е успешна резервацијата, прикажуваме успешна порака.
    } else {
        $error = "Failed to book table. Please try again."; // Ако не успее резервацијата, прикажуваме грешка.
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Table</title> <!-- Наслов на страницата -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Вклучување на Bootstrap за стилови. -->
</head>
<body>
<!-- Навигација до страниците за најавување, регистрација, резервација, нарачување и сл. -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Coffee Shop</a>
        <div class="navbar-nav">
            <?php if(isset($_SESSION['user_id'])): ?> <!-- Ако корисникот е најавен, прикажуваме линкови за мени, маси, нарачки и одјава. -->
                <a class="nav-link" href="menu.php">Menu</a> <!-- Линк за мени. -->
                <a class="nav-link" href="book-table.php">Book Table</a> <!-- Линк за резервирање на маса. -->
                <a class="nav-link" href="my-orders.php">My Orders</a> <!-- Линк за нарачки. -->
                <a class="nav-link" href="logout.php">Logout</a> <!-- Линк за одјава. -->
            <?php else: ?>
                <a class="nav-link" href="login.php">Login</a> <!-- Линк за логирање ако корисникот не е најавен. -->
                <a class="nav-link" href="register.php">Register</a> <!-- Линк за регистрација. -->
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Главен дел за форма за резервирање маса -->
<div class="container mt-4">
    <h2>Book a Table</h2>
    
    <?php if(isset($success)): ?> <!-- Ако има успешна резервација, прикажува зелен alert за успех. -->
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if(isset($error)): ?> <!-- Ако има грешка, прикажува црвен alert за грешка. -->
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Форма за резервирање на маса -->
    <form method="POST" class="needs-validation" novalidate>
        <!-- Поле за внесување на датумот на резервацијата -->
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        
        <!-- Поле за внесување на времето за резервацијата -->
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" class="form-control" id="time" name="time" required>
        </div>
        
        <!-- Поле за внесување на бројот на гости -->
        <div class="mb-3">
            <label for="num_guests" class="form-label">Number of Guests</label>
            <input type="number" class="form-control" id="num_guests" name="num_guests" min="1" max="10" required>
        </div>
        
        <!-- Копче за потврда на резервацијата -->
        <button type="submit" class="btn btn-primary">Book Table</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Вклучување на Bootstrap JS за функционалност на компонентите. -->
</body>
</html>
