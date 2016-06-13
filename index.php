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
    <br>автор <input name="author" required size="30">
    <br>название <input name="title" required size="30">
    <br>год издания <input name="year" required size="30">
    <br><input type="file" name="filename" required><br>
    <input type="submit" name="submit" value="New book">
</form>
  <br>
  </div>
</body>
<?php

?>