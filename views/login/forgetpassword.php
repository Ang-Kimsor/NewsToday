<?php
session_start();
require_once '../../root/header.php';
?>
<title>Forgot Password</title>
<link rel="stylesheet" href="../../asset/style/form.css">
</head>

<body>
    <div class="login-container bg-secondary">
        <div class="login-card bg-white p-4 shadow">
            <h2 class="text-center mb-4 fw-bold text-primary">Forgot Password</h2>
            <p class="text-center text-muted">Enter your email and we sent a 6-digit code to your email.</p>
            <?php
            if (isset($_SESSION['response'])) {
                $status = $_SESSION['response']['status'] ? "success" : "danger";
                ?>
                <div class="alert alert-<?php echo $status ?>">
                    <span class="fw-bold"><?php echo $_SESSION['response']['msg'] ?></span>
                    <?php echo $_SESSION['response']['des'] ?>
                </div>
                <?php
                unset($_SESSION['response']);
            }
            ?>
            <form action="../../models/login/forgetpassword.php" method="POST">
                <div class="form-outline mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase btn-custom">Send OTP</button>
            </form>
            <div class="text-center mt-3">
                <a href="index.php">Back to login</a>
            </div>
        </div>
    </div>
</body>

</html>