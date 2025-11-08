<?php
session_start();
require '../../vendor/autoload.php';
require '../../connection/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['response'] = ['msg' => "Invalid Email!", 'des' => "Please input a valid email."];
    header("Location: ../../views/login/forgetpassword.php");
    exit();
}
$stmt = $connect->prepare("SELECT * FROM admins WHERE email = ?");
if (!$stmt) {
    $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
    header("Location: ../../views/login/forgetpassword.php");
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($data = $result->fetch_array()) {
    $otp = rand(100000, 999999);
    $stmt = $connect->prepare("UPDATE admins SET otp_code = ?, otp_expires = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE id = ?");
    $stmt->bind_param("si", $otp, $data['id']);
    $stmt->execute();
    $stmt->close();
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = "kimsortrevor@gmail.com";
        $mail->Password = "exch tjcp unhn nrrw";
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom("kimsortrevor@gmail.com", 'NewsToday');
        $mail->addAddress($data['email']);
        $mail->isHTML(true);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "
                <html>
                <head>
                <style>
                    body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f7;
                    margin: 0;
                    padding: 0;
                    }
                    .container {
                    max-width: 600px;
                    margin: 50px auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    padding: 30px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    text-align: center;
                    }
                    h1 {
                    color: #333333;
                    }
                    p {
                    color: #555555;
                    font-size: 16px;
                    }
                    .otp {
                    display: inline-block;
                    font-size: 24px;
                    font-weight: bold;
                    background-color: #e2e8f0;
                    padding: 15px 25px;
                    border-radius: 8px;
                    letter-spacing: 4px;
                    margin: 20px 0;
                    color: #111827;
                    }
                    .footer {
                    font-size: 12px;
                    color: #999999;
                    margin-top: 20px;
                    }
                    a.button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #3b82f6;
                    color: white !important;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 20px;
                    }
                </style>
                </head>
                <body>
                <div class='container'>
                    <h1>NewsToday OTP Forgot Password</h1>
                    <p>Your one-time password (OTP) is valid for the next 5 minutes. Please use it to complete your reset password.</p>
                    <div class='otp'>{$otp}</div>
                    <p>If you did not request this, please ignore this email.</p>
                    <div class='footer'>© " . date('Y') . " NewsToday. All rights reserved.</div>
                </div>
                </body>
                </html>
                ";
        $mail->send();
    } catch (Exception $e) {
        $_SESSION['response'] = ['msg' => "Mailer Error: {$mail->ErrorInfo}", 'status' => false, 'des' => ''];
        header("Location: ../../views/login/forgetpassword.php");
        exit();
    }
    $_SESSION['pending_admin'] = $data['id'];
    header("Location: ../../views/login/verify-password-otp.php");
    exit();
} else {
    $_SESSION['response'] = ["msg" => "Admin Not Found", 'status' => false, "des" => "Email didn't have an account."];
    header("Location: ../../views/login/forgetpassword.php");
    exit();
}
?>