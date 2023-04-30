<?php
require_once 'Database.php';

$db = Database::getInstance();
$connection = $db->getConnection();

if (!isset($_GET['postId']) || !is_numeric($_GET['postId'])) {
    exit;
}

$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 2;

$query = $connection->prepare('SELECT * FROM comments WHERE postId = :postId ORDER BY createdAt DESC LIMIT ' . $offset . ', ' . $limit);
$query->bindParam(':postId', $_GET['postId'], PDO::PARAM_INT);
$query->execute();
$comments = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($comments) === 0) {
    exit;
}

foreach ($comments as $comment) {
    echo '<div class="comment">';
    echo '<h3>' . $comment['email'] . '</h3>';
    echo '<p>Publié le ' . $comment['createdAt'] . '</p>';
    echo '<p>' . $comment['body'] . '</p>';
    echo '</div>';
}
