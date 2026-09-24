<?php

class Controller
{
    /**
     * Carga una vista.
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("Vista no encontrada: " . $view);
        }

        extract($data);

        require $viewPath;
    }

    /**
     * Redirecciona a una ruta.
     *
     * @param string $url
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Devuelve una respuesta JSON.
     *
     * @param mixed $data
     * @return void
     */
    protected function json($data): void
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }
}