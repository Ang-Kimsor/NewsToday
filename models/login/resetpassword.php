<?php
session_start();
require_once '../../connection/config.php';
if (!isset($_SESSION['pending_admin'])) {
    $_SESSION['response'] = ['msg' => "Admin pending is missing", 'status' => false, 'des' => ''];
    header("Location: ../../views/login/index.php");
    exit();
}
$id = $_SESSION['pending_admin'];
$password = trim($_POST['password']);
$passwordconfirm = trim($_POST['passwordconfirm']);
if ($password != $passwordconfirm) {
    $_SESSION['response'] = ['msg' => "Password Not Match!", 'status' => false, 'des' => ''];
    header("Location: ../../views/login/resetpassword.php");
    exit();
}
$passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
if (!preg_match($passwordPattern, $password) && !preg_match($passwordPattern, $passwordconfirm)) {
    $_SESSION['response'] = ['msg' => "Invalid Password!", 'status' => false, 'des' => "Password must have 8+ chars, uppercase, lowercase, digit & symbol."];
    header("Location: ../../views/login/resetpassword.php");
    exit();
}
$password = password_hash($password, PASSWORD_ARGON2ID);
$stmt = $connect->prepare("UPDATE admins SET password = ? WHERE id = ?");
$stmt->bind_param('si', $password, $id);
if ($stmt->execute()) {
    $_SESSION['response'] = ['msg' => "Password Reset!", 'status' => true, 'des' => ""];
} else {
    $_SESSION['response'] = ['msg' => "Password Can't Reset!", 'status' => false, 'des' => ""];
}
header("Location: ../../views/login/index.php");
exit();
?>