<?php
require_once('../support/int.php');

if (isset($_GET['archiveid']) && is_numeric($_GET['archiveid'])) {
    $ticketId = $_GET['archiveid'];

    try {
        $db = new DB();
        $stmt = $db->conn->prepare("UPDATE tickets SET archive='yes' WHERE id=?");
        $stmt->bind_param('i', $ticketId);

        if ($stmt->execute()) {
            $stmt->close();
            $db->conn->close();
            header('Location: admin-dashboard.php?message=Ticket+archive+successfully');
            exit();
        } else {
            $stmt->close();
            $db->conn->close();
            header('Location: admin-dashboard.php?message=Failed+to+archive+ticket');
            exit();
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: admin-dashboard.php?message=Database+error');
        exit();
    }
} else {
    header('Location: admin-dashboard.php?message=Invalid+ticket+ID');
    exit();
}
