<?php
require_once 'Database.php';

$db = Database::getInstance();
$connection = $db->getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid or missing 'id' parameter";
    exit;
}

$query = $connection->prepare('SELECT * FROM posts WHERE id = :id');
$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$query->execute();

$post = $query->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "Post not found";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $query = $connection->prepare('DELETE FROM comments WHERE postId = :id; DELETE FROM posts WHERE id = :id');
    $query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);

    if ($query->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo '<p>Une erreur est survenue lors de la suppression du poste.</p>';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer un poste</title>
</head>
<body>
    <h1>Supprimer un poste</h1>
    <?php if ($post): ?>
        <p>Voulez-vous vraiment supprimer le poste "<?php echo htmlspecialchars($post['title']); ?>" ?</p>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
            <input type="submit" name="delete" value="Supprimer">
        </form>
    <?php else: ?>
        <p>Post not found</p>
    <?php endif; ?>
    <p><a href="index.php">Retourner à la liste des postes</a></p>
</body>
</html>
