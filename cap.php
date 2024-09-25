<?php
if(!empty($_COOKIE['idUser'])){
    $id = $_COOKIE['idUser'];
}else{
    $id = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>
<body>
<div class="container">
    <div class="btn_search_sub_sign">
        <div class="log_img_sec">
            <img src="/img/Hamburger_menu.svg" alt="hamburger">
            <a href="sections.php">Секции</a>
        </div>
        <div class="log_img_sec">
            <img src="/img/Man.svg" alt="man">
            <?php 
            if(!empty($id)){
                echo "<a href='cabinet.php' class='sign'>Личный кабинет</a>";
                echo "<a href='create_news.php' class='sign'>Поделиться новостью</a>";
                echo "<a href='logout.php' class='sign'>Выйти</a>";
            }else{
                echo "<a href='auto.php' class='sign'>Войти</a>";
                echo "<a href='reg.php' class='sign'>Регистрация</a>";
            }
            ?>
        </div>
    </div>
    <div class="line"></div>
    <div class="logo_others">
        <div class="ph_text">
            <img src="/img/stateNY.svg" alt="stateny">
            <p>Boston and New York Bear Brunt</p>
        </div>

        <a href="index.php" class="logo">Universal</a>

        <div class="date_pog">
            <p class="date"><?php echo date("D, F j, Y"); ?></p>

            <div class="align_weather">

                <img src="/img/Sun.svg" alt="">

                <div id="weather">

                    <p class='weather_ufa'>Загрузка данных...</p>

                </div>
                
            </div>


        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            url: 'get_weather.php',
            type: 'GET',
            success: function(data) {
                var weather = JSON.parse(data);
                var html = "<p class='weather_ufa'>Температура: " + weather.main.temp + "°C</p>";
                html += "<p class='weather_ufa'>Описание: " + weather.weather[0].description + "</p>";
                html += "<p class='weather_ufa'>Влажность: " + weather.main.humidity + "%</p>";
                $('#weather').html(html); // Изменено с 'weather' на '#weather'
            },
            error: function() {
                $('#weather').html('<p>Ошибка при получении данных</p>'); // Изменено с 'weather' на '#weather'
            }
        });
    });
</script>

</body>
</html>