<?php
require_once '../../root/header.php';
?>
<title>Category - Insert</title>
</head>

<body class="p-4 bg-light">

    <a href="index.php" class="btn btn-danger mb-3 fw-semibold">
        <i class="fa-solid fa-door-open me-2"></i>BACK
    </a>
    <h2 class="fs-2 fw-bold">Category Insert Form</h2>
    <form class="row g-3 my-3" action="../../models/categories/insert.php" method="POST">
        <!-- Category ID -->
        <div class="col-md-4">
            <label for="categoryid" class="form-label fs-5 fw-semibold">Category ID</label>
            <input type="number" name="id" class="form-control" id="categoryid" placeholder="Category ID">
        </div>
        <!-- Category Name -->
        <div class="col-md-4">
            <label for="categoryname" class="form-label fs-5 fw-semibold">Category Name</label>
            <input type="text" name="name" class="form-control" id="categoryname" placeholder="Category Name" required>
        </div>
        <!-- Category Slug -->
        <div class="col-md-4">
            <label for="categoryslug" class="form-label fs-5 fw-semibold">Category Slug</label>
            <input type="text" name="slug" class="form-control" id="categoryslug" placeholder="Category Slug" required>
        </div>
        <!-- Category Status -->
        <div class="col-md-4">
            <label for="categorystatus" class="form-label fs-5 fw-semibold">Category Status</label>
            <select name="status" id="categorystatus" class="form-select" required>
                <option value="Active">Active</option>
                <option value="Inactive" selected>Inactive</option>
            </select>
        </div>
        <!-- Category Description -->
        <div class="col-md-12">
            <label for="categorydes" class="form-label fs-5 fw-semibold">Category Description</label>
            <textarea rows="8" name="des" class="form-control" id="categorydes"
                placeholder="Category Description"></textarea>
        </div>
        <!-- Insert Button -->
        <div class="col-md-12">
            <button class="btn-success btn fw-bold" style="width: fit-content;"><i class="fa-solid fa-plus"></i>
                INSERT</button>
        </div>
    </form>
</body>

</html>