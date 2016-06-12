<?
//соединение с базой данных
    //проверка на глобальном серваке
$db = new mysqli('127.0.0.1', 'root', '4444', 'library');
    //проверка на локальном серваке
//$db = new mysqli('127.0.0.1', 'root', 'sa', 'library');
$db->set_charset('utf8');

if ($db->connect_errno) {
    echo "can't connect to mysql";
} else {
    echo "connected";
}

$url = '/var/www/books/'; //путь для хранения книг для глобального сервака
//$url = '/Users/afalina/Public/books/'; //путь для хранения книг для локального сервака

function escape_html($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
}

function add_book($author, $title, $year, $book_file) {
    global $db;
    $sql = "INSERT INTO books (`author`,`title`, `published_year`) VALUES ('$author', '$title', '$year');";
    $result = mysqli_query($db, $sql);
    if (!$result) {
        die("Error saving");
    } else {
        upload_file($book_file);
    }
}

function upload_file($book_file) {
    global $url;
    if ($book_file["size"] > 1024*5*1024) {
        echo ("Размер файла превышает 5 мегабайта");
        exit;
    }
    // Проверяем загружен ли файл
    if (is_uploaded_file($_FILES["filename"]["tmp_name"])){
    // Если файл загружен успешно, перемещаем его
    // из временной директории в конечную
        move_uploaded_file($_FILES["filename"]["tmp_name"], $url.($_FILES["filename"]["name"]));
    }
    parse_file($book_file);
}

function parse_file($book_file) {
    global $db;
    global $url;
    //открываем для разбивания
    $txt_file = file_get_contents($url.($_FILES["filename"]["name"]));

    $sentences = [];
        //с помощью регулярных выражений разбиваем текст на отдельные предложения
        //разделители: точка, !, ?, начало строки, конец строки
    preg_match_all("/[^.!?\r\n]+[.!?\r\n]+/", $txt_file, $sentences);

    $id = $db->insert_id;

    $sql = "INSERT INTO records VALUES ";

    foreach ($sentences[0] as $sentence) {
        $sentence = $db->escape_string(trim($sentence));
        $sql .= "(" . $id . ", '" . $sentence . "'),"; 
    }
    $sql = rtrim($sql, ",");
    $sql .= ";";

    $result = mysqli_query($db, $sql);
    if (!$result) {
        die("Ошибка сохранения предложений");
    }
}
?>
<head><meta charset="utf8"></head>