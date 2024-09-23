<?php
require "connect.php";
require "cap.php";

// Получение идентификатора поста из URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получение данных поста из таблицы News
$sql = "SELECT id, id_user, title, description, date, id_category FROM News WHERE id = $id";
$result = $conn->query($sql);
$post = $result->fetch_assoc();

// Получение комментариев к посту
$commentsSql = "SELECT c.id_comment, c.desc_comment, c.id_user, c.likes, u.nickname
                FROM Comments c
                JOIN Users u ON c.id_user = u.id
                WHERE c.id_news = $id";
$commentsResult = $conn->query($commentsSql);

// Добавление комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $conn->real_escape_string($_POST['comment']);
    $userId = $id; // Замените на ID текущего пользователя, если вы авторизованы
    $insertSql = "INSERT INTO Comments (desc_comment, id_user, id_news) VALUES ('$comment', $userId, $id)";
    if ($conn->query($insertSql) === TRUE) {
        header("Location: post.php?id=$id"); // Перезагрузка страницы для отображения нового комментария
        exit();
    }
}

$userId = isset($_COOKIE["idUser"]) ? intval($_COOKIE["idUser"]) : 0;

// Обработка лайка
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    $commentId = intval($_POST['comment_id']);

    if ($userId > 0) {
        // Проверка, ставил ли пользователь лайк этому комментарию
        $likeCheckSql = "SELECT * FROM CommentLikes WHERE id_comment = $commentId AND id_user = $userId";
        $likeCheckResult = $conn->query($likeCheckSql);

        if ($likeCheckResult->num_rows > 0) {
            // Удаление лайка
            $deleteLikeSql = "DELETE FROM CommentLikes WHERE id_comment = $commentId AND id_user = $userId";
            $conn->query($deleteLikeSql);
            
            // Уменьшение счетчика лайков
            $updateLikesSql = "UPDATE Comments SET likes = likes - 1 WHERE id_comment = $commentId";
            $conn->query($updateLikesSql);
        } else {
            // Добавление лайка
            $insertLikeSql = "INSERT INTO CommentLikes (id_comment, id_user) VALUES ($commentId, $userId)";
            $conn->query($insertLikeSql);
            
            // Увеличение счетчика лайков
            $updateLikesSql = "UPDATE Comments SET likes = likes + 1 WHERE id_comment = $commentId";
            $conn->query($updateLikesSql);
        }
    }

    header("Location: post.php?id=$id"); // Перезагрузка страницы для обновления состояния лайков
    exit();
}

$commentsSql = "SELECT c.id_comment, c.desc_comment, c.id_user, c.likes, u.nickname
                FROM Comments c
                JOIN Users u ON c.id_user = u.id
                WHERE c.id_news = $id";
$commentsResult = $conn->query($commentsSql);

// Обработка удаления комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $commentId = intval($_POST['comment_id']);
    $deleteCommentSql = "DELETE FROM Comments WHERE id_comment = $commentId";
    $conn->query($deleteCommentSql);
    header("Location: post.php?id=$id");
    exit();
}

// Обработка редактирования комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment'])) {
    $commentId = intval($_POST['comment_id']);
    $editedComment = $conn->real_escape_string($_POST['edited_comment']);
    $updateCommentSql = "UPDATE Comments SET desc_comment = '$editedComment' WHERE id_comment = $commentId";
    $conn->query($updateCommentSql);
    header("Location: post.php?id=$id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
</head>
<body>
<div class="post-container">
    <?php if ($post) { ?>
        <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="post-date"><?php echo date("M d, Y", strtotime($post['date'])); ?></p>
        <p class="post-description"><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
        <a class="back-button" href="index.php">Вернуться к новостям</a>
    <?php } else { ?>

        <p>Пост не найден.</p>


    <?php } ?>
            <div class="comment-section">
            <?php
                if ($commentsResult->num_rows > 0) {
                    while ($comment = $commentsResult->fetch_assoc()) {
                        $commentId = $comment['id_comment'];

                        // Проверка, лайкнул ли текущий пользователь этот комментарий
                        $userLikedSql = "SELECT * FROM CommentLikes WHERE id_comment = $commentId AND id_user = $userId";
                        $userLikedResult = $conn->query($userLikedSql);
                        $userLiked = $userLikedResult->num_rows > 0;

                        // Определяем иконку лайка
                        $likeIcon = $userLiked ? "img/like-unclear.svg" : "img/like_clear.svg";

                        echo "<div class='comment'>";
                        echo "<p><strong>" . htmlspecialchars($comment['nickname']) . ":</strong> " . nl2br(htmlspecialchars($comment['desc_comment'])) . "</p>";
                        echo "<p class='likes'>Likes: " . $comment['likes'] . " <img src='$likeIcon' class='like-button' onclick='likeComment($commentId)' alt='Like'></p>";

                        // Добавляем кнопки действий, если текущий пользователь является автором комментария
                        if ($comment['id_user'] == $userId) {
                            echo "<div class='comment-actions'>";
                            echo "<button onclick='showEditForm($commentId, \"" . htmlspecialchars($comment['desc_comment']) . "\")'>✎</button>";
                            echo "<form method='POST' style='display:inline;'>";
                            echo "<input type='hidden' name='comment_id' value='$commentId'>";
                            echo "<button type='submit' name='delete_comment' onclick='return confirm(\"Вы уверены, что хотите удалить этот комментарий?\");'>🗑️</button>";
                            echo "</form>";
                            echo "</div>";
                        }

                        echo "</div>";
                    }
                } else {
                    echo "<p>No comments yet.</p>";
                }
            ?>

                <form method="POST" class="comment-form">
                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                    <button type="submit" class="comment-button">Оставить комментарий</button>
                </form>
        </div>
    </div>

    <script>
        function likeComment(commentId) {
            fetch('post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `like=1&comment_id=${commentId}`
            }).then(response => {
                if (response.ok) {
                    // Обновляем иконку лайка после обновления
                    const likeImg = document.querySelector(`img[onclick='likeComment(${commentId})']`);
                    const currentSrc = likeImg.getAttribute('src');

                    // Переключаем изображение
                    if (currentSrc.includes('like_clear.svg')) {
                        likeImg.setAttribute('src', 'images/like_unclear.svg');
                    } else {
                        likeImg.setAttribute('src', 'images/like_clear.svg');
                    }

                    window.location.reload(); // Перезагрузка страницы для обновления лайков
                }
            });
        }
        function showEditForm(commentId, currentComment) {
                const editForm = document.createElement('form');
                editForm.method = 'POST';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'edited_comment';
                input.value = currentComment;

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'comment_id';
                hiddenInput.value = commentId;

                const submitButton = document.createElement('button');
                submitButton.type = 'submit';
                submitButton.name = 'edit_comment';
                submitButton.textContent = 'Сохранить';

                editForm.appendChild(input);
                editForm.appendChild(hiddenInput);
                editForm.appendChild(submitButton);

                const commentDiv = document.querySelector(`div.comment:nth-of-type(${commentId})`);
                commentDiv.innerHTML = '';
                commentDiv.appendChild(editForm);
        }       

    </script>

    <?php
    // Закрытие соединения
    $conn->close();
    ?>
</body>
</html>