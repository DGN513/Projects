<?php
require "connect.php";

// Проверяем, передан ли ID новости и авторизован ли пользователь
if (isset($_GET['id']) && isset($_COOKIE['idUser'])) {
    $newsId = $_GET['id'];
    $userId = $_COOKIE['idUser'];

    // Подготавливаем запрос для проверки, что новость принадлежит текущему пользователю
    $stmt = $conn->prepare("SELECT id FROM News WHERE id = ? AND id_user = ?");
    $stmt->bind_param("ii", $newsId, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Сначала удаляем все лайки, связанные с комментариями новости
        $deleteLikesStmt = $conn->prepare("DELETE FROM CommentLikes WHERE id_comment IN (SELECT id_comment FROM Comments WHERE id_news = ?)");
        $deleteLikesStmt->bind_param("i", $newsId);
        $deleteLikesStmt->execute();
        $deleteLikesStmt->close();

        // Затем удаляем все комментарии, связанные с новостью
        $deleteCommentsStmt = $conn->prepare("DELETE FROM Comments WHERE id_news = ?");
        $deleteCommentsStmt->bind_param("i", $newsId);
        $deleteCommentsStmt->execute();
        $deleteCommentsStmt->close();

        // После удаления комментариев удаляем новость
        $deleteStmt = $conn->prepare("DELETE FROM News WHERE id = ?");
        $deleteStmt->bind_param("i", $newsId);
        if ($deleteStmt->execute()) {
            echo "Новость успешно удалена.";
        } else {
            echo "Ошибка при удалении новости.";
        }
        $deleteStmt->close();
    } else {
        echo "Вы не можете удалить эту новость.";
    }

    $stmt->close();
} else {
    echo "Не авторизован или неверный запрос.";
}

$conn->close();

// Перенаправление обратно на главную страницу после удаления
header("Location: index.php");
exit();
?>