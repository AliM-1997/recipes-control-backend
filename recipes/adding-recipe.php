<?php
require "../connection-file.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['user_id'], $data['name'], $data['description'],$data["ingredients"])) {
        $user_id = $data['user_id'];
        $recipe_name = $data['name'];
        $description= $data['description'];
        $ingredients=$data['ingredients'];
        // print_r ($data);

        $stm = $conn->prepare("select * from recipes where name = ?;");
        $stm->bind_param("s", $recipe_name);
        $stm->execute();
        $result = $stm->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["message" => "The item already exists with the name $recipe_name"]);
        } else {
            $stm = $conn->prepare("insert into recipes (user_id, name, description,ingredients) values (?, ?, ?,?)");
            $stm->bind_param("isss", $user_id, $recipe_name, $description,$ingredients);

            try {
                $stm->execute();
                echo json_encode(["message" => "Recipe added successfully", "status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["message" => $e->getMessage(), "status" => "failure"]);
            }
        }
    } else {
        echo json_encode(["message" => "Invalid input"]);
    }
} else {
    echo json_encode(["message" => "Wrong request method"]);
}
?>
