<?php

require "../connection-file.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['recipe_id'])) {
        $recipe_id = $_POST['recipe_id'];

        // Debug: Print recipe_id
        error_log("Received recipe_id: " . $recipe_id);

        // Fetch recipe details
        $recipe_stmt = $conn->prepare('SELECT r.*, u.name as user_name, u.email as user_email 
                                       FROM recipes r 
                                       JOIN users u ON r.user_id = u.id 
                                       WHERE r.id = ?');
        if ($recipe_stmt === false) {
            error_log("Error preparing recipe query: " . $conn->error);
            echo json_encode(["message" => "Error preparing recipe query", "status" => "failure"]);
            exit();
        }
        
        $recipe_stmt->bind_param("i", $recipe_id);
        $recipe_stmt->execute();
        $recipe_result = $recipe_stmt->get_result();

        if ($recipe_result->num_rows > 0) {
            $recipe = $recipe_result->fetch_assoc();

            // Fetch comments related to the recipe
            $comments_stmt = $conn->prepare('SELECT c.*, u.name as user_name, u.email as user_email 
                                             FROM comments c 
                                             JOIN users u ON c.user_id = u.id 
                                             WHERE c.recipe_id = ?');
            if ($comments_stmt === false) {
                error_log("Error preparing comments query: " . $conn->error);
                echo json_encode(["message" => "Error preparing comments query", "status" => "failure"]);
                exit();
            }

            $comments_stmt->bind_param("i", $recipe_id);
            $comments_stmt->execute();
            $comments_result = $comments_stmt->get_result();
            $comments = [];

            if ($comments_result->num_rows > 0) {
                while ($comment_row = $comments_result->fetch_assoc()) {
                    $comments[] = $comment_row;
                }
            }

            // Include comments in the recipe details
            $recipe['comments'] = $comments;

            echo json_encode(["recipe" => $recipe, "status" => "success"]);
        } else {
            echo json_encode(["message" => "Recipe not found", "status" => "failure"]);
        }
    } else {
        echo json_encode(["message" => "recipe_id not  hello provided", "status" => "failure"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method", "status" => "failure"]);
}
?>
