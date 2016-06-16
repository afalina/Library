<?
require 'init.php';
class SphinxConnection extends PDO{
        public function __construct() {
            parent::__construct('mysql:host=' . '127.0.0.1' .
                                ';port=' . '9306' .
                                ';charset=' . DATABASE_CHARSET,
                                DATABASE_USER,
                                DATABASE_PASSWORD);
        }
}

//$sphinxConn = new PDO('mysql:host=127.0.0.1;port=9306;charset=utf8', 'reader', 'bwtlibrary2016');

//$stmt = $sphinxConn->query("SELECT * FROM test1 WHERE MATCH("привет, Фродо")");

//print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

?>