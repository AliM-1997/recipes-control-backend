<?php

require '../connection-file.php';
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset( $data['name'], $data['description'],$data["ingredients"])) {
        $recipe_name = $data['name'];
        $description= $data['description'];
        $ingredients=$data['ingredients'];
        // print_r ($data);

    if ($description !="" && $ingredients !=""){   
    $stm=$conn->prepare("update recipes Set description = ?, ingredients=? WHERE name = ?");
    $stm->bind_param("sss",$description,$ingredients,$recipe_name);
    try {
        $stm->execute();
        echo json_encode(["message"=>"the recipe is updated","status"=>"success"]);
    } catch (Exception $e) {
        echo json_encode(["message"=>"cant update the recipe","status"=>"failure"]);
    }
    }else{
    echo json_encode(["message"=>"can't update with empty value"]);
    }   
    }
    else{
        echo json_encode(["message" => "Invalid input"]);
    }
}else{
        echo json_encode(["message"=>"wrong request method"]);
    }