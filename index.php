<?php
session_start();
require 'root/header.php';
require 'connection/config.php';
if (isset($_COOKIE['auth_token'])) {
    $stmt = $connect->prepare("SELECT id, name, role, status, profile FROM admins WHERE auth_token = ? AND auth_token_expires > NOW() AND status = 'Active'");
    $stmt->bind_param('s', $_COOKIE['auth_token']);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($data)
        $_SESSION['admin'] = ['id' => $data['id'], 'name' => $data['name'], 'role' => $data['role'], 'status' => $data['status'], 'profile' => $data['profile']];
    else {
        $stmt = $connect->prepare("UPDATE admins SET auth_token = NULL, auth_token_expires = NULL WHERE auth_token = ?");
        $stmt->bind_param('s', $_COOKIE['auth_token']);
        $stmt->execute();
        $stmt->close();
        setcookie("auth_token", '', time() - 86400 * 7, '/');
        session_destroy();
        header("Location: views/login/index.php");
        exit();
    }
}

if (!isset($_SESSION['admin'])) {
    header("Location: views/login/index.php");
    exit();
}
?>
<title>NewsToday Management</title>
</head>
<frameset cols="300px,*" frameborder="0" border="0">
    <frame src="root/sidemenu.php" name="sidemenu">
        <frameset rows="65px,*">
            <frame src="root/navbar.php" name="navbar">
                <frame src="root/content.php" name="content">
        </frameset>
</frameset>

</html>