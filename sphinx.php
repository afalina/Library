<?
require 'init.php';

$sphinxConn = new PDO('mysql:host=127.0.0.1;port=9306;charset=utf8', 'reader', 'bwtlibrary2016');

$stmt = $sphinxConn->query("SELECT * FROM test1");

print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

?>