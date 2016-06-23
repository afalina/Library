<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
require 'init.php';
$client = ClientBuilder::create()->build();
global $db;
$query = $db->prepare('SELECT 
        records.record AS record,
        books.author AS author, 
        books.title AS title,
        books.published_year AS published_year 
        FROM records 
        INNER JOIN books ON records.book_id=books.id');
$query->execute();
print_r($query->fetchAll(PDO::FETCH_ASSOC));
/*while ($result = $query->fetch(PDO::FETCH_ASSOC)){
$params = [
    'index' => 'searching',
    'type' => 'search',
    'body' => [
	'record' => $result
    ]
];
}

$params = [
    'index' => 'searching',
    'type' => 'search',
    'body' => [
	'query' => [
    	    'match' => [
                'record' => 'привет'
            ]
        ]
    ]
];
                                                                        
$response = $client->search($params);
print_r($response);
*/
?>