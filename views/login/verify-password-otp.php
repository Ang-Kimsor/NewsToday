<?php
session_start();
if (!isset($_SESSION['pending_admin'])) {
    $_SESSION['response'] = ['msg' => "Admin pending is missing", 'status' => false, 'des' => ''];
    header("Location: index.php");
    exit();
}
require_once '../../root/header.php';
?>
<title>Two-Factor Authentication</title>
<link rel="stylesheet" href="../../asset/style/form.css">
</head>

<body>
    <div class="login-container bg-secondary">
        <div class="login-card bg-white p-4 shadow">
            <h2 class="text-center mb-4 fw-bold text-primary">Enter OTP To Reset Password</h2>
            <p class="text-center text-muted">We sent a 6-digit code to your email. Please enter it below.</p>
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
            <form action="../../models/login/verify-password-otp.php" method="POST">
                <div class="form-outline mb-4">
                    <label class="form-label">OTP Code</label>
                    <input type="text" class="form-control text-center fs-4" name="otp" maxlength="6" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase btn-custom">Verify
                    OTP</button>
            </form>
            <div class="text-center mt-3">
                <a href="index.php">Back to login</a>
            </div>
        </div>
    </div>
</body>

</html>