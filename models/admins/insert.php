<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    $name = trim(addslashes($_POST['name']));
    if (empty($name)) {
        $_SESSION['response'] = ['msg' => "Admin Name is empty!", 'status' => false, 'des' => "Please input a name."];
        goto StopAndEnd;
    }
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['response'] = ['msg' => "Invalid Email!", 'status' => false, 'des' => "Please input a valid email."];
        goto StopAndEnd;
    }
    $password = trim($_POST['password']);
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        $_SESSION['response'] = ['msg' => "Invalid Password!", 'status' => false, 'des' => "Password must have 8+ chars, uppercase, lowercase, digit & symbol."];
        goto StopAndEnd;
    }
    $password = password_hash($password, PASSWORD_ARGON2ID);
    $role = trim($_POST['role']);
    $status = trim($_POST['status']);
    $defaultImage = 'profile.jpg';
    $imagename = $defaultImage;
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
        $uploadFrom = $_FILES['profile']['tmp_name'];
        $originalName = $_FILES['profile']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $_SESSION['response'] = ['msg' => 'Admin Profile is Invalid!', 'status' => false, 'des' => "Only JPG, PNG, JPEG, GIF extensions are allowed."];
            goto StopAndEnd;
        }
        $filename = uniqid("Admin_Image_" . time() . "_", true) . ".$ext";
        $uploadDir = "../../upload/admins/";
        $uploadTo = $uploadDir . $filename;
        if (!move_uploaded_file($uploadFrom, $uploadTo)) {
            $_SESSION['response'] = ['msg' => "Failed to upload image!", 'status' => false, 'des' => "Check folder permissions."];
            goto StopAndEnd;
        }
        $imagename = $filename;
    }
    $stmt = $connect->prepare("INSERT INTO admins(id,name,email,password,role,status,profile) VALUES(?,?,?,?,?,?,?)");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        if (file_exists($uploadTo))
            unlink($uploadTo);
        goto StopAndEnd;
    }
    $stmt->bind_param("issssss", $id, $name, $email, $password, $role, $status, $imagename);
    if ($stmt->execute())
        $_SESSION['response'] = ['msg' => "Insert Succeed! ", 'status' => true, 'des' => "Admin: $name has inserted into database."];
    else {
        $_SESSION['response'] = ['msg' => "Insert Failed! ", 'status' => false, 'des' => $stmt->error];
        if (file_exists($uploadTo))
            unlink($uploadTo);
    }
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndEnd:
header("Location: ../../views/admins/index.php");
exit();
?>