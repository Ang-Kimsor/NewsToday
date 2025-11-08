<?php
require_once '../../connection/config.php';
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!isset($_GET['categoryid']) || !is_numeric($_GET['categoryid'])) {
        echo json_encode(['msg' => 'Category ID is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    $category = $_GET['categoryid'];
    $stmt = $connect->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->bind_param('i', $category);
    $stmt->execute();
    $categoryname = $stmt->get_result()->fetch_assoc()['name'];
    $stmt->close();
    $stmt = $connect->prepare("
            SELECT n.*, c.name AS categoryname, c.slug AS categoryslug, a.name as authorname FROM news n 
            INNER JOIN categories c ON c.id = n.category_id 
            INNER JOIN admins a ON a.id = n.author_id
            WHERE n.category_id = ? AND n.status = 'Published' AND c.status = 'Active' ORDER BY n.created_at DESC
            ");
    $stmt->bind_param('i', $category);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows <= 0) {
        echo json_encode(['msg' => 'No News Found', 'status' => false, 'des' => 'There are no published news in category name: ' . $categoryname . '.']);
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