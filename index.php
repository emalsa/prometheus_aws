<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . "/inc/bootstrap.php";

// Get request method
$method = $_SERVER['REQUEST_METHOD'] ?? NULL;
if ($method === 'POST') {
  echo "THIS IS A POST REQUEST\n";
  $receive = new ReceiveController();
  $receive->init();
  return;
}
// Process
$send = $argv[1] === 'aws-exec' ?? NULL;
if ($send) {
  $send = new SendController();
  $send->send();
  return;
}
echo "NO!!!\n";
