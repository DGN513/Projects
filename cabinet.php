<?php

require "connect.php";
require "cap.php";

if (!empty($_COOKIE['idUser'])) {
    $id = intval($_COOKIE['idUser']); 
} else {
    $id = 0;
}

$sql = mysqli_query($conn,"SELECT * FROM Users WHERE id = $id");
$user = mysqli_fetch_array($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = !empty($_POST["login"]) ? $_POST["login"] : $user["login"];
    $password = !empty($_POST["password"]) ? $_POST["password"] : $user["password"];
    $nickname = $_POST["name"];
    $img = !empty($_POST["img"]) ? $_POST["img"] : $user["img"];

    $check_double = mysqli_query($conn,"SELECT * FROM Users WHERE nickname = '$nickname' AND id != $id");
    
    if (mysqli_num_rows($check_double) > 0) {
        echo "<script>
                alert('Пользователь с таким Никнеймом уже существует. Пожалуйста, выберите другой!');
                window.location.href='cabinet.php';
              </script>";
        exit();
    } else {
        if (!empty($_POST["pass"])) {
            echo "<script>
                    if (confirm('Вы точно хотите обновить пароль?')) {
                        // Если подтвердили, выполняем обновление
                        window.location.href='update_password.php'; // или ваша логика смены пароля
                    }
                  </script>";
        }

        $sql_users = "UPDATE `Users` SET 
                        `nickname`='$nickname', 
                        `login`='$login', 
                        `password`='$password', 
                        `img`='$img' 
                      WHERE id = $id";
        
        if (mysqli_query($conn, $sql_users)) {
            echo "<script>
                    alert('Данные успешно обновлены!');
                    window.location.href='auto.php';
                  </script>";
            exit();
        } else {
            echo "Ошибка при обновлении данных: " . mysqli_error($conn);
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
    <title>Личный кабинет</title>
</head>
<body>

<div class="container light-style flex-grow-1 container-p-y">

<h4 class="font-weight-bold py-3 mb-4">
  Настройки аккаунта
</h4>

<div class="card overflow-hidden">
  <div class="row no-gutters row-bordered row-border-light">
    <div class="col-md-3 pt-0">
      <div class="list-group list-group-flush account-settings-links">
        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">Главное</a>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
        <div class="tab-pane fade active show" id="account-general">

          <div class="card-body media align-items-center">
            <img src="/img/<?= $user['img']?>" alt="" class="d-block ui-w-80">
            <div class="media-body ml-4">
              <label class="btn btn-outline-primary">
                Загрузить новое фото
                <input type="file" name="img" class="account-settings-fileinput">
              </label> &nbsp;
              <button type="button" class="btn btn-default md-btn-flat">Reset</button>
              <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
            </div>
          </div>
          <hr class="border-light m-0">

          <form class="card-body" method="POST">
            <div class="form-group">
              <label class="form-label">ФИО</label>
              <input type="text" name="name" class="form-control mb-1" value="<?= $user['nickname']?>">
            </div>
            <div class="form-group">
              <label class="form-label">Логин</label>
              <input type="text" name="login" class="form-control" value="<?= $user['login']?>">
            </div>
            <div class="form-group">
              <label class="form-label">Пароль</label>
              <input type="text" name="password" class="form-control mb-1" value="<?= $user['password']?>">
            </div>

            <div class="text-right mt-3 container">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>&nbsp;
            </div>
          </form>

        </div>

       

          </div>
        </div>
      </div>
    </div>
  </div>
</div>



</div>
</body>
</html>