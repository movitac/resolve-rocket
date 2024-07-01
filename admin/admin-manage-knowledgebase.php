<?php
require_once('head.php');
require_once('../support/int.php');

$success = false;
$error = false;

$db = new DB();

// Create or update knowledgebase entry
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $knowledge = $_POST['knowledge'];
    $problem_type = $_POST['problem_type'];

    // File upload handling
    $file_name = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        move_uploaded_file($file_tmp, "uploads/" . $file_name); // Move uploaded file to the desired location
    }

    // Check if it's an edit or a new entry
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];
        if ($file_name) {
            $sql = "UPDATE knowledgebase SET title=?, knowledge=?, problem_type=?, photo=? WHERE id=?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("ssssi", $title, $knowledge, $problem_type, $file_name, $editId);
        } else {
            $sql = "UPDATE knowledgebase SET title=?, knowledge=?, problem_type=? WHERE id=?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $knowledge, $problem_type, $editId);
        }
        $action = 'updated';
    } else {
        $sql = "INSERT INTO knowledgebase (title, knowledge, photo, problem_type) VALUES (?, ?, ?, ?)";
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $knowledge, $file_name, $problem_type);
        $action = 'created';
    }

    if ($stmt->execute()) {
        $success = 'Knowledgebase entry has been ' . $action . '.';
    } else {
        $error = 'Failed to ' . $action . ' knowledgebase entry, please try again later.';
    }

    $stmt->close();
}

// Delete knowledgebase entry
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
    $sql = "DELETE FROM knowledgebase WHERE id=?";
    $stmt = $db->conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = 'Knowledgebase entry has been deleted.';
    } else {
        $error = 'Failed to delete knowledgebase entry, please try again later.';
    }

    $stmt->close();
}

$latest = [];
$recodes = $db->conn->query("SELECT * FROM knowledgebase");
if ($recodes->num_rows > 0) {
    while ($row = $recodes->fetch_assoc()) {
        $latest[] = $row;
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
                    <h1 class="mt-4 mb-4">Manage Knowledge base</h1>
                    <hr>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="card mb-2">
                            <div class="card-header"> Create/Edit Knowledge base</div>
                            <div class="card-body">
                                <?php if (isset($error) && $error !== false) {
                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                } ?>
                                <?php if (isset($success) && $success !== false) {
                                    echo '<div class="alert alert-success">' . $success . '</div>';
                                } ?>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" required name="title" id="title" placeholder="Title" class="form-control" value="<?php echo isset($_POST['edit']) ? $_POST['title'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label>Upload Photo <span class="text-danger">*</span></label>
                                            <input type="file" required name="photo" id="photo" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mt-3">
                                        <div class="form-group">
                                            <textarea name="knowledge" class="form-control" id="knowledge" cols="30" rows="5" placeholder="Enter description about knowledgebase"><?php echo isset($_POST['edit']) ? $_POST['knowledge'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Problem Type <span class="text-danger">*</span></label>
                                            <select name="problem_type" id="problem_type" class="form-control" required>
                                                <option disabled selected value="">Select Problem Type</option>
                                                <option value="Hardware" <?php echo (isset($_POST['problem_type']) && $_POST['problem_type'] == 'Hardware') ? 'selected' : ''; ?>>Hardware</option>
                                                <option value="Software" <?php echo (isset($_POST['problem_type']) && $_POST['problem_type'] == 'Software') ? 'selected' : ''; ?>>Software</option>
                                                <option value="Troubleshooting" <?php echo (isset($_POST['problem_type']) && $_POST['problem_type'] == 'Troubleshooting') ? 'selected' : ''; ?>>Troubleshooting</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-12 mt-3">
                                        <div class="text-right">
                                            <input type="hidden" name="submit" value="form">
                                            <?php if (isset($_POST['edit'])) : ?>
                                                <input type="hidden" name="edit_id" value="<?php echo $_POST['edit_id']; ?>">
                                                <button class="btn btn-warning" type="submit">Update</button>
                                                <button class="btn btn-secondary" type="button" onclick="cancelEdit()" id="cancelBtn">Cancel</button>
                                            <?php else : ?>
                                                <button class="btn btn-primary" type="submit">Create</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="card">
                        <div class="card-header">
                            List of Knowledge base
                        </div>
                        <div class="card-body">
                            <?php if (count($latest) > 0) : ?>
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Problem Type</th>
                                            <th>Description</th>
                                            <th>Photo</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latest as $k => $v) : ?>
                                            <tr>
                                                <td><?php echo $v['id']; ?></td>
                                                <td><?php echo $v['title']; ?></td>

                                                <td><?php echo $v['problem_type']; ?></td>
                                                <td><?php echo $v['knowledge']; ?></td>
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
                                                                    <img src="uploads/<?php echo $v['photo']; ?>" class="img-fluid" alt="Photo">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form method="post" style="display: inline;">
                                                        <input type="hidden" name="edit" value="true">
                                                        <input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>">
                                                        <input type="hidden" name="title" value="<?php echo $v['title']; ?>">
                                                        <input type="hidden" name="problem_type" value="<?php echo $v['problem_type']; ?>">
                                                        <input type="hidden" name="knowledge" value="<?php echo $v['knowledge']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-warning">Edit</button>
                                                    </form>
                                                    <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this knowledgebase?');">
                                                        <input type="hidden" name="delete_id" value="<?php echo $v['id']; ?>">
                                                        <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-info">No any knowledgebase</div>
                            <?php endif; ?>
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


    <script src="../support/assets/js/jquery-3.6.0.min.js"></script>
    <script src="../support/assets/js/popper.min.js"></script>
    <script src="../support/assets/js/bootstrap.min.js"></script>



    <script>
        function cancelEdit() {
            <?php if (isset($_POST['edit'])) : ?>
                document.querySelector('[name="edit"]').value = '';
                document.querySelector('[name="edit_id"]').value = '';
                document.querySelector('[type="submit"]').textContent = 'Create';
                document.querySelector('[type="submit"]').classList.remove('btn-warning');
                document.querySelector('[type="submit"]').classList.add('btn-primary');
                document.getElementById('cancelBtn').style.display = 'none';

                // Clear form fields
                document.getElementById('title').value = '';
                document.getElementById('knowledge').value = '';
                document.getElementById('problem_type').value = '';
            <?php endif; ?>
        }
    </script>
</body>


</html>