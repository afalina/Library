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
<form method="post" action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data">
    автор <input name="author" required size="30">
    название <input name="title" required size="30">
    год издания <input name="year" required size="30">
    <br><input type="file" name="filename"><br>
    <input type="submit" name="submit" value="New book">
  </form>
</body>
<?php

?>