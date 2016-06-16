<?
class SphinxConnection extends PDO{
        public function __construct() {
            parent::__construct('mysql:host=' . '127.0.0.1' .
                                ';port=' . '9306' .
                                ';charset=' . DATABASE_CHARSET,
                                DATABASE_USER,
                                DATABASE_PASSWORD);
        }
}
?>