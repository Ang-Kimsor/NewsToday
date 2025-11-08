<?php
session_start();
require_once '../../root/header.php';
require_once '../../connection/config.php';
$id = intval($_GET['id']);
if ($id <= 0) {
    $_SESSION['response'] = ['msg' => 'Invalid ID From URL', 'status' => false, 'des' => "Recieve ID: " . $_GET['id'] . "."];
    header("Location: index.php");
    exit();
}
$stmt = $connect->prepare("SELECT * FROM admins WHERE id = ?");
if (!$stmt) {
    $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
    header("Location: index.php");
    exit();
}
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$data) {
    $_SESSION['response'] = ['msg' => "Admin Not Found! ", 'status' => false, 'des' => "There is no admin id: $id in database"];
    header("Location: index.php");
    exit();
}
?>
<title>Admin - Update</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <div>
        <h2 class="fs-2 fw-bold mb-4">Admin Update Form</h2>
        <form class="row g-4" action="../../models/admins/update.php" method="POST" enctype="multipart/form-data">
            <!-- Admin ID -->
            <div class="col-md-4 d-none">
                <label for="adminid" class="form-label fs-5 fw-semibold">Admin ID</label>
                <input type="hidden" name="id" class="form-control" id="adminid" value="<?php echo $data['id'] ?>"
                    placeholder="Admin ID">
            </div>
            <!-- Admin Name -->
            <div class="col-md-4">
                <label for="adminname" class="form-label fs-5 fw-semibold">Admin Name</label>
                <input type="text" name="name" class="form-control" id="adminname"
                    value="<?php echo htmlspecialchars($data['name']) ?>" placeholder="Admin Name" required>
            </div>
            <!-- Admin Email -->
            <div class="col-md-4">
                <label for="adminemail" class="form-label fs-5 fw-semibold">Admin Email</label>
                <input type="email" name="email" class="form-control" id="adminemail"
                    value="<?php echo htmlspecialchars($data['email']) ?>" placeholder="Admin Email" required>
            </div>
            <!-- Admin Password -->
            <div class="col-md-4">
                <label for="adminpassword" class="form-label fs-5 fw-semibold">Admin Password</label>
                <input type="password" name="password" class="form-control" id="adminpassword"
                    placeholder="Admin Password">
            </div>
            <!-- Admin Role -->
            <div class="col-md-4">
                <label for="adminrole" class="form-label fs-5 fw-semibold">Admin Role</label>
                <select name="role" id="adminrole" class="form-select" required>
                    <option value="Viewer" <?php echo $data['role'] == "Viewer" ? "selected" : '' ?>>
                        Viewer</option>
                    <option value="Editor" <?php echo $data['role'] == "Editor" ? "selected" : '' ?>>
                        Editor</option>
                    <option value="Superadmin" <?php echo $data['role'] == "Superadmin" ? "selected" : '' ?>>Superadmin
                    </option>
                </select>
            </div>
            <!-- Admin Status -->
            <div class="col-md-4">
                <label for="adminstatus" class="form-label fs-5 fw-semibold">Admin Status</label>
                <select name="status" id="adminstatus" class="form-select" required>
                    <option value="Active" <?php echo $data['status'] == "Active" ? "selected" : '' ?>>
                        Active</option>
                    <option value="Inactive" <?php echo $data['status'] == "Inactive" ? "selected" : '' ?>>Inactive
                    </option>
                </select>
            </div>
            <div class="col-md-4"></div>
            <!-- Admin Profile Upload -->
            <div class="col-md-4 position-relative">
                <label for="adminprofile" class="form-label fs-5 fw-semibold">Admin Profile</label>
                <input type="file" class="form-control" name="profile" id="adminprofile" accept="image/*">
                <!-- Image Preview -->
                <div class="mt-2">
                    <img id="profilePreview" src="../../upload/admins/<?php echo htmlspecialchars($data['profile']) ?>"
                        alt="Profile Image" class="rounded shadow-sm"
                        style="width: 100%; height: 200px; object-fit: contain;">
                </div>
            </div>
            <!-- Submit Button -->
            <div class="col-md-12">
                <button class="btn-warning btn fw-bold text-white" style="width: fit-content;"><i
                        class="fa-solid fa-pen"></i>
                    UPDATE</button>
            </div>
        </form>
    </div>
    <script>
        // Image preview on file select
        const inputFile = document.getElementById('adminprofile');
        const preview = document.getElementById('profilePreview');
        inputFile.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else preview.src = '../../asset/images/profile.jpg';
        });
    </script>
</body>

</html>