<?php
require "../connection-file.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email'], $data['password'])) {
        $email = $data['email'];
        $password = $data['password'];

        $stm = $conn->prepare("select * from users where email = ?");
        $stm->bind_param("s", $email);
        $stm->execute();
        $result = $stm->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo json_encode(["message" => "Login successful", "status" => "success"]);
            } else {
                echo json_encode(["message" => "Invalid email or password", "status" => "failure"]);
            }
        } else {
            echo json_encode(["message" => "Email not registered", "status" => "failure"]);
        }
    } else {
        echo json_encode(["message" => "Invalid input", "status" => "failure"]);
    }
} else {
    echo json_encode(["message" => "Wrong request method", "status" => "failure"]);
}
?>
