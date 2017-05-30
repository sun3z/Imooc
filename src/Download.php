<?php 
namespace Imooc;

use GuzzleHttp\Client;
use Imooc\CourseInfo;

class Download
{
    protected $client = null;
    protected $downloadDir = '';

    public function __construct()
    {
        $this->downloadDir = __DIR__ . '/../videos/';
        $this->client = new Client();
        $this->courseInfo = new CourseInfo();
    }

    public function downloadAll($courseTitle, $chapterList)
    {
        $videoDir = $this->downloadDir . $courseTitle .'/';
        if(!is_dir($videoDir)) {
            mkdir($videoDir, 0777, true);
        }
        
        foreach($chapterList as $chapter) {
            foreach($chapter['chapter'] as $value) {
                $videoFile = $videoDir . $value['title'] . '.mp4';
                $videoLink = $value['link'];
                $this->downloadVideo($videoFile, $videoLink);
            }
        }
    }

    public function downloadVideo($videoFile, $videoLink)
    {
        $this->client->request('GET', $videoLink, ['sink' => $videoFile]);
    }

}
