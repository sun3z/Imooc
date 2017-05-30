<?php 


require __DIR__ . '/vendor/autoload.php';

use phpQuery as phpQuery;
use GuzzleHttp\Client;
use Imooc\CourseInfo;
use Imooc\Download;




$client = new Client([
    'base_uri' => 'http://www.imooc.com'
]);

$response = $client->get('/course/list');
$body = $response->getBody()->getContents();

// 解析课程列表数据
PHPQuery::newDocumentHTML($body);
$courseList = [];
foreach(pq('.course-card') as $card) {
    $course['title'] = pq($card)->find('.course-card-content h3')->text();
    $course['info'] = pq($card)->find('.course-card-content p')->text();
    $course['href'] = pq($card)->attr('href');
    $infoTemp = explode('·', pq($card)->find('.course-card-info')->text());
    $course['rank'] = trim($infoTemp[0]);
    $course['num'] = trim($infoTemp[1]);
    $courseList[] = $course;
}

$courseInfo = new CourseInfo();

$info = $courseInfo->getAllChapter($courseList[4]['href']);
var_dump($courseList[4]);
var_dump($info);
var_dump($info[0]['chapter'][0]['title']);
var_dump($info[0]['chapter'][0]['href']);


// var_dump($courseInfo->parseDownloadLink($info[0]['chapter'][0]['href']));

// $courseInfo->parseDownloadLink(1);

$download = new Download();
// $download->downloadVideo(1);