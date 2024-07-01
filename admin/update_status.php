<?php
require_once('../support/int.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId']) && isset($_POST['status'])) {
    $userId = $_POST['userId'];
    $status = $_POST['status'];

    $db = new DB();
    $sql = "UPDATE users SET status_user='$status' WHERE id='$userId'";
    $result = $db->conn->query($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
