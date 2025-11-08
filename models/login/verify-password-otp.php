<?php
session_start();
require_once '../../connection/config.php';
if (!isset($_SESSION['pending_admin'])) {
    $_SESSION['response'] = ['msg' => "Admin pending is missing", 'status' => false, 'des' => ''];
    header("Location: ../../views/login/index.php");
    exit();
}
$id = $_SESSION['pending_admin'];
$otp = intval($_POST['otp']);
$stmt = $connect->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($data = $result->fetch_assoc()) {
    if ($data['otp_code'] == $otp && strtotime($data['otp_expires']) > time()) {
        $stmt = $connect->prepare("UPDATE admins SET otp_code = NULL, otp_expires = NULL WHERE id=?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        $stmt->close();
        header("Location: ../../views/login/resetpassword.php");
        exit();
    } else {
        $_SESSION['response'] = ['msg' => "Invalid or Expire OTP!", 'status' => false, 'des' => ''];
        header("Location: ../../views/login/verify-password-otp.php");
        exit();
    }
} else {
    unset($_SESSION['pending_admin']);
    $_SESSION['response'] = ['msg' => "Admin not found!", 'status' => false, 'des' => ''];
    header("Location: ../../views/login/index.php");
    exit();
}
?>