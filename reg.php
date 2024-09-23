<?php
require "connect.php";
require "cap.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["pass"];
    $nickname = $_POST["name"];
    $img = "avatar1.png";

    // Проверка на существование пользователя с таким же nickname
    $check_double = mysqli_query($conn, "SELECT * FROM Users WHERE nickname = '$nickname'");
    
    if (mysqli_num_rows($check_double) > 0) {
        echo "<script>
                alert('Пользователь с таким Никнеймом уже создан, попробуйте другой!');
                location.href = 'reg.php';
              </script>";
    } else {
        $sql_users = "INSERT INTO Users (nickname, login, password, img) 
                      VALUES ('$nickname', '$login', '$password', '$img')";
        
        if (mysqli_query($conn, $sql_users)) {
            echo "<script>
                    alert('Успешная Регистрация!');
                    location.href = 'auto.php';
                  </script>";
            exit();
        } else {
            echo "Ошибка регистрации: " . mysqli_error($conn);
        }
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Регистрация</title>
</head>
<body class="body_two">


    <div class="blackbox_two">
     </div>
    <div class="container">
        <div class="align_box_f">
            <div class="box">
                <form class="box_form" method="post">
                    <p class="regist">Регистрация</p>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" id="name1" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Логин</label>
                        <input type="text" name="login" class="form-control" id="log1" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Пароль</label>
                        <input type="password" name="pass" class="form-control" id="pass1"  required>
                    </div>
                    <button type="submit" class="btn btn-primary">Регистрация</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>