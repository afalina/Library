<?
require 'init.php';
menu();
?>
<head>
  <meta charset="utf8">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
</head>

<body>
<h1>Поиск по книгам</h1>
    <div class="content">
    <form method="post" id="search-form" action="">
        <input type="radio" name="tumbler" id="mysql" checked="true" value="Mysql" onclick="php_search_script = 'search.php';">Mysql
        <input type="radio" name="tumbler" id="sphinx" value="Sphinx" onclick="php_search_script = 'sphinx_search.php';">Sphinx
        <input type="radio" name="tumbler" id="lucene" disabled="true">Lucene
        <input type="radio" name="tumbler" id="elastic" disabled="true">Elastic
        <br>
        <input size="100%" name="search_text" id="search" placeholder="Введите предложение или его часть">
    </form>
    <div class="content" id="content">
        <p>Здесь будут результаты поиска
    </div>
    </div>

<script type="text/javascript">

php_search_script = "search.php";
$(function(){
  $("#search").on('input', function(){
     var search = $("#search").val();
     $.ajax({
       type: "POST",
       url: php_search_script,
       data: {"search": search},
       success: function(response){
          $("#content").html(response);
       }
     });
   });
});
</script>

</body>