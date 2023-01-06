<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . "/inc/bootstrap.php";

// get request method
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
  echo "THIS IS A GET REQUEST";
  $a = 1;
}
if ($method == 'POST') {
  echo "THIS IS A POST REQUEST";
  $receive = new ReceiveController();
  $receive->init();
  return;
}
if ($method == 'PUT') {
  echo "THIS IS A PUT REQUEST";
}
if ($method == 'DELETE') {
  echo "THIS IS A DELETE REQUEST";
}


?>