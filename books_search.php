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
        <input size="70" name="search_text" id="search-mysql" placeholder="Введите предложение или его часть">
        <input class="submit-button active" type="submit" id="mysql" value="Mysql">
        <input class="submit-button" type="submit" id="sphinx" disabled="true" value="Sphinx">
        <input class="submit-button" type="submit" id="lucene" disabled="true" value="Lucene">
        <input class="submit-button" type="submit" id="elastic" disabled="true" value="Elastic">
    </form>
    <div class="content" id="content">
        <p>Здесь будут результаты поиска
    </div>
    </div>

<script type="text/javascript">
/*mysql.onclick = (function mysql_change() {
    document.getElementsByName('text-search')[0].id='search-mysql';
    document.getElementById('mysql').class+= ' active'; 
});*/


$(function(){
  $("#search-mysql").keyup(function(){
     var search = $("#search-mysql").val();
     $.ajax({
       type: "POST",
       url: "search.php",
       data: {"search": search},
       cache: false,                                
       success: function(response){
          $("#content").html(response);
       }
     });
     return false;
   });
});
</script>

</body>