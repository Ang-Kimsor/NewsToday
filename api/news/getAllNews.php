<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $stmt = "
                    SELECT n.*, c.name AS categoryname, c.slug AS categoryslug, a.name as authorname FROM news n 
                    INNER JOIN categories c ON c.id = n.category_id 
                    INNER JOIN admins a ON a.id = n.author_id
                WHERE n.status = 'Published' AND c.status = 'Active'
            ";
    $result = $connect->query($stmt);
    if ($result->num_rows <= 0) {
        echo json_encode(['msg' => 'News Not Found', 'status' => false, 'des' => 'There are no active news.']);
        exit();
    }
    $row = [];
    while ($data = $result->fetch_assoc()) {
        $data['image'] = 'http://localhost/Project/NewsToday/upload/news/' . $data['image'];
        $row[] = $data;
    }
    echo json_encode(['data' => $row, 'status' => true, 'des' => '']);

} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);

?>