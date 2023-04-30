<?php
require_once 'Database.php';

$db = Database::getInstance();
$connection = $db->getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$query = $connection->prepare('SELECT * FROM comments WHERE id = :id');
$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$query->execute();

$comment = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $body = $_POST['body'];

    $query = $connection->prepare('UPDATE comments SET body = :body, createdAt = :createdAt WHERE id = :id');
    $query->bindParam(':body', $body);
    $query->bindParam(':createdAt', date('Y-m-d H:i:s'));
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

    if ($query->execute()) {
        header('Location: post.php?id=' . $comment['postId']);
        exit;
    } else {
        echo '<p>Une erreur est survenue lors de la mise à jour du commentaire.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un commentaire</title>
</head>
<body>
    <h1>Modifier un commentaire</h1>
    <form method="post">
        <div>
            <label for="body">Contenu:</label>
            <textarea id="body" name="body" required><?php echo htmlspecialchars($comment['body']); ?></textarea>
        </div>
        <input type="submit" name="update" value="Modifier">
    </form>
    <p><a href="post.php?id=<?php echo $comment['postId']; ?>">Retourner au poste</a></p>
</body>
</html>
