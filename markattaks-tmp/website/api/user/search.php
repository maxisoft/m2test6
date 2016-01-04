<?php
require_once 'autoload.php';



$session = new \website\utils\Session();

(isset($_GET['q']) and $session->isLogged()) or die();

$q = \website\db\Escape::escapeSQLLike($_GET['q']);

(strlen($q) >= 2) or die();

$q = $q . '%';

$input_parameters = ['q' => $q];
$where = '(first_name LIKE :q OR last_name LIKE :q)';

if (isset($_GET['role']) && \website\model\User::isValidRole($_GET['role'])) {
    $where .= ' AND role = :role';
    $input_parameters['role'] = $_GET['role'];
}

$result = \website\model\User::findWhere($where, $input_parameters, 10, ['id', 'first_name', 'last_name']);

$arrayResult = array_map(function ($e) {
    return $e->toArray();
}, $result);

header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

echo json_encode(['items' => $arrayResult]);
