<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\UserModel;

class LoginController
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function showLoginForm(Request $request, Response $response, $args)
    {

        $cookies = $request->getCookieParams();

        if (isset($cookies['user'])) {
            return $response->withHeader('Location', '/profile')->withStatus(302);
        }
        
        $view = file_get_contents(__DIR__ . '/../View/login.html');
        $response->getBody()->write($view);
        return $response;
    }

    public function login(Request $request, Response $response, $args)
    {
        // Получаем данные из формы
        $data = $request->getParsedBody();
        $username = $data['username'];
        $password = $data['password'];

        // Проверка на валидность логина и пароля
        if (strlen($username) < 2 || strlen($username) > 20) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid username length']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        if (strlen($password) < 5 || ctype_digit($password)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid password']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // Вызываем метод модели для проверки авторизационных данных
        $isAuthenticated = $this->userModel->checkCredentials($username, $password);

        if ($isAuthenticated) {
            // Установка куки в браузере
            $response = $response->withHeader('Set-Cookie', 'user=' . $username);

            // Возвращаем ответ
            $response->getBody()->write(json_encode(['success' => true, 'message' => 'Login successful']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // Возвращаем сообщение об ошибке
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid username or password']));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
