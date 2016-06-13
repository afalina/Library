<?
require '../configs/config.php';
//соединение с базой данных
$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
$db->set_charset(DATABASE_CHARSET);
if ($db->connect_errno) {
    echo "can't connect to mysql";
} else {
    echo "connected";
}

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
    if ($book_file["size"] > 1024*5*1024) {
        echo ("Размер файла превышает 5 мегабайта");
        exit;
    }
    // Проверяем загружен ли файл
    if (is_uploaded_file($_FILES["filename"]["tmp_name"])){
    // Если файл загружен успешно, перемещаем его
    // из временной директории в конечную
        move_uploaded_file($_FILES["filename"]["tmp_name"], BOOK_PATH.($_FILES["filename"]["name"]));
    }
    parse_file($book_file);
}

function parse_file($book_file) {
    global $db;
    //открываем для разбивания
    $txt_file = file_get_contents(BOOK_PATH.($_FILES["filename"]["name"]));

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