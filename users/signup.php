<?php
require "../connection-file.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['name'], $data['email'], $data['password'])) {
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];

        $stm = $conn->prepare("select * from users where email = ?");
        $stm->bind_param("s", $email);
        $stm->execute();
        $result = $stm->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["message" => "Email already registered", "status" => "failure"]);
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stm = $conn->prepare("insert into users (name, email, password) value (?, ?, ?)");
            $stm->bind_param("sss", $name, $email, $hashed_password);

            try {
                $stm->execute();
                echo json_encode(["message" => "User registered successfully", "status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["message" => "cant registered", "status" => "failure"]);
            }
        }
    } else {
        echo json_encode(["message" => "Invalid input", "status" => "failure"]);
    }
} else {
    echo json_encode(["message" => "Wrong request method", "status" => "failure"]);
}
?>
