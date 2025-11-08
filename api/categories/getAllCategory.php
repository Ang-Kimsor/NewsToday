<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    $stmt = "SELECT * FROM categories WHERE status = 'Active'";
    $result = $connect->query($stmt);
    if ($result->num_rows <= 0) {
        echo json_encode(['msg' => 'No Category Found', 'status' => false, 'des' => 'There are no category.']);
        exit();
    }
    $row = [];
    while ($data = $result->fetch_assoc())
        $row[] = $data;
    echo json_encode(['data' => $row, 'status' => true, 'des' => '']);

} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);
?>