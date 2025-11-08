<?php
session_start();
require_once '../../connection/config.php';
if (isset($_SESSION['admin'])) {
    $stmt = $connect->prepare("UPDATE admins SET auth_token = NULL, auth_token_expires = NULL WHERE id = ?");
    $stmt->bind_param('i', $_SESSION['admin']['id']);
    $stmt->execute();
    $stmt->close();
}
session_unset();
session_destroy();
setcookie("auth_token", '', time() - 86400 * 7, '/');
header("Location: ../../views/login/index.php");
exit();
?>