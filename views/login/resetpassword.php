<?php
session_start();
if (!isset($_SESSION['pending_admin'])) {
    $_SESSION['response'] = ['msg' => "Admin pending is missing", 'status' => false, 'des' => ''];
    header("Location: index.php");
    exit();
}
require_once '../../root/header.php';
?>
<title>Forgot Password</title>
<link rel="stylesheet" href="../../asset/style/form.css">
</head>

<body>
    <div class="login-container bg-secondary">
        <div class="login-card bg-white p-4 shadow">
            <h2 class="text-center mb-4 fw-bold text-primary">Reset Password</h2>
            <p class="text-center text-muted">Enter your new password.</p>
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
            <form action="../../models/login/resetpassword.php" method="POST">
                <div class="form-outline mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-outline mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="passwordconfirm" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase btn-custom">Reset</button>
            </form>
        </div>
    </div>
</body>

</html>