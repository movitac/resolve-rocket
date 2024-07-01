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

    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new DB();

    // Check if the username is already taken
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $db->conn->query($query);

    if ($result->num_rows > 0) {
        $erro = 'Username is already taken, please choose another one';
    } else {
        // Insert new user into the database
        $hashed_password = md5($password); // You should consider using more secure methods like password_hash() instead of md5
        $insert_query = "INSERT INTO users (name, email, username, password) VALUES ('$name', '$email', '$username', '$hashed_password')";

        if ($db->conn->query($insert_query) === TRUE) {
            echo '<script>alert("Registration successful! You can now login.");';
            echo 'window.location.href = "./index.php";</script>';
            exit();
        } else {
            $erro = 'Error: ' . $db->conn->error;
        }
    }
}

?>

<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="panel-heading" style="background:#00796B;color:white; border-radius: 5px 5px 0 0; padding: 15px;">
                        <div class="panel-title">Resolve Rocket - Register</div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php
                            if (isset($erro) && $erro != false) {
                                echo '<div class="alert alert-danger">' . $erro . '</div>';
                            }
                            ?>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name" required placeholder="Name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="email" required placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" id="username" required placeholder="Username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" required placeholder="Password" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Register</button>
                            </div>
                        </form>
                        <p class="text-center">Already have account? <a href="index.php">Login here</a></p>
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