<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!isset($_GET['bignews']) || !is_numeric($_GET['bignews'])) {
        echo json_encode(['msg' => 'News Limit is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    $big = $_GET['bignews'];
    $stmt = "
                SELECT n.*, c.name AS categoryname, c.slug AS categoryslug, a.name as authorname FROM news n 
                INNER JOIN categories c ON c.id = n.category_id 
                INNER JOIN admins a ON a.id = n.author_id
                WHERE n.status = 'Published' AND c.status = 'Active' ORDER BY n.created_at DESC LIMIT $big
                ";
    $result = $connect->query($stmt);
    if ($data = $result->fetch_assoc()) {
        $data['image'] = 'http://localhost/Project/NewsToday/upload/news/' . $data['image'];
        echo json_encode(['data' => $data, 'status' => true, 'des' => '']);
    } else
        echo json_encode(['msg' => 'News Not Found', 'status' => false, 'des' => 'There are no active news.']);
} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);

?>