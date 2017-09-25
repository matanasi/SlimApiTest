<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "";
$config['db']['dbname'] = "mr2";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container["logger"] = function($c){
    $logger = new \Monolog\Logger("my_logger");
    $file_handler = new \Monolog\Handler\StreamHandler("log/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container["db"] = function($c){
    $db = $c["settings"]["db"];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/breedStats/{type}/{subtype}', function (Request $request, Response $response) {
//    $this->logger->addInfo("Prueba \n");
    
    $type = $request->getAttribute('type');
    $subtype = $request->getAttribute('subtype');
    
    $mrstats = new MonsterStats($this->db);
    $stats = ["stats" => []];
    $stats["stats"] = $mrstats->getMonsterStat($type, $subtype);
    
    $response->getBody()->write(json_encode($stats));

    return $response;
});
$app->run();