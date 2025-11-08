<?php
require_once '../connection/config.php';
require_once '../root/header.php';
?>
<link rel="stylesheet" href="../asset/style/dashboard.css">
</head>

<body class="bg-light">
    <div class="container-fluid p-4">
        <!-- Summary Cards Row -->
        <?php
        $stmt = "SELECT
            COUNT(*) AS TotalNews,
            SUM(status='Published') AS TotalPublishedNews,
            SUM(status='Draft') AS TotalDraftNews,
            SUM(status='Pending') AS TotalPendingNews
        FROM news
        ";
        $result = $connect->query($stmt);
        $datanews = $result->fetch_assoc();
        ?>
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="summary-card p-4 bg-info-soft rounded-4 text-center shadow-sm h-100">
                    <i class="fas fa-newspaper text-info fa-2x mb-2"></i>
                    <h6>Total News</h6>
                    <h3><?php echo $datanews['TotalNews'] ?></h3>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="summary-card p-4 bg-success-soft rounded-4 text-center shadow-sm h-100">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <h6>Published</h6>
                    <h3><?php echo $datanews['TotalPublishedNews'] ?></h3>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="summary-card p-4 bg-warning-soft rounded-4 text-center shadow-sm h-100">
                    <i class="fas fa-pencil-alt text-warning fa-2x mb-2"></i>
                    <h6>Drafts</h6>
                    <h3><?php echo $datanews['TotalDraftNews'] ?></h3>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="summary-card p-4 bg-danger-soft rounded-4 text-center shadow-sm h-100">
                    <i class="fas fa-clock text-danger fa-2x mb-2"></i>
                    <h6>Pending</h6>
                    <h3><?php echo $datanews['TotalPendingNews'] ?></h3>
                </div>
            </div>
        </div>

        <!-- Secondary Metrics Row -->
        <?php
        $stmt = "SELECT 
            (SELECT COUNT(*) FROM admins) AS TotalAdmins,
            (SELECT COUNT(*) FROM categories) AS TotalCategories
        ";
        $result = $connect->query($stmt);
        $data = $result->fetch_assoc();
        ?>
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card bg-success-soft shadow-sm rounded-4 dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Total Admins</h6>
                            <h3><?php echo $data['TotalAdmins'] ?></h3>
                            <small>Currently online</small>
                        </div>
                        <i class="fas fa-user-tie fa-2x text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card bg-warning-soft shadow-sm rounded-4 dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Total Categories</h6>
                            <h3><?php echo $data['TotalCategories'] ?></h3>
                            <small>Content Sections</small>
                        </div>
                        <i class="fas fa-folder fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white rounded-4 border-0">
                        <h4 class="fw-bold py-3 mb-0"><i class="fas fa-folder me-2  text-warning"></i> Top 5 Categories
                        </h4>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart" height="143"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white rounded-4 border-0">
                        <h4 class="fw-bold py-3  me-0"><i class="fas fa-chart-pie me-2 text-info"></i> News Status
                        </h4>
                    </div>
                    <div class="card-body">
                        <canvas id="newsPieChart" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Dashboard Sections -->
        <div class="row g-3 mt-4">
            <!-- Latest News / Recent Posts -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white rounded-4 border-0">
                        <h4 class="fw-bold my-3"><i class="fas fa-newspaper me-2 text-primary"></i> Latest News</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php
                            $stmt = "SELECT title, status, created_at FROM news ORDER BY created_at DESC LIMIT 5";
                            $result = $connect->query($stmt);
                            while ($data = $result->fetch_assoc()) {
                                $status = $data['status'] == "Published" ? "success" : ($data['status'] == "Draft" ? "warning" : "danger");
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $data['title'] ?>
                                    <span
                                        class="badge bg-<?php echo $status ?> rounded-pill"><?php echo $data['status'] ?></span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Top Admin -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white rounded-4 border-0">
                        <h4 class="fw-bold my-3"><i class="fas fa-users me-2 text-info"></i> Most Active Admin</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php
                            $stmt = "SELECT a.name, COUNT(n.id) AS TotalPost FROM admins a LEFT JOIN news n ON a.id = n.author_id GROUP BY a.id, a.name ORDER BY TotalPost DESC LIMIT 5";
                            $result = $connect->query($stmt);
                            while ($data = $result->fetch_assoc()) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $data['name'] ?>
                                    <span class="badge bg-primary rounded-pill"><?php echo $data['TotalPost'] ?>
                                        Posts</span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once '../root/footer.php'; ?>
    <script>

        const ctxPie = document.getElementById('newsPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Published', 'Draft', 'Pending'],
                datasets: [{
                    data: [<?php echo $datanews['TotalPublishedNews'] ?>, <?php echo $datanews['TotalDraftNews'] ?>, <?php echo $datanews['TotalPendingNews'] ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });


        <?php
        $stmt = "SELECT c.name AS CategoryName, COUNT(n.id) as TotalNewsInEachCategory FROM categories c LEFT JOIN news n ON c.id = n.category_id WHERE c.status = 'Active' GROUP BY c.id, c.name ORDER BY TotalNewsInEachCategory DESC LIMIT 5";
        $result = $connect->query($stmt);
        $categoryLabel = [];
        $newsCount = [];
        while ($data = $result->fetch_assoc()) {
            $categoryLabel[] = $data['CategoryName'];
            $newsCount[] = $data['TotalNewsInEachCategory'];
        }
        ?>
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categoryLabel) ?>,
                datasets: [{
                    label: 'Total News',
                    data: <?php echo json_encode($newsCount) ?>,
                    backgroundColor: 'rgba(54, 235, 69, 0.6)',
                    borderColor: 'rgb(54, 235, 69)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>