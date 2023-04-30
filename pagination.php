<?php
require_once 'Database.php';

function getPosts($page, $perPage) {
    $db = Database::getInstance();
    $connection = $db->getConnection();

    $queryTotalPages = $connection->query('SELECT COUNT(*) FROM posts');
    $queryTotalPages->execute();
    $totalPosts = $queryTotalPages->fetchColumn();

    $totalPages = ceil($totalPosts / $perPage);

    $offset = ($page - 1) * $perPage;

    $query = $connection->prepare('SELECT * FROM posts ORDER BY createdAt DESC LIMIT :perPage OFFSET :offset');
    $query->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->execute();

    $posts = $query->fetchAll(PDO::FETCH_ASSOC);

    return array(
        'posts' => $posts,
        'totalPages' => $totalPages,
    );
}

