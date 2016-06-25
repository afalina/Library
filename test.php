<?
require 'init.php';
$query = $sphinxConnection->prepare('SELECT * FROM test1');
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
?>