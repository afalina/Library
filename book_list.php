<?
require 'init.php';
?>
<body>
<?
menu();
?>
<h1>Список книг</h1>
<?
    to_do_list(book_list());
?>

</body>