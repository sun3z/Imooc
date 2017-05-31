<?php 
namespace Imooc;

use GuzzleHttp\Client;
use Imooc\CourseInfo;

/**
 * 下载课程视频
 */
class Download
{
    protected $client = null;
    protected $courseInfo = null;
    protected $downloadDir = '';

    public function __construct()
    {
        $this->downloadDir = __DIR__ . '/../videos/';
        $this->client = new Client();
        $this->courseInfo = new CourseInfo();
    }

    /**
     * 下载指定课程下的所有视频
     * @param  string $courseTitle
     * @param  array $chapterList
     * @return
     */
    public function downloadAll($courseTitle, $chapterList)
    {
        $videoDir = $this->downloadDir . $courseTitle .'/';
        if(!is_dir($videoDir)) {
            mkdir($videoDir, 0777, true);
        }
        
        foreach($chapterList as $chapter) {
            foreach($chapter['chapter'] as $value) {
                $videoFile = $videoDir . $value['title'] . '.mp4';
                $videoLink = $this->courseInfo->parseDownloadLink($value['link']);
                $this->downloadVideo($videoFile, $videoLink);
            }
        }
    }

    /**
     * 执行下载操作
     * @param  string $videoFile
     * @param  mixed $videoLink
     * @return
     */
    public function downloadVideo($videoFile, $videoLink)
    {
        if($videoLink !== false) {
            $fh = fopen($videoFile, 'w');
            $this->client->request('GET', $videoLink, ['sink' => $fh]);
        }  
    }
}
