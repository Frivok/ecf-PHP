
<!DOCTYPE html>
<html>
<head>
    <title>Modifier un utilisateur</title>
</head>
<body>
    <?php
    require_once 'Database.php';
    include 'template.php';

    session_start();

    if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
        header('Location: login.php');
        exit;
    }

    $user = null;
    if (isset($_GET['email'])) {
        $email = $_GET['email'];

        $db = Database::getInstance();
        $connection = $db->getConnection();

        $query = $connection->prepare('SELECT * FROM user WHERE email = :email');
        $query->bindParam(':email', $email);
        $query->execute();

        $user = $query->fetch();
    }

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $newRole = $_POST['newRole'];

        $db = Database::getInstance();
        $connection = $db->getConnection();

        $query = $connection->prepare('UPDATE user SET role = :newRole WHERE email = :email');
        $query->bindParam(':newRole', $newRole);
        $query->bindParam(':email', $email);
        $query->execute();

        header('Location: admin.php');
        exit;
    }
    ?>
    <div class="container">
  <div class="row">
    <div class="col-md-6 mx-auto">
    <h2>Modifier le role d'un utilisateur</h2>
      <form method="post">
        <div class="form-group">
          <label for="email">Adresse email :</label>
          <input type="email" class="form-control" name="email" required="" value="<?php echo isset($user) ? htmlspecialchars($user['email']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="newRole">Nouveau rôle :</label>
          <select class="form-control" name="newRole" required>
            <option value="user" <?php if (isset($user) && $user['role'] == 'user') { echo 'selected'; } ?>>user</option>
            <option value="admin" <?php if (isset($user) && $user['role'] == 'admin') { echo 'selected'; } ?>>admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Modifier le rôle</button>
      </form>
    </div>
  </div>
</div>


</body>
</html>
