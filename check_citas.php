<?php
require_once 'config/Database.php';
$db = Database::getInstance()->getConnection();
print_r($db->query('DESCRIBE calificaciones')->fetchAll(PDO::FETCH_ASSOC));
?>
