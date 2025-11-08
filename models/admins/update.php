<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    if ($id <= 0) {
        $_SESSION['response'] = ['msg' => 'Invalid Admin ID', 'status' => false, 'des' => "Received ID: " . $_POST['id']];
        goto StopAndEnd;
    }
    $stmt = $connect->prepare("SELECT * FROM admins WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        goto StopAndEnd;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$data) {
        $_SESSION['response'] = ['msg' => "Admin Not Found!", 'status' => false, 'des' => "No admin found with ID: $id"];
        goto StopAndEnd;
    }
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
    $role = trim($_POST['role']);
    $status = trim($_POST['status']);
    // Password
    $password = trim($_POST['password'] ?? '');
    if (!empty($password)) {
        $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
        if (!preg_match($passwordPattern, $password)) {
            $_SESSION['response'] = ['msg' => "Invalid Password!", 'status' => false, 'des' => "Password must have 8+ chars, uppercase, lowercase, digit & symbol."];
            goto StopAndEnd;
        }
        $password = password_hash($password, PASSWORD_ARGON2ID);
    } else
        $password = $data['password'];

    // Image
    $uploadDir = '../../upload/admins/';
    $uploadTo = null;
    $oldimage = $data['profile'] ?? 'profile.jpg';
    $imagename = $oldimage;
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
        $file = $_FILES['profile'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $_SESSION['response'] = ['msg' => 'Admin Profile is Invalid!', 'status' => false, 'des' => "Only JPG, PNG, JPEG, GIF extensions are allowed."];
            goto StopAndEnd;
        }
        $imagename = uniqid("Admin_Image_" . time() . "_", true) . ".$ext";
        $uploadFrom = $file['tmp_name'];
        $uploadTo = $uploadDir . $imagename;
        if (!move_uploaded_file($uploadFrom, $uploadTo)) {
            $_SESSION['response'] = ['msg' => "Failed to upload image!", 'status' => false, 'des' => "Check folder permissions."];
            goto StopAndEnd;
        }
    }
    $stmt = $connect->prepare("UPDATE admins SET name = ?, email = ?, password = ?, role = ?, status = ?, profile = ? WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        if ($imagename !== $oldimage && $imagename !== 'profile.jpg' && file_exists($uploadTo))
            unlink($uploadTo);
        goto StopAndEnd;
    }
    $stmt->bind_param('ssssssi', $name, $email, $password, $role, $status, $imagename, $id);
    if ($stmt->execute()) {
        $_SESSION['response'] = ['msg' => "Admin Updated Successfully!", 'status' => true, 'des' => "Admin: $name (ID: $id) updated."];
        if ($id == $_SESSION['admin']['id'])
            $_SESSION['admin'] = ['id' => $id, 'name' => $name, 'role' => $role, 'status' => $status, 'profile' => $imagename];
        if ($imagename !== $oldimage && $oldimage !== 'profile.jpg' && file_exists($uploadDir . $oldimage))
            unlink($uploadDir . $oldimage);
    } else {
        $_SESSION['response'] = ['msg' => "Update Failed!", 'status' => false, 'des' => $stmt->error];
        if ($imagename !== $oldimage && $imagename !== 'profile.jpg' && file_exists($uploadTo))
            unlink($uploadTo);
    }
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndEnd:
header("Location: ../../views/admins/index.php");
exit();
?>