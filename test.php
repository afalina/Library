<?php
require 'config.php';

$db = new mysqli('127.0.0.1', 'root', '4444', 'library');
$db->set_charset('utf8');
if ($db->connect_errno) {
    echo "can't connect to mysql";
} else {
    echo "connected to mysql!";
}

//получаем текст книги в переменную
$txt_file = file_get_contents('books/Стивен_Кинг-Зеленая_миля-1996.txt');

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
echo $sql;

$result = mysqli_query($db, $sql);
if (!$result) die("Error saving");
//echo $sql;

?>