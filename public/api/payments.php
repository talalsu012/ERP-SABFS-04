<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/classes/Database.php';
require __DIR__.'/AuthMiddlewarePay.php'; 

$allHeaders = getallheaders();
// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];


$db_connection = new Database();
$conn = $db_connection->dbConnection();

$auth = new Auth($conn, $allHeaders,$data);

if ($_SERVER["REQUEST_METHOD"] != "POST") :
    echo json_encode($auth->msg(0, 404, 'Page Not Found!'));
else:
    echo json_encode($auth->isValid(2));
endif;