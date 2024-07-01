<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolve Rocket</title>
    <link rel="stylesheet" href="./support/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./support/assets/css/app.css">
    <link rel="stylesheet" href="./support/assets/css/admin.css">


</head>
<?php

require_once('./support/int.php');
if (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == true) {
    header('Location: ./user-dashboard.php');
}

$erro = false;
if (isset($_POST['username'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];

    $db = new DB();
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $db->conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['status_user'] == 'active') {
                if ($row['password'] == md5($password)) {
                    $_SESSION['user_logged'] = true;
                    $_SESSION['id'] = $row['id'];
                    header('Location: ./user-dashboard.php');
                    exit();
                } else {
                    $error = 'Invalid username or password, please try again';
                }
            } else {
                $error = 'Your account has been deactivated. Please contact the administrator for assistance.';
            }
        }
    } else {
        $error = 'Invalid username or password, please try again';
    }
}
?>

<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="panel-heading" style="background:#00796B;color:white; border-radius: 5px 5px 0 0; padding: 15px;">
                        <div class="panel-title">Resolve Rocket - Login</div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if (isset($error)) : ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" id="username" required placeholder="Username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" required placeholder="Password" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Login</button>
                            </div>
                        </form>
                        <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
<script src="./support/assets/js/jquery-3.6.0.min.js"></script>
<script src="./support/assets/js/popper.min.js"></script>
<script src="./support/assets/js/bootstrap.min.js"></script>

</html>