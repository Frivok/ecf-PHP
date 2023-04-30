<?php 
require_once('Database.php');

$db = Database::getInstance();
$conn = $db->getConnection();

$sql = "SELECT id, password FROM user";
$result = $conn->query($sql);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
    $update_sql = "UPDATE user SET password=:password WHERE id=:id";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':id', $row['id']);
    $stmt->execute();
}

$conn = null;
