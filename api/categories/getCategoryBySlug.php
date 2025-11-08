<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!isset($_GET['slug']) || empty(trim($_GET['slug']))) {
        echo json_encode(['msg' => 'Category Slug is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    $slug = $_GET['slug'];
    $stmt = $connect->prepare("SELECT * FROM categories WHERE status = 'Active' AND slug = ?");
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    if (!$data) {
        echo json_encode(['msg' => 'No Category Found', 'status' => false, 'des' => 'There are no category slug: ' . $slug . '.']);
        exit();
    }
    echo json_encode(['data' => $data, 'status' => true, 'des' => '']);
} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);
?>