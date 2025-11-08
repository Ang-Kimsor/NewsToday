<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $stmt = "SELECT * FROM advertising WHERE status = 'Active' LIMIT 2";
    $result = $connect->query($stmt);
    if ($result->num_rows <= 0) {
        echo json_encode(['msg' => 'Ads Not Found', 'status' => false, 'des' => 'There are no active ads.']);
        exit();
    }
    $row = [];
    while ($data = $result->fetch_assoc()) {
        $data['image'] = 'https://phpstack-1546894-5983648.cloudwaysapps.com/upload/advertising/' . $data['image'];
        $row[] = $data;
    }
    echo json_encode(['data' => $row, 'status' => true, 'des' => '']);
} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);
?>
