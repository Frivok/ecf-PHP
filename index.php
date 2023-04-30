<?php
require_once 'Database.php';
include 'template.php';

$perPage = 12;

$db = Database::getInstance();
$connection = $db->getConnection();

$queryTotalPages = $connection->query('SELECT COUNT(*) FROM posts');
$queryTotalPages->execute();
$totalPosts = $queryTotalPages->fetchColumn();

$totalPages = ceil($totalPosts / $perPage);

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$offset = ($page - 1) * $perPage;

$query = $connection->prepare('SELECT * FROM posts ORDER BY createdAt DESC LIMIT :perPage OFFSET :offset');
$query->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$query->bindParam(':offset', $offset, PDO::PARAM_INT);
$query->execute();
?>

<!DOCTYPE html>
<html>

<head>
  <title>My Webpage</title>
</head>

<body class="bg-dark">
  <div class="container-fluid">
    <div class="row justify-content-end">
      <div class="col-auto">
        <a href="login.php" class="btn btn-outline-dark">Login</a>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h1 class="text-center mb-5">Liste des postes</h1>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <?php while ($row = $query->fetch(PDO::FETCH_ASSOC)) : ?>
        <div class="col-md-4">
          <div class="card mb-4 mt-4 shadow-sm bg-dark text-light">
            <div class="card-body">
              <h2 class="card-title"><?php echo $row['title']; ?></h2>
              <p class="card-text"><?php echo substr($row['body'], 0, 50); ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Publié le <?php echo $row['createdAt']; ?></small>
                <button type="button" class="btn btn-sm btn-outline-primary"><a href="postes.php?id=<?php echo $row['id']; ?>">Lire la suite</a></button>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>


<?php if ($totalPages > 1) : ?>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-5">
      <?php if ($page > 1) : ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo ($page - 1); ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Page précédente</span>
          </a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <li class="page-item<?php echo ($i == $page) ? ' active' : ''; ?>">
          <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $totalPages) : ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo ($page + 1); ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Page suivante</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
<?php endif; ?>
</div>

<form action="logout.php" method="post">
  <button type="submit">Se déconnecter</button>
</form>
</body>

</html>