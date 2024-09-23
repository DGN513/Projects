<?php
require "connect.php";
require "cap.php";

$services_query = mysqli_query($conn, "SELECT * FROM `Categories`");
$services = mysqli_fetch_all($services_query, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $date = date("Y-n-j");
    $id_category = intval($_POST["id_category"]); 

    if (!empty($title) && !empty($description) && !empty($id_category)) {
        $sql_users = "INSERT INTO `News`(`id_user`, `title`, `description`, `date`, `id_category`) 
                      VALUES ('$id', '$title', '$description', '$date', '$id_category')";
        if (mysqli_query($conn, $sql_users)) {
            echo "<script>
                    alert('Новость успешно добавлена!');
                    location.href='index.php';
                  </script>";
            exit();
        } else {
            echo "Ошибка добавления новости: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Пожалуйста, заполните все поля!');</script>";
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
    <title>Добавить новость</title>
</head>
<body class="body_two">

    <div class="blackbox_two"></div>
    <div class="container">
        <div class="align_box_f">
            <div class="box">
                <form class="box_form" method="post">
                    <p class="regist">Добавить новость</p>

                    <div class="mb-3">
                        <label for="title" class="form-label">Заголовок</label>
                        <input type="text" name="title" class="form-control" id="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание новости</label>
                        <textarea name="description" class="form-control" id="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Категория новости</label>
                        <select name="id_category" class="form-select" id="category" required>
                            <option value="all">Все</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id_category'] ?>" 
                                <?= isset($_GET['name_category']) && $_GET['name_category'] == $service['id_category'] ? 'selected' : '' ?>><?= $service['name_category'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Добавить новость</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>