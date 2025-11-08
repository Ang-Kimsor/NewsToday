<?php
require_once '../../root/header.php';
?>
<title>Advertising - Insert</title>
</head>

<body class="p-4 bg-light">
    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <div>
        <h2 class="fs-2 fw-bold mb-4">Advertising Insert Form</h2>
        <form class="row g-4" action="../../models/advertising/insert.php" method="POST" enctype="multipart/form-data">
            <!-- Ads ID -->
            <div class="col-md-4">
                <label for="adsid" class="form-label fs-5 fw-semibold">Advertising ID</label>
                <input type="number" name="id" class="form-control" id="adsid" placeholder="Advertising ID">
            </div>
            <!-- Advertising Name -->
            <div class="col-md-4">
                <label for="adsname" class="form-label fs-5 fw-semibold">Advertising Name</label>
                <input type="text" name="name" class="form-control" id="adsname" placeholder="Advertising Name"
                    required>
            </div>
            <!-- Advertising Status -->
            <div class="col-md-4">
                <label for="adsstatus" class="form-label fs-5 fw-semibold">Advertising Status</label>
                <select name="status" id="adsstatus" class="form-select" required>
                    <option value="Active">Active</option>
                    <option value="Inactive" selected>Inactive</option>
                </select>
            </div>
            <!-- Advertising Image Upload -->
            <div class="col-md-4 position-relative">
                <label for="adsimage" class="form-label fs-5 fw-semibold">Advertising Image</label>
                <input type="file" class="form-control" name="image" id="adsimage" accept="image/*">
                <!-- Image Preview -->
                <div class="mt-2">
                    <img id="profilePreview" src="../../asset/images/image.jpg" alt="Image" class="rounded shadow-sm"
                        style="width: 100%; height: 185px; object-fit: contain;">
                </div>
            </div>
            <!-- Advertising Description -->
            <div class="col-md-8">
                <label for="adsdes" class="form-label fs-5 fw-semibold">Advertising Description</label>
                <textarea rows="9" style="resize: none;" name="des" class="form-control" id="adsdes"
                    placeholder="Advertising Description"></textarea>
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