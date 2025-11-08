<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    if ($id <= 0) {
        $_SESSION['response'] = ['msg' => 'Invalid Ads ID', 'status' => false, 'des' => "Received ID: " . $_POST['id']];
        goto StopAndEnd;
    }
    $stmt = $connect->prepare("SELECT * FROM advertising WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        goto StopAndEnd;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$data) {
        $_SESSION['response'] = ['msg' => "Ads Not Found!", 'status' => false, 'des' => "No ads found with ID: $id"];
        goto StopAndEnd;
    }
    $name = trim(addslashes($_POST['name']));
    if (empty($name)) {
        $_SESSION['response'] = ['msg' => "Ads Name is empty!", 'status' => false, 'des' => "Please input a name."];
        goto StopAndEnd;
    }
    $des = trim(addslashes($_POST['des']));
    $status = trim($_POST['status']);
    // Image
    $uploadDir = '../../upload/advertising/';
    $uploadTo = null;
    $oldimage = $data['image'] ?? 'image.jpg';
    $imagename = $oldimage;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $_SESSION['response'] = ['msg' => 'Ads image is Invalid!', 'status' => false, 'des' => "Only JPG, PNG, JPEG, GIF extensions are allowed."];
            goto StopAndEnd;
        }
        $imagename = uniqid("Ads_Image_" . time() . "_", true) . ".$ext";
        $uploadFrom = $file['tmp_name'];
        $uploadTo = $uploadDir . $imagename;
        if (!move_uploaded_file($uploadFrom, $uploadTo)) {
            $_SESSION['response'] = ['msg' => "Failed to upload image!", 'status' => false, 'des' => "Check folder permissions."];
            goto StopAndEnd;
        }
    }
    $stmt = $connect->prepare("UPDATE advertising SET name = ?, description = ?, status = ?, image = ? WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        if ($imagename !== $oldimage && $imagename !== 'image.jpg' && file_exists($uploadTo))
            unlink($uploadTo);
        goto StopAndEnd;
    }
    $stmt->bind_param('ssssi', $name, $des, $status, $imagename, $id);
    if ($stmt->execute()) {
        $_SESSION['response'] = ['msg' => "Ads Updated Successfully!", 'status' => true, 'des' => "Ads: $name (ID: $id) updated."];
        if ($imagename !== $oldimage && $oldimage !== 'image.jpg' && file_exists($uploadDir . $oldimage))
            unlink($uploadDir . $oldimage);
    } else {
        $_SESSION['response'] = ['msg' => "Update Failed!", 'status' => false, 'des' => $stmt->error];
        if ($imagename !== $oldimage && $imagename !== 'image.jpg' && file_exists($uploadTo))
            unlink($uploadTo);
    }
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndEnd:
header("Location: ../../views/advertising/index.php");
exit();
?>