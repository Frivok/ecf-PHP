<?php
require_once 'Database.php';
include 'template.php';

$db = Database::getInstance();
$connection = $db->getConnection();

$query = $connection->prepare('SELECT * FROM posts WHERE id = :id');
$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$query->execute();

$post = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];

    $query = $connection->prepare('UPDATE posts SET title = :title, body = :body, createdAt = :createdAt WHERE id = :id');
    $query->bindParam(':title', $title);
    $query->bindParam(':body', $body);
    $query->bindParam(':createdAt', date('Y-m-d H:i:s'));
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

    if ($query->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo '<p>Une erreur est survenue lors de la mise à jour du poste.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un poste</title>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Modifier un poste</h1>
        <form method="post">
            <div class="form-group">
                <label for="title">Titre:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="body">Contenu:</label>
                <textarea id="body" name="body" class="form-control" required><?php echo htmlspecialchars($post['body']); ?></textarea>
            </div>
            <input type="submit" name="update" value="Modifier" class="btn btn-primary">
        </form>
        <p class="my-4"><a href="index.php" class="btn btn-secondary">Retourner à la liste des postes</a></p>
    </div>
</body>
</html>

