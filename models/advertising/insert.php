<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    $name = trim(addslashes($_POST['name']));
    if (empty($name)) {
        $_SESSION['response'] = ['msg' => "Ads Name is empty!", 'status' => false, 'des' => "Please input a name."];
        goto StopAndEnd;
    }
    $des = trim(addslashes($_POST['des']));
    $status = trim($_POST['status']);
    $defaultImage = 'image.jpg';
    $imagename = $defaultImage;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadFrom = $_FILES['image']['tmp_name'];
        $originalName = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $_SESSION['response'] = ['msg' => 'Ads Image is Invalid!', 'status' => false, 'des' => "Only JPG, PNG, JPEG, GIF extensions are allowed."];
            goto StopAndEnd;
        }
        $filename = uniqid("Ads_Image_" . time() . "_", true) . ".$ext";
        $uploadDir = "../../upload/advertising/";
        $uploadTo = $uploadDir . $filename;
        if (!move_uploaded_file($uploadFrom, $uploadTo)) {
            $_SESSION['response'] = ['msg' => "Failed to upload image!", 'status' => false, 'des' => "Check folder permissions."];
            goto StopAndEnd;
        }
        $imagename = $filename;
    }
    $stmt = $connect->prepare("INSERT INTO advertising(id,name,description,status,image) VALUES(?,?,?,?,?)");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        if (file_exists($uploadTo))
            unlink($uploadTo);
        goto StopAndEnd;
    }
    $stmt->bind_param("issss", $id, $name, $des, $status, $imagename);
    if ($stmt->execute())
        $_SESSION['response'] = ['msg' => "Insert Succeed! ", 'status' => true, 'des' => "Ads: $name has inserted into database."];
    else {
        $_SESSION['response'] = ['msg' => "Insert Failed! ", 'status' => false, 'des' => $stmt->error];
        if (file_exists($uploadTo))
            unlink($uploadTo);
    }
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndEnd:
header("Location: ../../views/advertising/index.php");
exit();
?>