<?php
session_start();
require_once '../../connection/config.php';
date_default_timezone_set('Asia/Phnom_Penh');
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
        $_SESSION['admin'] = ['id' => $data['id'], 'name' => $data['name'], 'role' => $data['role'], 'status' => $data['status'], 'profile' => $data['profile']];
        if (isset($_SESSION['remember_me']) && $_SESSION['remember_me']) {
            $expires = time() + 86400 * 7;
            $expires_token = date('Y-m-d H:i:s', $expires);
            $token = bin2hex(random_bytes(32));
            setcookie("auth_token", $token, $expires, "/", "", true, true);
            $stmt = $connect->prepare("UPDATE admins SET auth_token = ?, auth_token_expires = ? WHERE id = ?");
            $stmt->bind_param("ssi", $token, $expires_token, $data['id']);
            $stmt->execute();
            $stmt->close();
        }
        $stmt = $connect->prepare("UPDATE admins SET otp_code = NULL, otp_expires = NULL WHERE id=?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        $stmt->close();
        unset($_SESSION['pending_admin']);
        unset($_SESSION['remember_me']);
        header("Location: ../../index.php");
        exit();
    } else {
        $_SESSION['response'] = ['msg' => "Invalid or Expire OTP!", 'status' => false, 'des' => ''];
        header("Location: ../../views/login/verify-otp.php");
        exit();
    }
} else {
    unset($_SESSION['pending_admin']);
    unset($_SESSION['remember_me']);
    $_SESSION['response'] = ['msg' => "Admin not found!", 'status' => false, 'des' => ''];
    header("Location: ../../views/login/index.php");
    exit();
}
?>