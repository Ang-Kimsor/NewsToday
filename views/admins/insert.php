<?php
session_start();
require_once '../../root/header.php';
if ($_SESSION['admin']['role'] != 'Superadmin') {
}
?>
<title>Admin - Insert</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <div>
        <h2 class="fs-2 fw-bold mb-4">Admin Insert Form</h2>
        <form class="row g-4" action="../../models/admins/insert.php" method="POST" enctype="multipart/form-data">
            <!-- Admin ID -->
            <div class="col-md-4">
                <label for="adminid" class="form-label fs-5 fw-semibold">Admin ID</label>
                <input type="number" name="id" class="form-control" id="adminid" placeholder="Admin ID">
            </div>
            <!-- Admin Name -->
            <div class="col-md-4">
                <label for="adminname" class="form-label fs-5 fw-semibold">Admin Name</label>
                <input type="text" name="name" class="form-control" id="adminname" placeholder="Admin Name" required>
            </div>
            <!-- Admin Email -->
            <div class="col-md-4">
                <label for="adminemail" class="form-label fs-5 fw-semibold">Admin Email</label>
                <input type="email" name="email" class="form-control" id="adminemail" placeholder="Admin Email"
                    required>
            </div>
            <!-- Admin Password -->
            <div class="col-md-4">
                <label for="adminpassword" class="form-label fs-5 fw-semibold">Admin Password</label>
                <input type="password" name="password" class="form-control" id="adminpassword"
                    placeholder="Admin Password" required>
            </div>
            <!-- Admin Role -->
            <div class="col-md-4">
                <label for="adminrole" class="form-label fs-5 fw-semibold">Admin Role</label>
                <select name="role" id="adminrole" class="form-select" required>
                    <option value="Viewer" selected>Viewer</option>
                    <option value="Editor">Editor</option>
                    <option value="Superadmin">Superadmin</option>
                </select>
            </div>
            <!-- Admin Status -->
            <div class="col-md-4">
                <label for="adminstatus" class="form-label fs-5 fw-semibold">Admin Status</label>
                <select name="status" id="adminstatus" class="form-select" required>
                    <option value="Active">Active</option>
                    <option value="Inactive" selected>Inactive</option>
                </select>
            </div>
            <!-- Admin Profile Upload -->
            <div class="col-md-4 position-relative">
                <label for="adminprofile" class="form-label fs-5 fw-semibold">Admin Profile</label>
                <input type="file" class="form-control" name="profile" id="adminprofile" accept="image/*">
                <!-- Image Preview -->
                <div class="mt-2">
                    <img id="profilePreview" src="../../asset/images/profile.jpg" alt="Profile Image"
                        class="rounded shadow-sm" style="width: 100%; height: 200px; object-fit: contain;">
                </div>
            </div>
            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-success fw-bold">
                    <i class="fa-solid fa-plus me-2"></i>INSERT
                </button>
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