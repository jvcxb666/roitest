<?php

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use App\Api;

$api = new Api('yakoffswidersky');
$data = $_POST['data'];
$_SESSION['last'] = $data;

$result = $api->addLead($data);

if(!empty($result)){
    echo json_encode(true);
}else{
    echo json_encode(false);
}

