<?php
require 'init.php';
if (filter_input(INPUT_POST, 'submit')) {
  add_book($_POST['author'], $_POST['title'], $_POST['year'], $_FILES["filename"]);
}
?>

<head>
  <meta charset="utf8">
</head>
<body>
<?
menu();
?>
<h1>Добавьте книгу</h1>
<div class="content">
<form method="post" action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data">
  <table>
    <tr><td>Автор</td><td><input name="author" required size="30"></td></tr>
    <tr><td>Название</td><td><input name="title" required size="30"></td></tr>
    <tr><td>Год издания</td><td><input name="year" required size="30"></td></tr>
    <tr>
      <td>Выберите файл с книгой</td>
      <td><input type="file" name="filename" required accept=".txt"></td>
    </tr>
  </table>
  <input class="submit-button" type="submit" name="submit" value="Добавить книгу">
</form>
  <br>
  </div>
</body>