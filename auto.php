<?php
require "connect.php";
require "cap.php";

    if (isset($_POST['login']) && isset($_POST['pass'])) {
        $login = $_POST["login"];
        $password = $_POST["pass"];

        $queryUser = mysqli_query($conn, "SELECT * FROM Users WHERE login='$login' AND password='$password'"); 
    $user = mysqli_fetch_array($queryUser);
    $idUser = $user["id"];
    if(!empty($idUser)) {
            setcookie('idUser', $idUser, time() + 3600, "/");   
            echo "<script>alert('Вы успешно вошли в аккаунт');
        location.href='cabinet.php';
        </script>";
    } else {
        echo "<script>alert('Данный пользователь не найден!');
        location.href='auto.php';
        </script>";
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
    <title>Авторизация</title>
</head>
<body>

    <div class="blackbox_two">
     </div>
    <div class="container">
        <div class="align_box_f">
            <div class="box">
                <form class="box_form" method="post">
                    <p class="regist">Авторизация</p>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Логин</label>
                        <input type="text" name="login" class="form-control" id="log1" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Пароль</label>
                        <input type="password" name="pass" class="form-control" id="pass1"  required>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>