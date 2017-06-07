<?php 

require __DIR__ . '/vendor/autoload.php';

use Imooc\CourseInfo;
use Imooc\Download;

$courseInfo = new courseInfo();

// 分类、页码、慕课课程id
$c = '';
$page = 1;
$learn = 0;


// 引导下载程序
if ($argc == 2) {
    // 判断是指定下载某课程，还是借助命令行工具查询
    if (is_numeric($argv[1])) {
        $learn = intval($argv[1]);
    } else {
        $c = $argv[1];
    }
}

// 执行查询操作
if ($c != '' || $argc == 1) {
    while (true) {
        echo "正在查询慕课网课程列表...\n";
        $courseList = $courseInfo->getCoursePageList($c, $page);
        echo '正在搜索第' . $page++ . '页，共查询到' . count($courseList) . "个课程\n";
        foreach ($courseList as $courseId => $course) {
            echo "title: {$course['title']}\n";
            echo "description: {$course['info']}\n";
            echo "courseId: {$courseId}\n\n";
        }
        echo "请输入要下载的courseId 或 直接回车查询下一页:\n";
        $courseId = trim(fgets(STDIN));
        if (is_numeric($courseId)) {
            break;
        }
    }
}


// 构造下载链接 
if ($learn !== 0) {
    $courseLink = "/learn/{$learn}";
} else {
    $courseLink = $courseList[$courseId]['href'];
}


echo "正在解析课程：{$courseLink}\n";
$chapterList = $courseInfo->getAllChapter($courseLink);
if (isset($courseList)) {
    echo $courseList[$courseId]['title'] . "\n";
}
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