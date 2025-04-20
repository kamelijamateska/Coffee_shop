<?php

session_start(); // Почеток на сесијата, за чување на податоците за корисникот низ различни страници.
require_once 'config/database.php'; // Вклучување на конфигурацијата за врската со базата на податоци.
require_once 'classes/User.php'; // Вклучување на класата за корисник, која содржи методи за управување со кориснички операции.

$error = ''; // Променлива за чување на грешки.

if (isset($_SESSION['user_id'])) { // Проверка дали корисникот е веќе најавен.
    header('Location: index.php'); // Пренасочување на корисникот на почетната страница ако е најавен.
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверка дали е извршено POST барање (дали форма е испратена).
    $db = connectDB(); // Поврзување со базата на податоци.
    $user = new User($db); // Креирање на нов објект од класата User.

    $email = trim($_POST['email']); // Земање на емаил адресата од формата и отстранување на непотребни симболи.
    $password = $_POST['password']; // Земање на лозинката од формата.

    if (empty($email) || empty($password)) { // Проверка дали емаил или лозинка се празни.
        $error = "Both email and password are required"; // Приказ на грешка ако не се внесени емаил или лозинка.
    } else {
        if ($user->login($email, $password)) { // Ако корисникот успешно се најави.
            header('Location: index.php'); // Пренасочување на почетната страница по успешна најава.
            exit();
        } else {
            $error = "Invalid email or password"; // Приказ на грешка ако емаилот или лозинката се погрешни.
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Подесување на карактерниот сет за страницата. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Подесување на видливоста за мобилни уреди. -->
    <title>Login - Coffee Shop</title> <!-- Назив на страницата што ќе се прикаже на табот на прелистувачот. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Поврзување на Bootstrap CSS за стилови. -->
</head>
<body class="bg-light"> <!-- Позадина на страницата со светла боја. -->
    <div class="container"> <!-- Главниот контејнер за содржината на страницата. -->
        <div class="row justify-content-center mt-5"> <!-- Ред за распоред на содржината, центрирање на елементите. -->
            <div class="col-md-6"> <!-- Колона која ќе има ширина од 6 од 12 простори (средина на страницата). -->
                <div class="card"> <!-- Картичка која ќе ги содржи сите елементи за логирање. -->
                    <div class="card-header">
                        <h3 class="text-center">Login</h3> <!-- Наслов на картичката за логирање. -->
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?> <!-- Ако има грешка, ќе се прикаже порака. -->
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div> <!-- Приказ на грешка. -->
                        <?php endif; ?>

                        <form method="POST" action=""> <!-- Форма која ќе испрати POST барање. -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label> <!-- Етикета за полето за емаил. -->
                                <input type="email" class="form-control" id="email" name="email" required> <!-- Влезно поле за емаил. -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label> <!-- Етикета за полето за лозинка. -->
                                <input type="password" class="form-control" id="password" name="password" required> <!-- Влезно поле за лозинка. -->
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button> <!-- Копче за логирање. -->
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            Don't have an account? <a href="register.php">Register here</a> <!-- Линк за регистрација ако корисникот нема сметка. -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Вклучување на Bootstrap JavaScript за функционалности. -->
</body>
</html>
