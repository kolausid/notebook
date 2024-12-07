<?php
require '../../includes/connection.php';
require 'notebook.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

$notebook = new Notebook($mysqli);

switch ($method) {
    case 'GET':
        if (preg_match('/^notebook\/?$/', $path)) {
            $notebook->getAll();
        } elseif (preg_match('/^notebook\/(\d+)\/?$/', $path, $matches)) {
            $notebook->getById($matches[1]);
        }
        break;

    case 'POST':
        if (preg_match('/^notebook\/?$/', $path)) {
            $notebook->create();
        } elseif (preg_match('/^notebook\/(\d+)\/?$/', $path, $matches)) {
            $notebook->update($matches[1]);
        }
        break;

    case 'DELETE':
        if (preg_match('/^notebook\/(\d+)\/?$/', $path, $matches)) {
            $notebook->delete($matches[1]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
