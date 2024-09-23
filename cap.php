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
                    echo "<a href='reg.php' class='sign'>Регистраци</a>";
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
                <p class="date"><?php echo date("D, F j, Y");?></p>
                <div>
                    <img src="/img/Sun.svg" alt="">
                </div>
            </div>

        </div>
    </div>
</body>
</html>