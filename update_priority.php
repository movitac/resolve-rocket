<?php
require_once('./support/int.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticketId']) && isset($_POST['priority'])) {
    $ticketId = $_POST['ticketId'];
    $priority = $_POST['priority'];

    $db = new DB();
    $sql = "UPDATE tickets SET priority='$priority' WHERE id='$ticketId'";
    $result = $db->conn->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Priority updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update priority']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
