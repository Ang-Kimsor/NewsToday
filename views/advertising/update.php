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
$stmt = $connect->prepare("SELECT * FROM advertising WHERE id = ?");
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
    $_SESSION['response'] = ['msg' => "Ads Not Found! ", 'status' => false, 'des' => "There is no ads id: $id in database"];
    header("Location: index.php");
    exit();
}
?>
<title>Advertising - Update</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <div>
        <h2 class="fs-2 fw-bold mb-4">Advertising Update Form</h2>
        <form class="row g-4" action="../../models/advertising/update.php" method="POST" enctype="multipart/form-data">
            <!-- Ads ID -->
            <div class="col-md-4 d-none">
                <label for="adsid" class="form-label fs-5 fw-semibold">Advertising ID</label>
                <input type="hidden" name="id" class="form-control" id="adsid" value="<?php echo $data['id'] ?>"
                    placeholder="Advertising ID">
            </div>
            <!-- Advertising Name -->
            <div class="col-md-6">
                <label for="adsname" class="form-label fs-5 fw-semibold">Advertising Name</label>
                <input type="text" name="name" class="form-control" id="adsname" placeholder="Advertising Name"
                    value="<?php echo $data['name'] ?>" required>
            </div>
            <!-- Advertising Status -->
            <div class="col-md-6">
                <label for="adsstatus" class="form-label fs-5 fw-semibold">Advertising Status</label>
                <select name="status" id="adsstatus" class="form-select" required>
                    <option value="Active" <?php echo $data['status'] == "Active" ? "selected" : '' ?>>
                        Active</option>
                    <option value="Inactive" <?php echo $data['status'] == "Inactive" ? "selected" : '' ?>>Inactive
                    </option>
                </select>
            </div>
            <!-- Advertising Image Upload -->
            <div class="col-md-6 position-relative">
                <label for="adsimage" class="form-label fs-5 fw-semibold">Advertising Image</label>
                <input type="file" class="form-control" name="image" id="adsimage" accept="image/*">
                <!-- Image Preview -->
                <div class="mt-2">
                    <img id="profilePreview" src="../../upload/advertising/<?php echo $data['image'] ?>" alt="Image"
                        class="rounded shadow-sm" style="width: 100%; height: 185px; object-fit: contain;">
                </div>
            </div>
            <!-- Advertising Description -->
            <div class="col-md-6">
                <label for="adsdes" class="form-label fs-5 fw-semibold">Advertising Description</label>
                <textarea rows="9" style="resize: none;" name="des" class="form-control" id="adsdes"
                    placeholder="Advertising Description"><?php echo $data['description'] ?></textarea>
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
        const inputFile = document.getElementById('adsimage');
        const preview = document.getElementById('profilePreview');
        inputFile.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else preview.src = '../../asset/images/image.jpg';
        });
    </script>
</body>

</html>