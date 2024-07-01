<!DOCTYPE html>
<html lang="en">

<?php
require_once('head.php');
require_once('../support/int.php');
require_once('auth.php');

$new_status = 0;
$waiting_reply_status = 1;
$closed_status = 2;

$new_count = 0;
$reply_count = 0;
$closed_count = 0;

$db = new DB();

$latest = [];
$recodes = $db->conn->query("SELECT * FROM tickets WHERE status=1 ORDER BY 'date' DESC ");
if ($recodes->num_rows > 0) {
    while ($row = $recodes->fetch_assoc()) {
        $latest[] = $row;
    }
}

?>

<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="list-inline admn_ul">
                            <a href="./admin-dashboard.php" class="list-inline-item">Dashboard</a>
                            <a href="./admin-new-tickets.php" class="list-inline-item">New Tickets</a>
                            <a href="./admin-waiting-tickets.php" class="list-inline-item">Waiting For Reply</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        New Tickets
                    </div>
                    <div class="card-body">
                        <?php if (count($latest) > 0) { ?>
                            <table class="table table-striped table-inverse">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($latest as $k => $v) {
                                        echo '
                                    <tr>
                                        <td>' . $v['id'] . '</td>
                                        <td>' . $v['name'] . '</td>
                                        <td>' . $v['email'] . '</td>
                                        <td>' . $v['subject'] . '</td>
                                        <td>' . $v['date'] . '</td>
                                        <td><a href="./admin-ticket-view.php?id=' . $v['id'] . '" class="btn btn-sm btn-info">View<a/></td>
                                    </tr>
                                    ';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php } else {
                            echo '<div class="alert alert-info">No any new tickets</div>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../support/assets/js/jquery-3.6.0.min.js"></script>
<script src="../support/assets/js/popper.min.js"></script>
<script src="../support/assets/js/bootstrap.min.js"></script>

</html>