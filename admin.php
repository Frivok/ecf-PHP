<?php 
require_once 'Database.php';
require_once 'pagination.php';
include 'template.php';

session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}


$db = Database::getInstance();
$connection = $db->getConnection();

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 12;

$postsData = getPosts($page, $perPage);

$posts = $postsData['posts'];
$totalPages = $postsData['totalPages'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Page Admin</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h2>Page Admin</h2>
		<p>Bienvenue, <?php echo $_SESSION['email']; ?> !</p>
	    <a href="edit-user.php">Modifier le rôle d'un utilisateur en admin !</a>
        <p><a href="logout.php">Se déconnecter</a></p>

	    <h1>Liste des posts</h1>
	    <table class="table">
	        <thead>
	            <tr>
	                <th>Titre</th>
	                <th>Date de création</th>
	                <th>Actions</th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($posts as $post): ?>
	                <tr>
	                    <td><?php echo htmlspecialchars($post['title']); ?></td>
	                    <td><?php echo htmlspecialchars($post['createdAt']); ?></td>
	                    <td class="modifier">
	                        <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Modifier</a>
	                        <a href="postes.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Voir</a>
	                        <form method="post" action="delete-post.php?id=<?php echo $post['id']; ?>">
	                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
	                            <input type="submit" value="Supprimer" class="btn btn-danger">
	                        </form>
	                    </td>
	                </tr>
	            <?php endforeach; ?>
	        </tbody>
	    </table>
	    
	    <nav>
	        <ul class="pagination">
	            <?php if ($page > 1): ?>
	                <li class="page-item"><a href="?page=<?php echo ($page - 1); ?>" class="page-link">Page précédente</a></li>
	            <?php endif; ?>

	            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
	                <?php if ($i == $page): ?>
	                    <li class="page-item active"><a href="#" class="page-link"><?php echo $i; ?></a></li>
	                <?php else: ?>
	                    <li class="page-item"><a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
	                <?php endif; ?>
	            <?php endfor; ?>

	            <?php if ($page < $totalPages): ?>
	                <li class="page-item"><a href="?page=<?php echo ($page + 1); ?>" class="page-link">Page suivante</a></li>
	            <?php endif; ?>
	        </ul>
	    </nav>
	</div>
</body>
</html>
