<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
    if ($id <= 0) {
        $_SESSION['response'] = ['msg' => 'Invalid ID From URL', 'status' => false, 'des' => "Recieve ID: " . $_POST['id'] . "."];
        goto StopAndEnd;
    }
    $stmt = $connect->prepare("SELECT * FROM categories WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
        goto StopAndEnd;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$data) {
        $_SESSION['response'] = ['msg' => "Category Not Found! ", 'status' => false, 'des' => "There is no category id: $id in database"];
        goto StopAndEnd;
    }

    $name = trim(addslashes($_POST['name']));
    if (empty($name)) {
        $_SESSION['response'] = ['msg' => "Category Name is Empty! ", 'status' => false, 'des' => "Please Input Again."];
        goto StopAndEnd;
    }
    $slug = trim(addslashes($_POST['slug']));
    if (empty($slug)) {
        $_SESSION['response'] = ['msg' => "Category Slug is Empty! ", 'status' => false, 'des' => "Please Input Again."];
        goto StopAndEnd;
    }
    $status = trim($_POST['status']);
    $des = trim(addslashes($_POST['des']));
    $stmt = $connect->prepare("UPDATE categories SET name = ?, slug = ?, status = ?, description = ? WHERE id = ?");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
        goto StopAndEnd;
    }
    $stmt->bind_param('ssssi', $name, $slug, $status, $des, $id);
    if ($stmt->execute())
        $_SESSION['response'] = ['msg' => "Update Succeed! ", 'status' => true, 'des' => "Category: $name (ID: $id) has update into database."];
    else
        $_SESSION['response'] = ['msg' => "Update Failed! ", 'status' => false, 'des' => $stmt->error];
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndEnd:
header("Location: ../../views/categories/index.php");
exit();
?>