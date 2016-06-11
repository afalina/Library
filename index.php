<?php
require 'config.php';
$db = new mysqli('127.0.0.1', 'root', '4444', 'library');
$db->set_charset('utf8');
if ($db->connect_errno) {
    echo "can't connect to mysql";
}

if (filter_input(INPUT_POST, 'submit')) {
    $year = intval($_POST['year']);
    echo $year.'<br>';
    $title = $_POST['title'];
    echo $title.'<br>';
    $author = $_POST['author'];
    echo $author.'<br>';
    $sql = "INSERT INTO books (`author`,`title`, `published_year`) VALUES ('$author', '$title', '$year');";
    $result = mysqli_query($db, $sql);
    if (!$result) die("Error saving");

    if($_FILES["filename"]["size"] > 1024*5*1024) {
        echo ("Размер файла превышает 5 мегабайта");
        exit;
    }
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     $url = '/var/www/books/';

        move_uploaded_file($_FILES["filename"]["tmp_name"], $url.($_FILES["filename"]["name"]));

        //открываем для разбивания
        $txt_file = file_get_contents($url.($_FILES["filename"]["name"]));

        $sentences = [];
        //с помощью регулярных выражений разбиваем текст на отдельные предложения
        //разделители: точка, !, ?, начало строки, конец строки
        preg_match_all("/[^.!?\r\n]+[.!?\r\n]+/", $txt_file, $sentences);

        $id = $db->insert_id;

        $insertSql = "INSERT INTO records VALUES ";

        foreach ($sentences[0] as $sentence) {
            $sentence = $db->escape_string(trim($sentence));
            $insertSql .= "(" . $id . ", '" . $sentence . "'),"; 
        }
        $insertSql = rtrim($insertSql, ",");
        $insertSql .= ";";

        $result = mysqli_query($db, $insertSql);
        if (!$result) die("Ошибка сохранения предложений");

   } else {
      echo("Ошибка загрузки файла");
   }

}

?>
<form method="post" action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data">
    автор <input name="author" required size="30">
    название <input name="title" required size="30">
    год издания <input name="year" required size="30">
    <br><input type="file" name="filename"><br>
    <input type="submit" name="submit" value="New book">
  </form>
<?php

?>