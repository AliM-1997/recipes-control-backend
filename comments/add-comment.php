<?php
require "../connection-file.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['user_id'], $data['recipe_id'],$data["content"])) {
        $user_id = $data['user_id'];
        $recipe_id=$data['recipe_id'];
        $content=$data['content'];
        print_r($data);
        $stm = $conn->prepare("select * from recipes where id = ?");
        $stm->bind_param("i", $recipe_id);
        $stm->execute();
        $result = $stm->get_result();

        if ($result->num_rows > 0) {
            $stm = $conn->prepare("insert into comments (user_id, recipe_id, content) value (?, ?, ?)");
            $stm->bind_param("iis", $user_id, $recipe_id, $content);

            try {
                $stm->execute();
                echo json_encode(["message" => "Comment added successfully", "status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["message" => "comment not added", "status" => "failure"]);
            }
        } else {
            echo json_encode(["message" => "Recipe not found", "status" => "failure"]);
        }
    } else {
        echo json_encode(["message" => "Invalid input", "status" => "failure"]);
    }
} else {
    echo json_encode(["message" => "Wrong request method", "status" => "failure"]);
}
