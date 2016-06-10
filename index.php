<?php
require 'config.php';
$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
$db->set_charset(DATABASE_CHARSET);
if ($mysqli->connect-errno) {
    echo "can't connect to mysql";
} else {
    echo "connected to mysql!";
}


if ($_POST['submit']) {
    $year = intval($_POST['year']);
    $title = $_POST['title'];
    $author = $_POST['author'];
    if ($year > 0) {
      $sql = "INSERT INTO books (`author`,`title`, `published_year`) 
              VALUES ('$author', '$title', '$year');";
      $result = mysqli_query($db, $sql);
    }
    if (!$result) die("Error saving");

    /*if($_FILES["filename"]["size"] > 1024*5*1024) {
        echo ("Размер файла превышает 5 мегабайта");
        exit;
    }*/
   // Проверяем загружен ли файл
   /*if(is_uploaded_file($_FILES["filename"]["tmp_name"])) {
        // Если файл загружен успешно, перемещаем его
        // из временной директории в конечную
        move_uploaded_file($_FILES["filename"]["tmp_name"], "books/".$_FILES["filename"]["name"]);

        //открываем для разбивания
        $txt_file = file_get_contents('books/'.$_FILES["filename"]["name"]);
        $sentences = [];
        //с помощью регулярных выражений разбиваем текст на отдельные предложения
        //разделители: точка, !, ?, начало строки, конец строки
        preg_match_all("/[^.!?\r\n]+[.!?\r\n]+/", $txt_file, $sentences);

        $id = $db->insert_id;
        $insertSql = 'INSERT INTO records VALUES ';

        foreach ($sentences[0] as $sentence) {
            $sentence = $db->escape_string(trim($sentence));
            $insertSql .= '(' . $id . ', "' . $sentence . '"),';
        }
        $insertSql = rtrim($insertSql, ",");
        $insertSql .= ';';

        $result = mysqli_query($db, $insertSql);
        if (!$result) die("Error saving");
   } else {
      echo("Ошибка загрузки файла");
   }*/

}

?>
<form method="post" action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data">
    автор <input name="author" required size="30">
    название <input name="title" required size="30">
    год издания <input name="year" required size="30">
    <!--<br><input type="file" name="filename"><br> -->
    <input type="submit" name="submit" value="New book">
  </form>
<?php

?>