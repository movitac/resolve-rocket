<!DOCTYPE html>
<html lang="en">


<?php
require_once('head.php');
require_once('./support/int.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'admin/vendor/PHPMailer/src/Exception.php';
require 'admin/vendor/PHPMailer/src/PHPMailer.php';
require 'admin/vendor/PHPMailer/src/SMTP.php';

$success = false;
$error = false;
require_once('./support/int.php');
if (isset($_POST['submit'])) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $department = $_POST['department'];
    $problem_type = $_POST['problem_type'];
    $other_problem_type = $_POST['other_problem_type'];

    // Map problem type to its short form
    $problem_type_map = [
        'Hardware' => 'HW',
        'Software' => 'SW',
        'Troubleshooting' => 'TS',
        'Others'  => 'O'

    ];
    $problem_type_short = $problem_type_map[$problem_type];

    //Generate random 4-digit number
    $random_number = mt_rand(1000, 9999);

    //Create ticket ID
    $ticket_id = '#' . $problem_type_short . $random_number;
    $db = new DB();

    $sql = "INSERT INTO tickets (ticket_id, status, userid, subject, message,department,problem_type,other_problem_type) 
        VALUES ('$ticket_id', '0', '" . $_SESSION['id'] . "', '$subject', '$message', '$department', '$problem_type', '$other_problem_type')";

    $inset = $db->conn->query($sql);
    if ($inset) {

        $subject = "New Ticket";
        $message = "New ticket id: " . $ticket_id . " has been created.";

        // Load composer's autoloader
        require 'admin/vendor/autoload.php';

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
            $mail->addAddress('resolverocket@gmail.com');
            $mail->addReplyTo('resolverocket@gmail.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            // $_SESSION['result'] = 'Message has been sent';
            // $_SESSION['status'] = 'ok';
            $success = 'Your ticket has been created. Your ticket id is ' . $ticket_id;
        } catch (Exception $e) {
            // $_SESSION['result'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            // $_SESSION['status'] = 'error';
            $error = "Can not send reply " . $mail->ErrorInfo;
        }
    } else {
        $error = 'Ticket not sent to our team, please try again later';
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
                    <h1 class="mt-4 mb-4">Create New Tickets</h1>
                    <hr>


                    <form method="POST">
                        <div class="card">
                            <div class="card-header"> Create New Support Ticket</div>
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
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Your name <span class="text-danger">*</span> </label>
                                            <input type="text" disabled name="name" id="name" value="<?php echo $name ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Your E-Mail Address <span class="text-danger">*</span></label>
                                            <input type="email" disabled name="email" id="email" value="<?php echo $email ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Subject <span class="text-danger">*</span></label>
                                            <input type="text" required name="subject" id="subject" placeholder="Subject About Support" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Department <span class="text-danger">*</span></label>
                                            <select name="department" id="department" class="form-control" required>
                                                <option selected disabled value="">Select Department</option>
                                                <option value="Customer Support">Customer Support</option>
                                                <option value="Technical Support">Technical Support</option>
                                                <option value="Billing">Billing</option>
                                                <option value="Sales">Sales</option>
                                                <option value="Human Resources">Human Resources</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Problem Type <span class="text-danger">*</span></label>
                                            <select name="problem_type" id="problem_type" class="form-control" required onchange="toggleOtherProblemType()">
                                                <option selected disabled value="">Select Problem Type</option>
                                                <option value="Hardware">Hardware</option>
                                                <option value="Software">Software</option>
                                                <option value="Troubleshooting">Troubleshooting</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3" id="other_problem_type_div" style="display: none;">
                                        <div class="form-group">
                                            <label>Specify Other Problem Type <span class="text-danger">*</span></label>
                                            <input type="text" name="other_problem_type" id="other_problem_type" class="form-control">
                                        </div>
                                    </div>
                                    <!-- <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>You Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" required name="mobile" id="mobile" placeholder="Your Mobile Number" class="form-control">
                                </div>
                            </div> -->
                                    <div class="col-12 mb-3">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" id="message" cols="30" rows="5" placeholder="Enter your description about help"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="text-right">
                                            <input type="hidden" name="submit" value="form">
                                            <button class="btn btn-primary btn-md" type="submit">Submit</button>
                                        </div>
                                    </div>
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


        function toggleOtherProblemType() {
            var problemType = document.getElementById('problem_type').value;
            var otherProblemTypeDiv = document.getElementById('other_problem_type_div');

            if (problemType === 'Others') {
                otherProblemTypeDiv.style.display = 'block';
            } else {
                otherProblemTypeDiv.style.display = 'none';
            }
        }
    </script>
</body>

</html>