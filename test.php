<?php
require 'config.php';

$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
$db->set_charset(DATABASE_CHARSET);
if ($db->connect-errno) {
    echo "can't connect to mysql";
}

//получаем текст книги в переменную
$txt_file = file_get_contents('books/Стивен_Кинг-Зеленая_миля-1996.txt');
echo $txt_file;

$sentences = [];
//с помощью регулярных выражений разбиваем текст на отдельные предложения
//разделители: точка, !, ?, начало строки, конец строки
preg_match_all("/[^.!?\r\n]+[.!?\r\n]+/", $txt_file, $sentences);
$id = 1;
$sql = 'INSERT INTO records VALUES ';

foreach ($sentences[0] as $sentence) {
    $sentence = $db->escape_string(trim($sentence));
    $sql .= '(' . $id . ', "' . $sentence . '"),'; 
    //echo $sentence.'<br>';
}
$sql = rtrim($sql, ",");
$sql .= ';';

$result = mysqli_query($db, $sql);
if (!$result) die("Error saving");
//echo $sql;

?>