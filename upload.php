      <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="filename"><br> 
      <input type="submit" value="Загрузить"><br>
      </form>
<?php
   if($_FILES["filename"]["size"] > 1024*3*1024)
   {
     echo ("Размер файла превышает три мегабайта");
     exit;
   }
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     //$url = '/Users/afalina/Public/Library/books/';
     $url = '/var/www/html/books/';

    if(move_uploaded_file($_FILES["filename"]["tmp_name"], $url.basename($_FILES["filename"]["name"]))){
      echo 'Удалось переместить файл';
    } else {echo 'не удалось переместить файл';}
     echo 'Файл загружен в '.$url.basename($_FILES["filename"]["name"]);
   } else {
      echo 'Ошибка загрузки файла';
   }
?>