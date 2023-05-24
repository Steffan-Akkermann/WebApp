<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\UserModel;

class ProfileController
{
    public function showProfile(Request $request, Response $response, $args)
    {
        // Проверка куки в браузере
        $cookies = $request->getCookieParams();

        if (isset($cookies['user'])) {

            $username = $cookies['user'];
            // Кука установлена, отображаем страницу профиля
            $view = file_get_contents(__DIR__ . '/../View/profile.html');
            $view = str_replace('{{username}}', $username, $view);
            $response->getBody()->write($view);
            return $response;
        } else {
            // Кука не установлена, перенаправляем на форму авторизации
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    }


    public function logout(Request $request, Response $response)
    {
        // Удаление куки из браузера
        $response = $response->withHeader('Set-Cookie', 'user=; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT;');

        // Перенаправление на форму авторизации
        return $response->withHeader('Location', '/login');
    }

}