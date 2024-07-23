<?php

require "../connection-file.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data["name"])) {
        $recipe_name=$data["name"];
        // print_r ($data);
    $stm=$conn->prepare("select * from recipes where name=?");
    $stm->bind_param("s",$recipe_name);
    $stm->execute();
    $result=$stm->get_result();
    if ( $result->num_rows>0){
        $stm=$conn->prepare("delete from recipes where name=?");
        $stm->bind_param("s",$recipe_name);
        try {
            $stm->execute();
            echo json_encode(["message" => "product got deleted", "status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $stmt->error, "status" => "failure"]);
        }
    }
    else{
        echo json_encode(["message"=>"item not found"]);
    }
}
else{
    echo json_encode(["message"=>"cannot take this input"]);
}

}else{
    echo json_encode(["message"=>"wrong request method"]);
}

