<?php
session_start();
include("header.php");
?>
<link rel="stylesheet" href="../asset/style/sidemenu.css">
</head>

<body>
    <div class="menu">
        <h1 class="header">News Management</h1>
        <ul class="components">
            <li>
                <a href="../views/dashboard.php" target="content">
                    <i class="fa-solid fa-chart-area me-2"></i> Dashboard
                </a>
            </li>
            <?php
            if ($_SESSION['admin']['role'] == 'Superadmin') { ?>
                <li>
                    <a href="../views/admins/index.php" target="content"><i class="fa-solid fa-user-tie me-2"></i>
                        Admins</a>
                </li>
                <?php
            }
            ?>
            <li>
                <a href="../views/advertising/index.php" target="content"><i class="fa-solid fa-bullhorn me-2"></i>
                    Advertising</a>
            </li>
            <li>
                <a href="../views/categories/index.php" target="content"><i class="fa-solid fa-folder me-2"></i>
                    Categories</a>
            </li>
            <li>
                <a href="../views/news/index.php" target="content"><i class="fa-solid fa-scroll me-2"></i>
                    News</a>
            </li>
            <li>
                <a href="../views/login/confirmLogout.php" target="content">
                    <i class="fa-solid fa-door-open me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    <?php include("footer.php"); ?>
</body>

</html>