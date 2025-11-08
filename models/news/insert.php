<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    $title = trim(filter_var($_POST['title'], FILTER_SANITIZE_ADD_SLASHES));
    if (empty($title)) {
        $_SESSION['response'] = ['msg' => "Title is empty!", 'status' => false, 'des' => "Please input a title."];
        goto StopAndExit;
    }
    $slug = trim($_POST['slug']);
    if (empty($slug)) {
        $_SESSION['response'] = ['msg' => "Slug is empty!", 'status' => false, 'des' => "Please input a slug."];
        goto StopAndExit;
    }
    $des = trim($_POST['des']);
    if (empty($des)) {
        $_SESSION['response'] = ['msg' => "Description is empty!", 'status' => false, 'des' => "Please input the description."];
        goto StopAndExit;
    }
    $categoryid = intval($_POST['category']);
    if ($categoryid <= 0) {
        $_SESSION['response'] = ['msg' => "Category Not Exist!", 'status' => false, 'des' => "Please input a valid category."];
        goto StopAndExit;
    }
    $authorid = $_SESSION['admin']['id'];
    $status = trim($_POST['status']);
    // image
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $_SESSION['response'] = ['msg' => "Image Required", 'status' => false, 'des' => 'Please input the image.'];
        goto StopAndExit;
    }
    $uploadFrom = $_FILES['image']['tmp_name'];
    $originalName = $_FILES['image']['name'];
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) {
        $_SESSION['response'] = ['msg' => 'News Image is Invalid!', 'status' => false, 'des' => "Only JPG, PNG, JPEG, GIF extensions are allowed."];
        goto StopAndExit;
    }
    $filename = uniqid("News_Image_" . time() . "_", true) . ".$ext";
    $uploadDir = "../../upload/news/";
    $uploadTo = $uploadDir . $filename;
    if (!move_uploaded_file($uploadFrom, $uploadTo)) {
        $_SESSION['response'] = ['msg' => "Failed to upload image!", 'status' => false, 'des' => "Check folder permissions."];
        goto StopAndExit;
    }
    // stmt
    $stmt = $connect->prepare("INSERT INTO news(id,title,slug,description,category_id,author_id,status,image) VALUES(?,?,?,?,?,?,?,?)");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        if (file_exists($uploadTo))
            unlink($uploadTo);
        goto StopAndExit;
    }
    $stmt->bind_param("isssiiss", $id, $title, $slug, $des, $categoryid, $authorid, $status, $filename);
    if ($stmt->execute())
        $_SESSION['response'] = ['msg' => "Insert Succeed! ", 'status' => true, 'des' => "News: $title has inserted into database."];
    else {
        $_SESSION['response'] = ['msg' => "Insert Failed! ", 'status' => false, 'des' => $stmt->error];
        if (file_exists($uploadTo))
            unlink($uploadTo);
    }
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndExit:
header("Location: ../../views/news/index.php");
exit();
?>