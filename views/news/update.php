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
$stmt = $connect->prepare("SELECT * FROM news WHERE id = ?");
if (!$stmt) {
    $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
    header("Location: index.php");
    exit();
}
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) {
    $_SESSION['response'] = ['msg' => "News Not Found! ", 'status' => false, 'des' => "There is no news id: $id in database"];
    header("Location: index.php");
    exit();

}
?>
<title>News - Update</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <div>
        <h2 class="fs-2 fw-bold mb-4">News Update Form</h2>
        <form class="row g-4" action="../../models/news/update.php" method="POST" enctype="multipart/form-data">
            <!-- News ID -->
            <div class="col-md-4 d-none">
                <label for="newsid" class="form-label fs-5 fw-semibold">News ID</label>
                <input type="hidden" name="id" class="form-control" id="newsid" value="<?php echo $data['id'] ?>"
                    placeholder="News ID">
            </div>
            <!-- News Title -->
            <div class="col-md-6">
                <label for="newstitle" class="form-label fs-5 fw-semibold">News Title</label>
                <input type="text" name="title" class="form-control" id="newstitle" value="<?php echo $data['title'] ?>"
                    placeholder="News Title" required>
            </div>
            <!-- News Slug -->
            <div class="col-md-6">
                <label for="newsslug" class="form-label fs-5 fw-semibold">News Slug</label>
                <input type="text" name="slug" class="form-control" id="newsslug" value="<?php echo $data['slug'] ?>"
                    placeholder="News Slug" required>
            </div>
            <!-- News Category -->
            <div class="col-md-6">
                <label for="newscategory" class="form-label fs-5 fw-semibold">News Category</label>
                <select name="category" id="newscategory" class="form-select" required>
                    <?php
                    $stmt = "SELECT id, name FROM categories WHERE status = 'Active'";
                    $result = $connect->query($stmt);
                    while ($dataCate = $result->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $dataCate['id'] ?>" <?php echo $dataCate['id'] == $data['category_id'] ? "selected" : '' ?>><?php echo $dataCate['name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <!-- News Status -->
            <div class="col-md-6">
                <label for="newsstatus" class="form-label fs-5 fw-semibold">News Status</label>
                <select name="status" id="newsstatus" class="form-select" required>
                    <option value="Published" <?php echo $data['status'] == "Published" ? "selected" : '' ?>>Published
                    </option>
                    <option value="Draft" <?php echo $data['status'] == "Draft" ? "selected" : '' ?>>Draft</option>
                    <option value="Pending" <?php echo $data['status'] == "Pending" ? "selected" : '' ?>>Pending</option>
                </select>
            </div>
            <!-- News Image Upload -->
            <div class="col-md-4 position-relative">
                <label for="newsimage" class="form-label fs-5 fw-semibold">News Image</label>
                <input type="file" class="form-control" name="image" id="newsimage" accept="image/*">
                <!-- Image Preview -->
                <div class="mt-2">
                    <img id="profilePreview" src="../../upload/news/<?php echo $data['image'] ?>" alt="Image"
                        class="rounded shadow-sm" style="width: 100%; height: 185px; object-fit: contain;">
                </div>
            </div>
            <!-- News Description -->
            <div class="col-md-8">
                <label for="newsdes" class="form-label fs-5 fw-semibold">News Description</label>
                <textarea rows="9" style="resize: none;" name="des" class="form-control" id="newsdes"
                    placeholder="News Description" required><?php echo $data['description'] ?></textarea>
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
        const inputFile = document.getElementById('newsimage');
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