<?php 
namespace Imooc;

use phpQuery as phpQuery;
use GuzzleHttp\Client;

/**
 * 解析课程详情信息
 */
class CourseInfo
{

    protected $listClient = null;
    protected $client = null;

    public function __construct()
    {
        $this->listClient = new Client(['base_uri' => 'http://www.imooc.com']);
        $this->client = new Client([
            'base_uri' => 'http://m.imooc.com',
        ]);
    }

    /**
     * 获取指定课程列表页面课程详情
     * @param  integer $page 
     * @return array
     */
    public function getCoursePageList($c='', $page=1)
    {
        $reponse = $this->listClient->get('/course/list?' . http_build_query(['c' => $c, 'page' => $page]));
        return $this->parseCoursePageInfo($reponse->getBody()->getContents());
    }

    /**
     * 解析指定课程列表页课程详情
     * @return array
     */
    protected function parseCoursePageInfo($body)
    {
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
        return $courseList;
    }


    /**
     * 获取指定课程详情页下的所有章节数据
     * @param  string $learnUrl 
     * @return array
     */
    public function getAllChapter($learnUrl)
    {
        $reponse = $this->client->get($learnUrl);
        return $this->parseChapterInfo($reponse->getBody()->getContents());
    }


    /**
     * 解析指定课程所有章节数据
     * @param  string $html 
     * @return array
     */
    protected function parseChapterInfo($html)
    {
        // 定义pq文档
        PHPQuery::newDocumentHTML($html);
        // 所有章节数据
        $chapterList = [];
        // 遍历所有章节列表
        foreach(pq('.course-chapter>li') as $chapter) {
            $info['title'] = pq($chapter)->find('h3')->text();
            $info['chapter'] = [];
            // 遍历章节下的课程
            foreach(pq($chapter)->find('ul>li') as $k => $section) {
                $sectionTemp['title'] = pq($section)->find('a')->text();
                $sectionTemp['link'] = pq($section)->find('a')->attr('href');
                $info['chapter'][] = $sectionTemp;
            }
            $chapterList[] = $info;
        }
        return $chapterList;
    }


    /**
     * 解析指定课程页面视频的下载链接
     * @param  string $videoUrl
     * @return mixed 
     */
    public function parseDownloadLink($videoUrl)
    {
        if(preg_match('/\\/video\\/\d+/', $videoUrl)) {
            $clientTemp = new Client([
                'base_uri' => 'http://m.imooc.com',
                'cookies' => true
            ]);
            $reponse = $clientTemp->get($videoUrl);
            $reponse = $clientTemp->get($videoUrl);
            $html = $reponse->getBody()->getContents();
            preg_match_all('/course\.videoUrl="(.*?)"/', $html, $video);
            if(!empty($video[1][0])) {
                return $video[1][0];
            } else {
                file_put_contents('error.log', $html);
            }
            
        } else {
            echo $videoUrl . "\n";
            return false;
        } 
    }
}