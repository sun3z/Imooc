<?php 

require __DIR__ . '/vendor/autoload.php';

use Imooc\CourseInfo;
use Imooc\Download;



$courseInfo = new courseInfo();
$courseList = $courseInfo->getCoursePageList(1);
var_dump($courseList[17]);

$chapterList = $courseInfo->getAllChapter($courseList[17]['href']);
var_dump($chapterList);

$download = new Download();
$download->downloadAll($courseList[17]['title'], $chapterList);
