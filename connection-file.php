<?php

header('Content-Type: application/json'); // Ensure the content type is JSON
header('Access-Control-Allow-Origin: *'); // Allow all origins (adjust if needed)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific methods
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$servername="localhost";
$user="root";
$password="";
$db_name="recipe_managment-db";

$conn = new mysqli($servername,$user,$password,$db_name);

if ($conn->connect_error){
    echo json_encode(["message"=>"failed to connect"]);
}else{
    echo json_encode(["message"=>"connect succesfully"]);
}