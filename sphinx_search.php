<?
require 'init.php';

    $result_set = searched_by_text_sphinx($_POST['search_text']);
    if ($result_set) founded_list($result_set);
    else echo 'К сожалению, ничего не найдено!';
?>