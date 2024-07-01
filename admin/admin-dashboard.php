<!DOCTYPE html>
<html lang="en">


<?php
require_once('head.php');
require_once('../support/int.php');
// require_once('auth.php');


$new_status = 0;
$opened = 1;
$reply_status = 2;

$new_count = 0;
$opened_count = 0;
$reply_count = 0;

$db = new DB();

$new_tickets_query = "SELECT COUNT(*) AS new_tickets FROM tickets WHERE status=$new_status";
$ntr = $db->conn->query($new_tickets_query);
if ($ntr->num_rows > 0) {
    while ($row = $ntr->fetch_assoc()) {
        $new_count = $row['new_tickets'];
    }
}


$inprogress_query = "SELECT COUNT(*) AS in_progress FROM tickets WHERE status_ticket='in progress'";
$ntr = $db->conn->query($inprogress_query);
if ($ntr->num_rows > 0) {
    while ($row = $ntr->fetch_assoc()) {
        $inprogress_count = $row['in_progress'];
    }
}

$opened_tickets_query = "SELECT COUNT(*) AS opened_ticket FROM tickets WHERE status=$opened";
$rtc = $db->conn->query($opened_tickets_query);
if ($rtc->num_rows > 0) {
    while ($row = $rtc->fetch_assoc()) {
        $opened_count = $row['opened_ticket'];
    }
}

$feedback_query = "SELECT COUNT(*) AS feedback_user FROM feedback";
$rtc = $db->conn->query($feedback_query);
if ($rtc->num_rows > 0) {
    while ($row = $rtc->fetch_assoc()) {
        $feedback_count = $row['feedback_user'];
    }
}

$total_query = "SELECT COUNT(*) AS total_ticket FROM tickets";
$rtc = $db->conn->query($total_query);
if ($rtc->num_rows > 0) {
    while ($row = $rtc->fetch_assoc()) {
        $total_ticket = $row['total_ticket'];
    }
}

$knowledgebase_query = "SELECT COUNT(*) AS knowledgebase FROM knowledgebase";
$rtc = $db->conn->query($knowledgebase_query);
if ($rtc->num_rows > 0) {
    while ($row = $rtc->fetch_assoc()) {
        $knowledgebase_count = $row['knowledgebase'];
    }
}

$reply_tickets_query = "SELECT COUNT(*) AS new_tickets FROM tickets WHERE status=$reply_status";
$ctr = $db->conn->query($reply_tickets_query);
if ($ctr->num_rows > 0) {
    while ($row = $ctr->fetch_assoc()) {
        $closed_count = $row['new_tickets'];
    }
}

$recodes = $db->conn->query("SELECT a.id, a.subject, a.message, a.date, a.ticket_id, b.name, b.email, a.status, a.priority, a.status_ticket
FROM tickets AS a 
JOIN users AS b ON a.userid = b.id 
WHERE a.archive IS NULL OR a.archive != 'yes'
ORDER BY a.date DESC");

$latest = array(); // Initialize an array to store fetched records

if ($recodes->num_rows > 0) {
    while ($row = $recodes->fetch_assoc()) {
        $latest[] = $row; // Store fetched record in the array
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
                    <h1 class="mt-4 mb-4">Dashboard</h1>
                    <hr>
                    <center>
                        <h3>Welcome <?php echo $name ?> [admin]</h3>
                        <!-- <img src="../assets/img/logo.png" alt="MICORS Logo" width="150" height="150"> -->
                    </center>

                    <div class="row mt-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-primary">
                                    <div class="card-body text-white">
                                        <h3>Total Tickets</h3>
                                        <h2><?php echo $total_ticket; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-danger">
                                    <div class="card-body text-white">
                                        <h3>New Tickets</h3>
                                        <h2><?php echo $new_count; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-success">
                                    <div class="card-body text-white">
                                        <h3>Replied Tickets</h3>
                                        <h2><?php echo $closed_count; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-warning">
                                    <div class="card-body text-white">
                                        <h3>Knowledgebase</h3>
                                        <h2><?php echo $knowledgebase_count; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-dark">
                                    <div class="card-body text-white">
                                        <h3>Feedback</h3>
                                        <h2><?php echo $feedback_count; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-secondary">
                                    <div class="card-body text-white">
                                        <h3>Opened Tickets</h3>
                                        <h2><?php echo $opened_count; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-info">
                                    <div class="card-body text-white">
                                        <h3>In Progress Tickets</h3>
                                        <h2><?php echo $inprogress_count; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Tickets
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary" id="searchButton">Search</button>
                                </div>
                            </div>
                            <?php if (count($latest) > 0) { ?>
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <!-- <th>Email</th> -->
                                            <th>Subject</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Ticket ID</th>
                                            <th>Log Status</th>
                                            <th>Priority</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latest as $k => $v) : ?>
                                            <tr>
                                                <td><?php echo $v['id']; ?></td>
                                                <td><?php echo $v['name']; ?></td>
                                                <!-- <td><?php echo $v['email']; ?></td> -->
                                                <td><?php echo $v['subject']; ?></td>
                                                <td><?php echo $v['message']; ?></td>
                                                <td><?php echo $v['date']; ?></td>
                                                <td><?php echo $v['ticket_id']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($v['status'] == 0) {
                                                        echo 'New Ticket';
                                                    } elseif ($v['status'] == 1) {
                                                        echo 'Opened';
                                                    } elseif ($v['status'] == 2) {
                                                        echo 'Replied';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $v['priority']; ?></td>
                                                <td>
                                                    <a href="./admin-ticket-view.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-info">View</a>
                                                    <div id="archive-btn-<?php echo $v['id']; ?>">
                                                        <?php if ($v['status_ticket'] == 'closed') : ?>
                                                            <a href="archive_status_ticket.php?archiveid=<?php echo $v['id']; ?>" class="btn mt-1 btn-sm btn-danger">Archive</a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <select class="mt-2 form-control status_ticket-select" data-ticket-id="<?php echo $v['id']; ?>">
                                                        <option value="pending" <?php echo ($v['status_ticket'] == 'pending' ? ' selected' : ''); ?>>Pending</option>
                                                        <option value="in progress" <?php echo ($v['status_ticket'] == 'in progress' ? ' selected' : ''); ?>>In Progress</option>
                                                        <option value="closed" <?php echo ($v['status_ticket'] == 'closed' ? ' selected' : ''); ?>>Closed</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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
<script>
    $(document).ready(function() {
        $('.status_ticket-select').change(function() {
            var ticketId = $(this).data('ticket-id');
            var status_ticket = $(this).val();

            $.ajax({
                type: "POST",
                url: "update_status_ticket.php",
                data: {
                    ticketId: ticketId,
                    status_ticket: status_ticket
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert('Status ticket updated successfully');
                        updateDeleteButton(ticketId, status_ticket);
                    } else {
                        alert('Failed to update status ticket');
                    }
                },
                error: function() {
                    alert('Failed to update status ticket');
                }
            });
        });



        function updateDeleteButton(ticketId, status_ticket) {
            var deleteButtonContainer = $('#archive-btn-' + ticketId);

            if (status_ticket === 'closed') {
                deleteButtonContainer.html('<button class="btn mt-1 btn-sm btn-danger archive-btn" data-ticket-id="' + ticketId + '">Archive</button>');
            } else {
                deleteButtonContainer.html('');
            }
        }
    });
</script>


<script>
    $(document).ready(function() {
        $("#searchButton").click(function() {
            var value = $("#searchInput").val().toLowerCase();
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>


</html>