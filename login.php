<?php
require_once 'Database.php';
include 'template.php';
ob_start();
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $db = Database::getInstance();
    $connection = $db->getConnection();

    $email = $_POST['email'];
	$password = $_POST['password'];

    $query = $connection->prepare('SELECT * FROM user WHERE email = :email');
    $query->bindParam(':email', $email);

    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

	if ($row && password_verify($password, $row['password'])) {
		$_SESSION['email'] = $email;
        $_SESSION['role'] = $row['role'];
		header('Location: admin.php');
		exit;
	} else {
		$error = 'Email ou mot de passe incorrect';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
</head>
<body>
	<div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Login</h2>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="email">Nom d'utilisateur:</label>
                                <input type="text" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
