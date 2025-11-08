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
    if (!isset($_GET['slug']) || empty(trim($_GET['slug']))) {
        echo json_encode(['msg' => 'News Slug is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    $cateid = $_GET['categoryid'];
    $slug = $_GET['slug'];
    $stmt = $connect->prepare("
                SELECT n.*, c.name AS categoryname, c.slug AS categoryslug, a.name as authorname FROM news n 
                INNER JOIN categories c ON c.id = n.category_id 
                INNER JOIN admins a ON a.id = n.author_id
                WHERE n.category_id = ? AND n.slug = ? AND n.status = 'Published' AND c.status = 'Active'
            ");
    $stmt->bind_param('is', $cateid, $slug);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    if (!$data) {
        echo json_encode(['msg' => 'News Not Found', 'status' => false, 'des' => 'There are no published news slug: ' . $slug . '.']);
        exit();
    }
    $data['image'] = 'http://localhost/Project/NewsToday/upload/news/' . $data['image'];
    echo json_encode(['data' => $data, 'status' => true, 'des' => '']);
    $stmt = $connect->prepare("UPDATE news SET views = views + 1 WHERE category_id = ? AND slug = ?");
    $stmt->bind_param('is', $cateid, $slug);
    $stmt->execute();
} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);

?>