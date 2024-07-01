<!DOCTYPE html>
<html lang="en">

<?php

$success = false;
$error = false;
require_once('head.php');
require_once('./support/int.php');

if (isset($_POST['id'])) {
    $db = new DB();
    $ticket_q = $db->conn->query("SELECT * FROM tickets WHERE ticket_id='" . $_POST['id'] . "'");
    if ($ticket_q->num_rows > 0) {
        while ($row  = $ticket_q->fetch_assoc()) {
            header("Location: ./ticket.php?id=" . $row['id']);
        }
        //f,s9cXM,FWgL
    } else {
        $error = "Invalid ticket id";
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
                    <h1 class="mt-4 mb-4">Check Tickets</h1>
                    <hr>

                    <form method="POST">

                        <div class="card">
                            <div class="card-header"> Check Your Ticket</div>
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
                                <div class="form-group">
                                    <label>Enter Your Ticket ID</label>
                                    <input type="text" name="id" id="id" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary mt-3">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>

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
</body>

</html>