<?php
session_start();
require_once '../../connection/config.php';
$id = intval($_GET['id']);
if ($id <= 0) {
    $_SESSION['response'] = ['msg' => 'Invalid ID From URL', 'status' => false, 'des' => "Recieve ID: " . $_GET['id'] . "."];
    goto StopAndEnd;
}
$stmt = $connect->prepare("SELECT * FROM news WHERE id = ?");
if (!$stmt) {
    $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
    goto StopAndEnd;
}
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$data) {
    $_SESSION['response'] = ['msg' => "News Not Found! ", 'status' => false, 'des' => "There is no news id: $id in database"];
    goto StopAndEnd;
}
$stmt = $connect->prepare("DELETE FROM news WHERE id = ?");
if (!$stmt) {
    $_SESSION['response'] = ['msg' => "Prepare Statement Failed! ", 'status' => false, 'des' => $connect->error];
    goto StopAndEnd;
}
$stmt->bind_param('i', $id);
$title = $data['title'];
if ($stmt->execute()) {
    $_SESSION['response'] = ['msg' => "Delete Succeed! ", 'status' => true, 'des' => "News: $title (ID: $id) has deleted from database."];
    if (file_exists('../../upload/news/' . $data['image']))
        unlink('../../upload/news/' . $data['image']);
} else
    $_SESSION['response'] = ['msg' => "Delete Failed! ", 'status' => false, 'des' => $stmt->error];
$stmt->close();
StopAndEnd:
header("Location: ../../views/news/index.php");
exit();
?>