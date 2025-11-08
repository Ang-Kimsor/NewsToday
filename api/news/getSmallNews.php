<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!isset($_GET['smallnews']) || !is_numeric($_GET['smallnews'])) {
        echo json_encode(['msg' => 'News Limit is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    if (!isset($_GET['created_at']) || empty(trim($_GET['created_at']))) {
        echo json_encode(['msg' => 'Created Date is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    $small = $_GET['smallnews'];
    $date = $_GET['created_at'];
    $stmt = $connect->prepare("
            SELECT n.*, c.name AS categoryname, c.slug AS categoryslug, a.name as authorname FROM news n 
                INNER JOIN categories c ON c.id = n.category_id 
                INNER JOIN admins a ON a.id = n.author_id
                WHERE n.status = 'Published' AND n.created_at < ? AND c.status = 'Active' ORDER BY n.created_at DESC LIMIT $small
                ");
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows <= 0) {
        echo json_encode(['msg' => 'No News Found', 'status' => false, 'des' => 'There are no published news in category id: ' . $category . '.']);
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