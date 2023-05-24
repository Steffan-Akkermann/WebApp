<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\UserModel;

class RegistrationController
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function showRegistrationForm(Request $request, Response $response, $args)
    {
        // Отображение формы регистрации
        $view = file_get_contents(__DIR__ . '/../View/registration.html');
        $response->getBody()->write($view);
        return $response;
    }

    public function register(Request $request, Response $response, $args)
    {
        // Получаем данные из формы
        $data = $request->getParsedBody();
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];

        var_dump($data);

        // Проверка на валидность логина, пароля и email
        if (strlen($username) < 2 || strlen($username) > 20) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid username length']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        if (strlen($password) < 5 || !preg_match('/[^\d]/', $password)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid password']));
            return $response->withHeader('Content-Type', 'application/json');
        }        

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid email']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // Вызываем метод модели для сохранения данных регистрации
        $isRegistered = $this->userModel->registerUser($username, $password, $email);

        if ($isRegistered) {
            // Возвращаем ответ
            $response->getBody()->write(json_encode(['success' => true, 'message' => 'Registration successful']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // Возвращаем сообщение об ошибке
            $response->getBody()->write(json_encode(['success' => false, 'message' => 'Registration failed']));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
