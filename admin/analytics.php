<!DOCTYPE html>
<html lang="en">

<?php
require_once('head.php');
require_once('../support/int.php');
// require_once('auth.php');
$db = new DB();

// Function to fetch ticket counts by admin_id for a given status
function fetchTicketCountsByStatus($db, $status_column, $status_value)
{
    $query = "SELECT a.name, COUNT(t.id) AS ticket_count 
              FROM tickets t 
              JOIN admin a ON t.admin_id = a.id 
              WHERE t.$status_column='$status_value'
              GROUP BY a.name";
    $result = $db->conn->query($query);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['name']] = $row['ticket_count'];
        }
    }
    return $data;
}

// Function to fetch problem type counts
function fetchProblemTypeCounts($db)
{
    $query = "SELECT problem_type, COUNT(id) AS problem_count 
              FROM tickets 
              GROUP BY problem_type";
    $result = $db->conn->query($query);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['problem_type']] = $row['problem_count'];
        }
    }
    return $data;
}

// $new_tickets = fetchTicketCountsByStatus($db, 'status', 0); // New tickets
$in_progress_tickets = fetchTicketCountsByStatus($db, 'status_ticket', 'in progress'); // In-progress tickets
$opened_tickets = fetchTicketCountsByStatus($db, 'status', 1); // Opened tickets
$closed_tickets = fetchTicketCountsByStatus($db, 'status_ticket', 'closed'); // Closed tickets
$problem_types = fetchProblemTypeCounts($db); // Problem types

// Passing data to JavaScript
echo "<script>
    var inProgressTickets = " . json_encode($in_progress_tickets) . ";
    var openedTickets = " . json_encode($opened_tickets) . ";
    var closedTickets = " . json_encode($closed_tickets) . ";
    var problemTypes = " . json_encode($problem_types) . ";
</script>";

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
                    <h1 class="mt-4 mb-4">Analytics</h1>
                    <hr>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-light">
                                    <div class="card-body text-white">
                                        <canvas id="ticketsChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div class="card bg-light">
                                    <div class="card-body text-white">
                                        <canvas id="categoryChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to merge ticket counts by admin name
    // function mergeTicketCounts(newTickets, inProgressTickets, openedTickets, closedTickets) {
    function mergeTicketCounts(inProgressTickets, openedTickets, closedTickets) {
        // var adminNames = new Set([...Object.keys(newTickets), ...Object.keys(inProgressTickets), ...Object.keys(openedTickets), ...Object.keys(closedTickets)]);
        var adminNames = new Set([...Object.keys(inProgressTickets), ...Object.keys(openedTickets), ...Object.keys(closedTickets)]);
        var data = {
            labels: [],
            // newCounts: [],
            inProgressCounts: [],
            openedCounts: [],
            closedCounts: []
        };

        adminNames.forEach(function(adminName) {
            data.labels.push(adminName);
            // data.newCounts.push(newTickets[adminName] || 0);
            data.inProgressCounts.push(inProgressTickets[adminName] || 0);
            data.openedCounts.push(openedTickets[adminName] || 0);
            data.closedCounts.push(closedTickets[adminName] || 0);
        });

        return data;
    }

    // var mergedData = mergeTicketCounts(newTickets, inProgressTickets, openedTickets, closedTickets);
    var mergedData = mergeTicketCounts(inProgressTickets, openedTickets, closedTickets);

    var ctx = document.getElementById('ticketsChart').getContext('2d');
    var ticketsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: mergedData.labels,
            datasets: [
                // {
                //     label: 'New Tickets',
                //     data: mergedData.newCounts,
                //     backgroundColor: 'rgba(75, 192, 192, 0.2)',
                //     borderColor: 'rgba(75, 192, 192, 1)',
                //     borderWidth: 1
                // },
                {
                    label: 'In Progress',
                    data: mergedData.inProgressCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Opened Tickets',
                    data: mergedData.openedCounts,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Closed Tickets',
                    data: mergedData.closedCounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Function to generate random color
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Function to merge problem type counts
    function mergeProblemTypeCounts(problemTypes) {
        var data = {
            labels: [],
            problemCounts: [],
            backgroundColors: [],
            borderColors: []
        };

        for (var problemType in problemTypes) {
            if (problemTypes.hasOwnProperty(problemType)) {
                var bgColor = getRandomColor();
                var borderColor = getRandomColor();
                data.labels.push(problemType);
                data.problemCounts.push(problemTypes[problemType]);
                data.backgroundColors.push(bgColor + '66'); // 66 for transparency
                data.borderColors.push(borderColor);
            }
        }

        return data;
    }

    var problemData = mergeProblemTypeCounts(problemTypes);

    var ctx2 = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: problemData.labels,
            datasets: [{
                label: 'Problem Types',
                data: problemData.problemCounts,
                backgroundColor: problemData.backgroundColors,
                borderColor: problemData.borderColors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</html>