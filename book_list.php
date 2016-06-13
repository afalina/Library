<?
require 'init.php';
?>
<head>
    <meta charset="utf-8">
</head>

<body>
<?
menu();
?>
<h1>Список книг</h1>
<?
    to_do_list(book_list());
?>

</body>