<?php
session_start();
require_once '../../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    if ($id <= 0) {
        $_SESSION['response'] = ['msg' => 'Invalid News ID', 'status' => false, 'des' => "Received ID: " . $_POST['id']];
        header("Location: ../../views/news/index.php");
        exit();
    }
    $stmt = $connect->prepare("SELECT * FROM news WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        header("Location: ../../views/news/index.php");
        exit();
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$data) {
        $_SESSION['response'] = ['msg' => "News Not Found!", 'status' => false, 'des' => "No news found with ID: $id"];
        header("Location: ../../views/news/index.php");
        exit();
    }
    $title = trim($_POST['title']);
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
    $uploadDir = '../../upload/news/';
    $imagename = $data['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $_SESSION['response'] = ['msg' => 'News image is Invalid!', 'status' => false, 'des' => "Only JPG, PNG, JPEG, GIF extensions are allowed."];
            goto StopAndExit;
        }
        $imagename = uniqid("News_Image_" . time() . "_", true) . ".$ext";
        $uploadFrom = $_FILES['image']['tmp_name'];
        $uploadTo = $uploadDir . $imagename;
        if (!move_uploaded_file($uploadFrom, $uploadTo)) {
            $_SESSION['response'] = ['msg' => "Failed to upload image!", 'status' => false, 'des' => "Check folder permissions."];
            goto StopAndExit;
        }
    }
    $stmt = $connect->prepare("UPDATE news SET title = ?, slug = ?, description = ?, category_id = ?, author_id = ?, status = ?, image = ? WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed!", 'status' => false, 'des' => $connect->error];
        if (isset($uploadTo) && file_exists($uploadTo))
            unlink($uploadTo);
        header("Location: ../../views/news/index.php");
        exit();
    }
    $stmt->bind_param('sssiissi', $title, $slug, $des, $categoryid, $authorid, $status, $imagename, $id);
    if ($stmt->execute()) {
        $_SESSION['response'] = ['msg' => "News Updated Successfully!", 'status' => true, 'des' => "News: $title (ID: $id) updated."];
        if (isset($uploadTo) && file_exists($uploadDir . $data['image']))
            unlink($uploadDir . $data['image']);
    } else {
        $_SESSION['response'] = ['msg' => "Update Failed!", 'status' => false, 'des' => $stmt->error];
        if (isset($uploadTo) && file_exists($uploadTo))
            unlink($uploadTo);
    }
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method!", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndExit:
header("Location: ../../views/news/index.php");
exit();
?>