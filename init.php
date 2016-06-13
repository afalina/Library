<?
require '../configs/config.php';
require 'connection.php';

$db = new DBConnection();

function escape_html($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
}

function add_book($author, $title, $year, $book_file) {
    global $db;
    $query = $db->prepare('
        INSERT books SET
            author=:author,
            title=:title,
            published_year=:year
    ');
    $query->bindValue(':author', $author, PDO::PARAM_STR);
    $query->bindValue(':title', $title, PDO::PARAM_STR);
    $query->bindValue(':year', $year, PDO::PARAM_INT);
    $query->execute();
    upload_file($book_file);
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

    //получаем айдишник последней вставки
    $id = $db->lastInsertId();

    //первая часть запроса
    $sql = "INSERT INTO records VALUES ";

    //для хранения предложений сделаем массив, из него потом будут вставляться в подготовленный запрос строки
    $strings = array();

    foreach ($sentences[0] as $sentence) {
        $sentence = (trim($sentence));
        $strings[] = $sentence;
    }

    for ($i = 0; $i < count($strings); $i++) {
        $sql .= '('.$id.',?),';
    }

    $sql = rtrim($sql, ",");
    $sql .= ";";

    $query = $db->prepare($sql);
    $query->execute($strings);
}

function book_list() {
    global $db;
    $query = $db->prepare('SELECT author, title, published_year as year FROM books order by author ASC;');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function to_do_list($books) {
    $i = 1;
    styles();
    ?>
    <div class="content">
    <?
    foreach ($books as $book) {
        echo $i.'. '.$book['author'].' "'.$book['title'].'" '.$book['year'].'г. издательства<br>';
        $i++;
    }
    ?>
    </div>
    <?
}

function styles() {
    ?>
    <style>
        html, body {
            width: 90%;
            margin: 0 auto;
        }
        a {
            color:white;
            font-size: 20px;
        }

        div.menu {
            background: black;
            display: inline-block;
            width: 33%;
            height: 25px;
            text-align: center;
        }

        div.content {
            font-size: 20px;
        }
        div.nav {
            width: 100%;
        }

        input {
            border-radius: 20px 20px 20px 20px;
            margin-bottom: 10px;
        }

        form {
            background: #ddd;
            padding:30px;
        }


    </style>
    <?
}

function menu() {
    styles();
    ?>
    <div class="nav">
        <div class="menu"><a href="index.php">Добавить книгу</a></div>
        <div class="menu"><a href="book_list.php">Список книг</a></div>
        <div class="menu"><a href="#">Поиск по книгам</a></div>
    </div>
    <?
}
?>
<head><meta charset="utf8"></head>