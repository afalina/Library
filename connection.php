<?
class DBConnection extends PDO
{
    public function __construct()
    {
        parent::__construct('mysql:host=' . DATABASE_HOST .
                            ';dbname=' . DATABASE_NAME .
                            ';charset=' . DATABASE_CHARSET,
                            DATABASE_USER,
                            DATABASE_PASSWORD);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}
?>