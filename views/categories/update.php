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
$stmt = $connect->prepare("SELECT * FROM categories WHERE id = ?");
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
    $_SESSION['response'] = ['msg' => "Category Not Found! ", 'status' => false, 'des' => "There is no category id: $id in database"];
    header("Location: index.php");
    exit();
}
?>
<title>Category - Update</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <h2 class="fs-2 fw-bold">Category update Form</h2>
    <form class="row g-3 my-3" action="../../models/categories/update.php" method="POST">
        <!-- Category ID -->
        <div class="col-md-4 d-none">
            <label for="categoryid" class="form-label fs-5 fw-semibold">Category ID</label>
            <input type="hidden" value="<?php echo htmlspecialchars($data['id']) ?>" name="id" class="form-control"
                id="categoryid" placeholder="Category ID">
        </div>
        <!-- Category Name -->
        <div class="col-md-4">
            <label for="categoryname" class="form-label fs-5 fw-semibold">Category Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']) ?>" class="form-control"
                id="categoryname" placeholder="Category Name" required>
        </div>
        <!-- Category Slug -->
        <div class="col-md-4">
            <label for="categoryslug" class="form-label fs-5 fw-semibold">Category Slug</label>
            <input type="text" name="slug" value="<?php echo htmlspecialchars($data['slug']) ?>" class="form-control"
                id="categoryslug" placeholder="Category Slug" required>
        </div>
        <!-- Category Status -->
        <div class="col-md-4">
            <label for="categorystatus" class="form-label fs-5 fw-semibold">Category Status</label>
            <select name="status" id="categorystatus" class="form-select" required>
                <option value="Active" <?php echo htmlspecialchars($data['status']) == 'Active' ? "selected" : '' ?>>
                    Active</option>
                <option value="Inactive" <?php echo htmlspecialchars($data['status']) == 'Inactive' ? "selected" : '' ?>>
                    Inactive</option>
            </select>
        </div>
        <!-- Category Description -->
        <div class="col-md-12">
            <label for="categorydes" class="form-label fs-5 fw-semibold">Category Description</label>
            <textarea rows="8" name="des" class="form-control" id="categorydes"
                placeholder="Category Description"><?php echo htmlspecialchars($data['description']) ?></textarea>
        </div>
        <!-- Submit Button -->
        <div class="col-md-12">
            <button class="btn-warning btn fw-bold text-white" style="width: fit-content;"><i
                    class="fa-solid fa-pen"></i>
                UPDATE</button>
        </div>
    </form>
</body>

</html>