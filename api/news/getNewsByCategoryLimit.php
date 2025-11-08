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
    if (!isset($_GET['limit']) || !is_numeric($_GET['limit'])) {
        echo json_encode(['msg' => 'Category Limit is invalid', 'status' => false, 'des' => 'Please Make Request Again.']);
        exit();
    }
    $category = $_GET['categoryid'];
    $limit = $_GET['limit'];
    $stmt = $connect->prepare("
                SELECT n.*, c.name AS categoryname, c.slug AS categoryslug, a.name as authorname FROM news n 
                INNER JOIN categories c ON c.id = n.category_id 
                INNER JOIN admins a ON a.id = n.author_id
                WHERE n.category_id = ? AND n.status = 'Published' AND c.status = 'Active' ORDER BY n.created_at DESC LIMIT $limit
            ");
    $stmt->bind_param('i', $category);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows <= 0) {
        echo json_encode(['msg' => 'No News Found', 'status' => false, 'des' => 'There are no published news in category id: ' . $category . '.']);
        exit();
    }
    $cate = '';
    $slug = '';
    $row = [];
    while ($data = $result->fetch_assoc()) {
        $cate = $data['categoryname'];
        $slug = $data['categoryslug'];
        $data['image'] = 'https://phpstack-1546894-5983648.cloudwaysapps.com/upload/news/' . $data['image'];
        $row[] = $data;
    }
    echo json_encode(['data' => $row, 'status' => true, 'des' => '', 'category' => $cate, 'slug' => $slug]);
} else
    echo json_encode(['msg' => "Only GET Method Allowed!", 'status' => false, 'des' => "Received Method: " . $_SERVER['REQUEST_METHOD']]);

?>
