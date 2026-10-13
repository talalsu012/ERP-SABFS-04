<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Report all PHP errors
error_reporting(E_ALL);

require __DIR__ . '/classes/Database.php';
require __DIR__.'/AuthMiddlewarePay.php';

$allHeaders = getallheaders();
// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$auth = new Auth($conn, $allHeaders,$data);

if ($_SERVER["REQUEST_METHOD"] != "GET") :
    exit(json_encode($auth->msg(0, 404, 'Page Not Found!')));
elseif (!isset($allHeaders['Stdid'])) :
    // var_dump($allHeaders);
    exit(json_encode($auth->msg(0, 422, 'Please Fill in all Required Fields!')));
else:
    echo json_encode($auth->isValid(1));
endif;