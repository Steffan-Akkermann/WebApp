<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controller\RegistrationController;
use App\Controller\LoginController;
use App\Controller\ProfileController;
use App\Model\UserModel;


return function (App $app) {

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', [LoginController::class, 'showLoginForm']);

    // $app->get('/db-test', function (Request $request, Response $response) {
    //     $db = $this->get(PDO::class);
    //     $sth = $db->prepare("SELECT * FROM users");
    //     $sth->execute();
    //     $data = $sth->fetchAll(PDO::FETCH_ASSOC);
    //     $payload = json_encode($data);
    //     $response->getBody()->write($payload);
    //     return $response->withHeader('Content-Type', 'application/json');
    // });


    $app->get('/registration', [RegistrationController::class, 'showRegistrationForm']);
    $app->post('/registration', [RegistrationController::class, 'register']);
    
    $app->get('/login', [LoginController::class, 'showLoginForm']);
    $app->post('/login', [LoginController::class, 'login']);

    $app->get('/profile', [ProfileController::class, 'showProfile']);
    $app->get('/logout', [ProfileController::class, 'logout']);

};