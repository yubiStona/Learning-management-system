<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['theme'])) {
        $_SESSION['theme'] = $data['theme'];
        echo json_encode(["status" => "success", "theme" => $data['theme']]);
        exit;
    }
}
echo json_encode(["status" => "error"]);
?>
