<!DOCTYPE html>
<html lang="en">


<?php
require_once('head.php');
require_once('../support/int.php');

$db = new DB();

$latest = [];
$recodes = $db->conn->query("SELECT a.id,a.feedback,b.name,b.email
FROM feedback AS a 
JOIN users AS b ON a.userid = b.id 
WHERE a.userid=b.id
ORDER BY a.date DESC");
if ($recodes->num_rows > 0) {
    while ($row = $recodes->fetch_assoc()) {
        $latest[] = $row;
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
                    <h1 class="mt-4 mb-4">Feedback</h1>
                    <hr>

                    <div class="card">
                        <div class="card-header">
                            Feedback List
                        </div>
                        <div class="card-body">
                            <?php if (count($latest) > 0) { ?>
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Feedback</th>
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
                                        <td>' . $v['feedback'] . '</td>
                                    
                                    </tr
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