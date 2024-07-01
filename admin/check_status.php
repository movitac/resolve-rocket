<?php
// Include your database connection file if needed
require_once 'db_connection.php'; // Update this with the actual filename and path

// Check if the ticketId is set in the POST data
if (isset($_POST['ticketId'])) {
    $ticketId = $_POST['ticketId'];

    // Query to check the status of the ticket
    $query = "SELECT status FROM tickets WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bind_param("i", $ticketId);

    // Execute the statement
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($status);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Return the status as JSON
    echo json_encode(['status' => $status]);
} else {
    // If ticketId is not set, return an error message
    echo json_encode(['error' => 'Ticket ID not provided']);
}
