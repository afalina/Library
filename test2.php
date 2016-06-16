<?php

$s = new SphinxClient;
$s->setServer("localhost", 6712);
$s->setMatchMode(SPH_MATCH_ANY);
$s->setMaxQueryTime(3);

$result = $s->query("test");

var_dump($result);

?>