<?php
require_once 'Database.php';

session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    echo "You are not allowed to delete comments";
    exit;
}

$db = Database::getInstance();
$connection = $db->getConnection();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "Invalid or missing 'id' parameter";
    exit;
}

$id = $_POST['id'];

$query = $connection->prepare('SELECT * FROM comments WHERE id = :id');
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();

$comment = $query->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    echo "Comment not found";
    exit;
}

$query = $connection->prepare('DELETE FROM comments WHERE id = :id');
$query->bindParam(':id', $id, PDO::PARAM_INT);

if ($query->execute()) {
    echo "success";
} else {
    echo "Error deleting comment";
}
