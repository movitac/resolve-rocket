<!DOCTYPE html>
<html lang="en">

<?php
require_once('head.php');
require_once('./support/int.php');

$db = new DB();

$recodes = $db->conn->query("SELECT a.id, a.subject, a.message, a.date, a.ticket_id,a.status,a.priority,a.department,a.problem_type,a.other_problem_type,a.status_ticket
                            FROM tickets AS a 
                            JOIN users AS b ON a.userid = b.id 
                            WHERE a.userid='" . $_SESSION['id'] . "' 
                            ORDER BY a.date DESC ");

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


                    <div class="card">
                        <div class="card-header">
                            My Tickets
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <select class="form-control" id="problemTypeSelect">
                                        <option value="">All Problem Types</option>
                                        <option value="Hardware">Hardware</option>
                                        <option value="Software">Software</option>
                                        <option value="Troubleshooting">Troubleshooting</option>
                                        <option value="Others">Others</option>
                                        <option value="others_dd">Specify Other</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary" id="searchButton">Filter</button>
                                </div>
                            </div>
                            <div class="row mb-3" id="otherProblemTypeDiv" style="display: none;">
                                <div class="col">
                                    <input type="text" class="form-control" id="otherProblemTypeInput" placeholder="Please specify">
                                </div>
                            </div>
                            <?php if (count($latest) > 0) { ?>
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>No</th>

                                            <th>Priority</th>
                                            <th>Subject</th>
                                            <th>Department</th>
                                            <th>Problem Type</th>
                                            <th>Description</th>
                                            <th>Ticket ID</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($latest as $k => $v) {
                                            echo '
                                            <tr>
                                                <td>' . $v['id'] . '</td>
                                                <td>
                                                    <select class="form-control priority-select" data-ticket-id="' . $v['id'] . '">
                                                        <option value="normal"' . ($v['priority'] == 'normal' ? ' selected' : '') . '>Normal</option>
                                                        <option value="medium"' . ($v['priority'] == 'medium' ? ' selected' : '') . '>Medium</option>
                                                        <option value="urgent"' . ($v['priority'] == 'urgent' ? ' selected' : '') . '>Urgent</option>
                                                    </select>
                                                </td>
                                                <td>' . $v['subject'] . '</td>
                                                <td>' . $v['department'] . '</td>
                                                <td>';
                                            if ($v['problem_type'] == 'Others') {
                                                echo 'Others (' . $v['other_problem_type'] . ')';
                                            } else {
                                                echo $v['problem_type'];
                                            }
                                            echo '</td>
                                                <td>' . $v['message'] . '</td>
                                                <td>' . $v['ticket_id'] . '</td>
                                                <td>' . $v['date'] . '</td>
                                                <td>';
                                            echo $v['status_ticket'];
                                            // if ($v['status'] == 0) {
                                            //     echo 'Pending';
                                            // } elseif ($v['status'] == 1) {
                                            //     echo 'Opened';
                                            // } elseif ($v['status'] == 2) {
                                            //     echo 'Replied';
                                            // }
                                            echo '</td>
                                            </tr>
                                            ';
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            <?php } else {
                                echo '<div class="alert alert-info">No any tickets</div>';
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




    <div class="chatbot" id="chatbot">
        <div class="chatbot-header">
            Chatbot
            <button class="close-btn" onclick="toggleChatbot()">X</button>
        </div>
        <div class="chatbot-body" id="chatbotBody">
            <!-- Chatbot messages will be displayed here -->
        </div>
        <div class="chatbot-footer">
            <input type="text" id="userInput" placeholder="Type a message..." onkeypress="checkEnter(event)">
            <button onclick="generateResponse()">Send</button>
        </div>
        <!-- <div id="response"></div> -->
    </div>
    <button class="open-btn" onclick="toggleChatbot()">
        <i class="fas fa-comment"></i>
    </button>

    <style>
        .user-message {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            clear: both;
            /* Ensure proper clearing for floating elements */
            max-width: 70%;
            /* Limit width to ensure responsiveness */
            float: right;
            /* Align user messages to the right */
        }

        .bot-message {
            background-color: #4BD65A;
            color: black;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            clear: both;
            /* Ensure proper clearing for floating elements */
            max-width: 70%;
            /* Limit width to ensure responsiveness */
            float: left;
            /* Align bot messages to the left */
        }

        
    </style>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="./js/scripts.js"></script>


    <script src="./support/assets/js/jquery-3.6.0.min.js"></script>
    <script src="./support/assets/js/popper.min.js"></script>
    <script src="./support/assets/js/bootstrap.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     $("#searchButton").click(function() {
        //         var value = $("#searchInput").val().toLowerCase();
        //         $("table tbody tr").filter(function() {
        //             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //         });
        //     });
        // });
    </script>
    <script>
        $(document).ready(function() {
            $('.priority-select').change(function() {
                var ticketId = $(this).data('ticket-id');
                var priority = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "update_priority.php",
                    data: {
                        ticketId: ticketId,
                        priority: priority
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            alert('Priority updated successfully');
                        } else {
                            alert('Failed to update priority');
                        }
                    },
                    error: function() {
                        alert('Failed to update priority');
                    }
                });
            });
        });



        function toggleChatbot() {
            var chatbot = document.getElementById('chatbot');
            chatbot.classList.toggle('open');
        }

        function generateResponse() {
            var userInput = document.getElementById('userInput').value.trim();
            var chatbotBody = document.getElementById('chatbotBody');

            if (userInput !== '') {
                // Add user input to the chatbot body
                var userMessage = '<div class="user-message">' + userInput + '</div>';
                chatbotBody.innerHTML += userMessage;

                fetch("response.php", {
                        method: "POST",
                        body: JSON.stringify({
                            text: userInput,
                        }),
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Add response to the chatbot body with custom styling
                        var botMessage = '<div class="bot-message">' + data + '</div>';
                        chatbotBody.innerHTML += botMessage;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                // Clear user input
                document.getElementById('userInput').value = '';
            }
        }

        function checkEnter(event) {
            if (event.key === 'Enter') {
                generateResponse();
            }
        }
    </script>
    <script>
        document.getElementById('problemTypeSelect').addEventListener('change', function() {
            var otherProblemTypeDiv = document.getElementById('otherProblemTypeDiv');
            if (this.value === 'others_dd') {
                otherProblemTypeDiv.style.display = 'block';
            } else {
                otherProblemTypeDiv.style.display = 'none';
            }
        });

        $(document).ready(function() {
            $("#searchButton").click(function() {
                var selectedValue = $("#problemTypeSelect").val().toLowerCase();
                var filterValue = selectedValue === 'others_dd' ? $("#otherProblemTypeInput").val().toLowerCase() : selectedValue;

                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(filterValue) > -1)
                });
            });
        });
    </script>
</body>



</html>