<?php
session_start();
require_once '../../root/header.php';
require_once '../../connection/config.php';
$stmt = "SELECT * FROM advertising";
$result = $connect->query($stmt);
?>
<title>Advertising - Index</title>
</head>

<body class="p-4 bg-light">
    <h2 class="d-flex align-items-center fw-bold justify-content-between">
        Advertising Table
        <?php
        if ($_SESSION['admin']['role'] != 'Viewer') { ?>
            <div>
                <a href="insert.php" class="btn btn-success my-3 fw-semibold">
                    <i class="fa-solid fa-plus me-2"></i>INSERT
                </a>
            </div>
            <?php
        }
        ?>
    </h2>
    <?php
    if (isset($_SESSION['response'])) {
        $alert = $_SESSION['response']['status'] ? "success" : "danger";
        ?>
        <div class="alert alert-<?php echo $alert ?>" role="alert">
            <span class="fw-bold"><?php echo $_SESSION['response']['msg'] ?></span>
            <?php echo $_SESSION['response']['des'] ?>
        </div>
        <?php
    }
    ?>
    <table id="example" class="table table-striped mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Image</th>
                <th>Created</th>
                <th>Updated</th>
                <?php
                if ($_SESSION['admin']['role'] != 'Viewer') { ?>
                    <th>Action</th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($data = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['id']) ?></td>
                    <td><?php echo htmlspecialchars($data['name']) ?></td>
                    <td><?php echo htmlspecialchars($data['description']) ?></td>
                    <td><?php echo htmlspecialchars($data['status']) ?></td>
                    <td>
                        <img src="<?php echo "../../upload/advertising/" . htmlspecialchars($data['image']) ?>"
                            alt="Profile" style="width: 80px;height:80px;object-fit:contain;">
                    </td>
                    <td><?php echo date('H:i:s d-M-Y', strtotime($data['created_at'])) ?></td>
                    <td><?php echo date('H:i:s d-M-Y', strtotime($data['updated_at'])) ?></td>
                    <?php
                    if ($_SESSION['admin']['role'] != 'Viewer') {
                        ?>
                        <td class="text-center">
                            <div class="d-inline-flex gap-2">
                                <a href="update.php?id=<?php echo $data['id'] ?>"
                                    class="btn btn-primary d-flex align-items-center justify-content-center"
                                    style="width:40px; height:40px;"><i class="fa-solid fa-pen"></i></a>
                                <button
                                    onclick='confirmDelete(<?php echo $data["id"] ?>, "<?php echo addslashes($data["name"]) ?>")'
                                    class="btn btn-danger d-flex align-items-center justify-content-center"
                                    style="width:40px; height:40px;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php require_once '../../root/footer.php'; ?>
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Are you sure to delete ads name: ${name}?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: "success",
                        title: `Deleting ads name: ${name}`,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '../../models/advertising/delete.php?id=' + id;
                    });
                }
            });
        }
    </script>
</body>

</html>
<?php
unset($_SESSION['response']);
$stmt = null;
?>