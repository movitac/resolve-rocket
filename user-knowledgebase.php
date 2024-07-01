<!DOCTYPE html>
<html lang="en">

<?php
require_once('head.php');
require_once('./support/int.php');

$db = new DB();

$recodes = $db->conn->query("SELECT * FROM knowledgebase");

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
                    <h1 class="mt-4 mb-4">Knowlegde base</h1>
                    <hr>

                    <div class="card">
                        <div class="card-header">
                            List of Knowledge base
                        </div>
                        <div class="card-body">
                            <!-- <div class="row mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary" id="searchButton">Search</button>
                                </div>
                            </div> -->
                            <div class="row mb-3">
                                <div class="col">
                                    <select class="form-control" id="problemTypeSelect">
                                        <option value="">All Problem Types</option>
                                        <option value="Hardware">Hardware</option>
                                        <option value="Software">Software</option>
                                        <option value="Troubleshooting">Troubleshooting</option>
                                        <option value="others_dd">Others</option>
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
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Problem Type</th>
                                            <th>Description</th>
                                            <th>Photo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($latest as $k => $v) {
                                            echo '
                                        <tr>
                                            <td>' . $v['id'] . '</td>
                                            <td>' . $v['title'] . '</td>
                                            <td>' . $v['problem_type'] . '</td>
                                            <td>' . $v['knowledge'] . '</td>'; ?>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm " data-bs-toggle="modal" data-bs-target="#photoModal<?php echo $v['id']; ?>">
                                                    View
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="photoModal<?php echo $v['id']; ?>" tabindex="-1" aria-labelledby="photoModalLabel<?php echo $v['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="photoModalLabel<?php echo $v['id']; ?>">Photo</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="admin/uploads/<?php echo $v['photo']; ?>" class="img-fluid" alt="Photo">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            </tr>
                                        <?php
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