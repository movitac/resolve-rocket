<!DOCTYPE html>
<html lang="en">

<?php
require_once('head.php');
require_once('../support/int.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

$success = false;
$error = false;
if (!isset($_GET['id'])) {
    header('Location: ./admin-dashboard.php');
}
require_once('../support/int.php');
if (!isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] != true) {
    header('Location: ./admin-login.php');
}
$db = new DB();
$ticket = '';
$ticket_id = $_GET['id'];
// Check if the ticket status is 0, and update it to 1 if needed
$check_status_query = $db->conn->query("SELECT status FROM tickets WHERE id = $ticket_id");
if ($check_status_query->num_rows > 0) {
    $status_row = $check_status_query->fetch_assoc();
    $status = $status_row['status'];
    if ($status == 0) {
        // Update the status to 1
        $db->conn->query("UPDATE tickets SET status = 1,admin_id = '" . $_SESSION['id'] . "' WHERE id = $ticket_id");
    }
} else {
    // If the ticket doesn't exist, redirect to the admin dashboard
    header('Location: ./admin-dashboard.php');
    exit(); // Stop further execution
}

// Proceed to fetch the ticket details
$this_ticket_query = $db->conn->query("SELECT * FROM tickets a JOIN users AS b ON a.userid = b.id  WHERE a.id = $ticket_id");
if ($this_ticket_query->num_rows > 0) {
    while ($row = $this_ticket_query->fetch_assoc()) {
        $ticket = $row;
    }
} else {
    // If the ticket doesn't exist, redirect to the admin dashboard
    header('Location: ./admin-dashboard.php');
    exit(); // Stop further execution
}

$reps = [];
if ($ticket != '') {
    $replies = $db->conn->query("SELECT * FROM ticket_reply WHERE ticket_id =$ticket_id");
    if ($replies->num_rows > 0) {
        while ($row = $replies->fetch_assoc()) {
            $reps[] = $row;
        }
    }
}
//Reply Send Method
if (isset($_POST['submit'])) {
    $message = $_POST['message'];
    $send_mail = $_POST['send_mail'];
    $send_ticket_id = $_POST['send_ticket_id'];

    if ($db->conn->query("INSERT INTO ticket_reply (ticket_id,send_by,message) VALUES('$ticket_id','1','$message')")) {

        $db->conn->query("UPDATE tickets SET status=2 WHERE id=$ticket_id");

        $subject = "Ticket Replied";
        $emailMessage = "Your ticket id: " . $send_ticket_id . " has been replied.";

        // Load composer's autoloader
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'resolverocket@gmail.com';
            $mail->Password = 'wnyvxupscttfbryq';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('resolverocket@gmail.com', 'Resolve Rocket');
            $mail->addAddress($send_mail);
            $mail->addReplyTo('resolverocket@gmail.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $emailMessage;

            $mail->send();

            // Display success message and refresh the page
            echo "<script>
                    alert('Reply has been sent to user\'s email.');
                    window.location.href = window.location.href;
                  </script>";
        } catch (Exception $e) {
            $error = "Can not send reply: " . $mail->ErrorInfo;
        }
    } else {
        $error = "Can not send reply";
    }
}
?>


<body class="sb-nav-fixed">
    <?php include 'auth.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include "sidebar.php" ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-4">Reply Ticket ID : <?php echo $ticket['ticket_id']; ?></h1>
                    <hr>

                    <div class="card mb-3">
                        <div class="card-header">
                            Ticket ID : <?php echo $ticket['ticket_id']; ?>

                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($error) && $error != false) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                            ?>
                            <?php
                            if (isset($success) && $success != false) {
                                echo '<div class="alert alert-success">' . $success . '</div>';
                            }
                            ?>
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $ticket['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $ticket['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Subject</th>
                                    <td><?php echo $ticket['subject']; ?></td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td><?php echo $ticket['department']; ?></td>
                                </tr>
                                <tr>
                                    <th>Problem Type</th>
                                    <td>
                                        <?php
                                        if ($ticket['problem_type'] == 'Others') {
                                            echo 'Others (' . $ticket['other_problem_type'] . ')';
                                        } else {
                                            echo $ticket['problem_type'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $ticket['message']; ?></td>
                                </tr>
                            </table>
                            <!-- <p><?php echo $ticket['message']; ?></p> -->
                            <div class="reply-area">
                                <ul>
                                    <?php if (count($reps) > 0) { ?>
                                        <?php foreach ($reps as $k => $v) {
                                            if ($v['send_by'] == 0) {
                                        ?>
                                                <li class="reply-user">
                                                    <div class="card bg-info text-white">
                                                        <div class="card-body">
                                                            <p><?php echo $v['message']; ?></p>
                                                            <div class="text-right">
                                                                <span><?php echo $v['date']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li class="reply-me">
                                                    <div class="card bg-gray text-dark">
                                                        <div class="card-body">
                                                            <p><?php echo $v['message']; ?></p>
                                                            <small class="text-muted" style="float:right;">Send by support team at <?php echo $v['date']; ?></small>
                                                        </div>
                                                    </div>
                                                </li>



                                        <?php
                                            }
                                        }
                                        ?>

                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if ($ticket['status_ticket'] != 'closed') { ?>
                                <div class="send-area">
                                    <form method="POST">

                                        <div class="form-group">
                                            <textarea name="message" required class="form-control" placeholder="Reply" id="message" cols="30" rows="4"></textarea>
                                        </div>
                                        <div class="form-group text-right mt-3">
                                            <input type="hidden" name="submit" value="send">
                                            <input type="hidden" name="send_mail" value="<?php echo $ticket['email']; ?>">
                                            <input type="hidden" name="send_ticket_id" value="<?php echo $ticket['ticket_id']; ?>">
                                            <button class="btn btn-success" type="submit">Send</button>
                                        </div>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Resolve Rocket 2024</div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>


</body>


<script src="../support/assets/js/jquery-3.6.0.min.js"></script>
<script src="../support/assets/js/popper.min.js"></script>
<script src="../support/assets/js/bootstrap.min.js"></script>

</html>