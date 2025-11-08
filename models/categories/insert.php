<?php
session_start();
require_once '../../connection/config.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? '');
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
    $stmt = $connect->prepare("INSERT INTO categories(id, name, slug, status, description) VALUES(?,?,?,?,?)");
    if (!$stmt) {
        $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
        goto StopAndEnd;
    }
    $stmt->bind_param('issss', $id, $name, $slug, $status, $des);
    if ($stmt->execute())
        $_SESSION['response'] = ['msg' => "Insert Succeed! ", 'status' => true, 'des' => "Category: $name has inserted into database."];
    else
        $_SESSION['response'] = ['msg' => "Insert Failed! ", 'status' => false, 'des' => $stmt->error];
    $stmt->close();
} else
    $_SESSION['response'] = ['msg' => "Invalid Request Method! ", 'status' => false, 'des' => "Only POST request is allowed. You sent a " . $_SERVER['REQUEST_METHOD'] . " request."];
StopAndEnd:
header("Location: ../../views/categories/index.php");
exit();
?>