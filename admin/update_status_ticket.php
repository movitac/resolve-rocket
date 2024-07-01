<?php
require_once('../support/int.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticketId']) && isset($_POST['status_ticket'])) {
    $ticketId = $_POST['ticketId'];
    $status_ticket = $_POST['status_ticket'];

    $db = new DB();
    $sql = "UPDATE tickets SET status_ticket='$status_ticket',admin_id='" . $_SESSION['id'] . "' WHERE id='$ticketId'";
    $result = $db->conn->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Status ticket updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status ticket']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
