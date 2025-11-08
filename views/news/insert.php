<?php
require_once '../../root/header.php';
require_once '../../connection/config.php';
?>
<title>News - Insert</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <div>
        <h2 class="fs-2 fw-bold mb-4">News Insert Form</h2>
        <form class="row g-4" action="../../models/news/insert.php" method="POST" enctype="multipart/form-data">
            <!-- News ID -->
            <div class="col-md-4">
                <label for="newsid" class="form-label fs-5 fw-semibold">News ID</label>
                <input type="number" name="id" class="form-control" id="newsid" placeholder="News ID">
            </div>
            <!-- News Title -->
            <div class="col-md-4">
                <label for="newstitle" class="form-label fs-5 fw-semibold">News Title</label>
                <input type="text" name="title" class="form-control" id="newstitle" placeholder="News Title" required>
            </div>
            <!-- News Slug -->
            <div class="col-md-4">
                <label for="newsslug" class="form-label fs-5 fw-semibold">News Slug</label>
                <input type="text" name="slug" class="form-control" id="newsslug" placeholder="News Slug" required>
            </div>
            <!-- News Category -->
            <div class="col-md-6">
                <label for="newscategory" class="form-label fs-5 fw-semibold">News Category</label>
                <select name="category" id="newscategory" class="form-select" required>
                    <?php
                    $stmt = "SELECT id, name FROM categories WHERE status = 'Active'";
                    $result = $connect->query($stmt);
                    while ($data = $result->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <!-- News Status -->
            <div class="col-md-6">
                <label for="newsstatus" class="form-label fs-5 fw-semibold">News Status</label>
                <select name="status" id="newsstatus" class="form-select" required>
                    <option value="Published">Published</option>
                    <option value="Draft" selected>Draft</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <!-- News Image Upload -->
            <div class="col-md-4 position-relative">
                <label for="newsimage" class="form-label fs-5 fw-semibold">News Image</label>
                <input type="file" class="form-control" name="image" id="newsimage" accept="image/*" required>
                <!-- Image Preview -->
                <div class="mt-2">
                    <img id="profilePreview" src="../../asset/images/image.jpg" alt="Image" class="rounded shadow-sm"
                        style="width: 100%; height: 185px; object-fit: contain;">
                </div>
            </div>
            <!-- News Description -->
            <div class="col-md-8">
                <label for="newsdes" class="form-label fs-5 fw-semibold">News Description</label>
                <textarea rows="9" style="resize: none;" name="des" class="form-control" id="newsdes"
                    placeholder="News Description" required></textarea>
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