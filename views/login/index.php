<?php
session_start();
require '../../root/header.php';
?>
<title>NewsToday - Login</title>
<link rel="stylesheet" href="../../asset/style/form.css">
</head>

<body class="bg-secondary">
    <div class="login-container">
        <div class="card login-card p-4">
            <div class="card-body">
                <h2 class="text-center mb-4 fw-bold text-primary">Login to NewsToday</h2>
                <?php
                if (isset($_SESSION['response'])) {
                    $status = $_SESSION['response']['status'] ? "success" : "danger";
                    ?>
                    <div class="alert alert-<?php echo $status ?>">
                        <span class="fw-bold"><?php echo $_SESSION['response']['msg'] ?></span>
                        <br>
                        <?php echo $_SESSION['response']['des'] ?>
                    </div>
                <?php } ?>
                <form action="../../models/login/login.php" method="POST">
                    <div class="form-outline mb-3">
                        <label class="form-label fw-semibold">Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-outline mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password"
                            required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="check" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>
                        <a href="forgetpassword.php" class="text-decoration-none">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-custom text-uppercase fw-bold">Sign
                        In</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php unset($_SESSION['response']); ?>

</html>