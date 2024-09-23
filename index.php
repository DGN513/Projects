<?php
if(!empty($_COOKIE['idUser'])){
    $id = $_COOKIE['idUser'];
}else{
    $id = 0;
}
require "connect.php";
require "cap.php";

$sql = "SELECT id, id_user, title, description, date, id_category FROM News";
$result = $conn->query($sql);

$topCommentsSQL = "SELECT c.id_category, c.name_category, COUNT(cm.id_comment) AS comment_count
        FROM News n
        LEFT JOIN Comments cm ON n.id = cm.id_news
        LEFT JOIN Categories c ON n.id_category = c.id_category
        GROUP BY c.id_category
        ORDER BY comment_count DESC
        LIMIT 5";
$topCategoriesResult = $conn->query($topCommentsSQL);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Главная</title>
</head>
<body>
    

    <div class="blackbox">
     </div>
        <div class="container">
            <ul class="nav">
                <li class="li_bgc"><a class="href_nav" href="">News</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Opinion</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Science</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Life</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Travel</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Moneys</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Art & Design</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Sports</a></li>
                <li class="li_bgc"><a class="href_nav" href="">People</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Health</a></li>
                <li class="li_bgc"><a class="href_nav" href="">Education</a></li>
            </ul>

            <div class="box_transparency">
                <div class="text_img_bt">
                 <p class="text_wide_card">25 Songs That Tell Us Where Music Is Going</p>
                 <img src="/img/gitara.svg" alt="">   
                </div>
                
                    <div class="line_vert"></div>

                <div class="text_img_bt">
                 <p class="text_wide_card">These Ancient Assassins Eat Their Own Kind</p>
                 <img src="/img/butterfly.svg" alt="">   
                </div>

                    <div class="line_vert"></div>

                <div class="text_img_bt">
                 <p class="text_wide_card">How Do You Teach People to Love Difficult Music?</p>
                 <img src="/img/nigga.svg" alt="">   
                </div>

                    <div class="line_vert"></div>

                
                <div class="text_img_bt">
                 <p class="text_wide_card">International Soccer’s Man of Mystery</p>
                 <img src="/img/peisaj.svg" alt="">   
                </div>
             </div>

                <div class="news-container">
                    <div class="align_news_blog">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='news-block'>";
                            echo "<h3>" . $row["title"] . "</h3>";
                            echo "<p class='description'>" . $row["description"] . "</p>";
                            echo "<a class='show-more' href='post.php?id=" . $row["id"] . "'>Подробнее</a>";
                            echo "<span class='date'>" . date("M d", strtotime($row["date"])) . "</span>";

                            // Проверяем, авторизован ли пользователь
                            if (isset($_COOKIE["idUser"]) & $_COOKIE["idUser"] = $row["id"]) {
                                echo "<div class='options-menu'>";
                                echo "<img src='/img/option.svg' class='options-icon' alt='Опции' onclick='toggleMenu(" . $row["id"] . ")'>";
                                
                                // Меню с кнопками Обновить и Удалить
                                echo "<div id='menu-" . $row["id"] . "' class='dropdown-menu' style='display: none;'>";
                                echo "<a href='update_news.php?id=" . $row["id"] . "'>Обновить</a>";
                                echo "<a href='#' onclick='confirmDelete(" . $row["id"] . ")'>Удалить</a>";
                                echo "</div>";
                                echo "</div>";
                            }

                            echo "</div>";
                        }
                    } else {
                        echo "Новостей не найдено.";
                    }

                    ?>
                    </div>

                    <div class="top-comments">
                            <h3 class="title_top5">Топ 5 категорий</h3>
                            <?php
                           if ($topCategoriesResult && $topCategoriesResult->num_rows > 0) {
                            while ($category = $topCategoriesResult->fetch_assoc()) {
                                echo "<h3>Категория: " . $category['name_category'] . " (Комментариев: " . $category['comment_count'] . ")</h3>";
                    
                                $category_id = $category['id_category'];
                                $commentsSql = mysqli_query($conn,"SELECT cm.desc_comment, u.nickname
                                    FROM Comments cm 
                                    LEFT JOIN News n ON cm.id_news = n.id 
                                    LEFT JOIN Users u ON cm.id_user = u.id 
                                    WHERE n.id_category = $category_id");
                    
                                if ($commentsSql && mysqli_num_rows($commentsSql) > 0) {
                                    echo "<div class='align_comments_top'>";
                                    while ($comment = mysqli_fetch_assoc($commentsSql)) {
                                        echo "<div>" . $comment['nickname'] . ":</strong> " . $comment['desc_comment'] . "</div>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<p>Комментариев нет.</p>";
                                }
                            }
                        } else {
                            echo "Топ категорий не найдено.";
                        }
                            ?>
                    </div>
                </div>
            </div>

                <script>
                    // Функция для переключения видимости меню
                    function toggleMenu(id) {
                        var menu = document.getElementById('menu-' + id);
                        if (menu.style.display === 'none') {
                            menu.style.display = 'block';
                        } else {
                            menu.style.display = 'none';
                        }
                    }

                    // Функция для подтверждения удаления
                    function confirmDelete(id) {
                        if (confirm("Вы точно хотите удалить запись?")) {
                            window.location.href = "delete.php?id=" + id;
                        }
                    }
                </script>
   
</body>
</html>

<?php
// Закрываем соединение только один раз, в конце
$conn->close();
?>