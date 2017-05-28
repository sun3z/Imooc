<?php 


require __DIR__ . '/vendor/autoload.php';

use phpQuery as phpQuery;
use GuzzleHttp\Client;



$client = new Client([
    'base_uri' => 'http://www.imooc.com'
]);

$response = $client->get('/course/list');
$body = $response->getBody()->getContents();

// echo $body;
PHPQuery::newDocumentHTML($body);
foreach(pq('.course-card') as $card) {
    $course['title'] = pq($card)->find('.course-card-content h3')->text();
    $course['info'] = pq($card)->find('.course-card-content p')->text();
    $course['href'] = pq($card)->attr('href');
    $infoTemp = explode('·', pq($card)->find('.course-card-info')->text());
    $course['rank'] = trim($infoTemp[0]);
    $course['num'] = trim($infoTemp[1]);

    var_dump($course);

}

// foreach($cards as $card) {
//    echo $card;
// }