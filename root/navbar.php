<?php
session_start();
require 'header.php';
?>
<link rel="stylesheet" href="../asset/style/navbar.css">
</head>

<body>
    <nav class="navbar">
        <!-- Brand -->
        <a class="navbar-brand" href="#">News Dashboard</a>
        <!-- Actions -->
        <div class="navbar-actions">
            <!-- User profile -->
            <div class="user-profile">
                <img src="../upload/admins/<?php echo htmlspecialchars($_SESSION['admin']['profile']) ?>" alt="Admin"
                    style="object-fit: contain;">
                <span><?php echo htmlspecialchars($_SESSION['admin']['name']) ?></span>
                <a href="../views/login/confirmLogout.php"
                    class="nav-link d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;"
                    target="content">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>
</body>

</html>