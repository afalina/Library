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
    $sql = "INSERT INTO records(book_id, record) VALUES ";

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
    if ($query) success_adding();
}

function success_adding() {
    ?>
    <script>
        alert("Книга успешно добавлена!");
    </script>
    <?
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
    <div id="content" class="content">
    <?
    foreach ($books as $book) {
        echo $i.'. '.$book['author'].' "'.$book['title'].'" '.$book['year'].'г. издательства<br>';
        $i++;
    }
    ?>
    </div>
    <?
}

function founded_list($list) {
    $i = 1;
    styles();
    ?>
    <div id="content" class="found-content">
        <?
        foreach ($list as $item) {
            echo $i.'. '.$item['author'].' "'.$item['title'].'" '.$item['published_year'].'г. издательства<br>';
            echo $item['record'].'<br><br><hr>';
            $i++;
        }
        ?>
    </div>
    <?
}

function styles() {
    ?>
    <style>
        html {
            background: url(background.jpg);
        }
        body {
            width: 90%;
            color: white;
            --background: rgba(255, 255, 255, 0.7);
            margin: 0 auto;
            font-family: Arial;
        }
        a {
            color:white;
            font-size: 20px;
        }

        div.menu {
            background: black;
            display: inline-block;
            width: 33%;
            height: 35px;
            text-align: center;
            padding-top: 10px;
        }

        div.content {
            font-size: 20px;
        }
        div.nav {
            width: 100%;
        }
        div.found-content {
            font-size: 10pt;
        }

        input {
            border-radius: 20px 20px 20px 20px;
            margin-bottom: 10px;
            font-size: 20px;
        }

        input.submit-button {
            font-size: 30px;
            border-radius: 10px;
            margin-top: 40px;
        }

        .content {
            background: rgba(0,0,0,0.9);
            padding:30px;
        }


    </style>
    <?
}

function menu() {
    styles();
    ?>
    <div class="nav">
        <a href="index.php"><div class="menu">Добавить книгу</div></a>
        <a href="book_list.php"><div class="menu">Список книг</div></a>
        <a href="#"><div class="menu">Поиск по книгам</div></a>
    </div>
    <?
}


function searched_by_text_mysql($text) {
    global $db;
    $query = $db->prepare('SELECT 
        records.record AS record,
        books.author AS author, 
        books.title AS title,
        books.published_year AS published_year 
        FROM records 
        INNER JOIN books ON records.book_id=books.id
        WHERE records.record LIKE ?;');
    $query->bindValue(1, "%$text%", PDO::PARAM_STR);
    $start_time = microtime(true);
    $query->execute();
    $executing_time = microtime(true) - $start_time;
    echo '<p style="font-size:7pt;">Время выполнения запроса: '.$executing_time.' ms</p>';
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<head><meta charset="utf8"></head>