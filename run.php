<?php 

require __DIR__ . '/vendor/autoload.php';

use Imooc\CourseInfo;
use Imooc\Download;

$courseInfo = new courseInfo();
$page = 1;

if($argc == 2) {
    if($argv[1] == 'all') {
        while(true) {
            echo "正在查询慕课网课程列表...\n";
            $courseList = $courseInfo->getCoursePageList('', $page);
            echo '正在搜索第' . $page++ . '页，共查询到' . count($courseList) . "个课程\n";
            foreach($courseList as $courseId => $course) {
                echo "title: {$course['title']}\n";
                echo "description: {$course['info']}\n";
                echo "courseId: {$courseId}\n\n";
            }
            echo "请输入要下载的courseId 或 直接回车查询下一页:\n";
            $courseId = trim(fgets(STDIN));
            if(is_numeric($courseId)) {
                break;
            }
        }
    } else {
        while(true) {
            echo "正在查询慕课网课程列表...\n";
            $courseList = $courseInfo->getCoursePageList($argv[1], $page);
            echo '正在搜索第' . $page++ . '页，共查询到' . count($courseList) . "个课程\n";
            foreach($courseList as $courseId => $course) {
                echo "title: {$course['title']}\n";
                echo "description: {$course['info']}\n";
                echo "courseId: {$courseId}\n\n";
            }
            echo "请输入要下载的courseId 或 直接回车查询下一页:\n";
            $courseId = trim(fgets(STDIN));
            if(is_numeric($courseId)) {
                break;
            }
        }
    }
}



echo "正在解析课程：{$courseId}\n";
$chapterList = $courseInfo->getAllChapter($courseList[$courseId]['href']);
echo $courseList[$courseId]['title'] . "\n";
foreach($chapterList as $chapter) {
    echo "--{$chapter['title']}\n";
    foreach($chapter['chapter'] as $section) {
        echo "----{$section['title']}\n";
    }
}

echo "请确认开始下载（Y/n）:\n";
$confirm = trim(fgets(STDIN));
if($confirm == 'Y') {
    $download = new Download();
    $download->downloadAll($courseList[$courseId]['title'], $chapterList);
}