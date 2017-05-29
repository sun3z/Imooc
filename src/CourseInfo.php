<?php 
namespace Imooc;

use phpQuery as phpQuery;
use GuzzleHttp\Client;


class CourseInfo
{

    protected $client = null;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://m.imooc.com'
        ]);
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
     * 解析所有章节数据
     * @param  [type] $html [description]
     * @return [type]       [description]
     */
    protected function parseChapterInfo($html)
    {
        // 定义pq文档
        PHPQuery::newDocumentHTML($html);
        // 所有章节数据
        $chapterList = [];
        // 遍历所有章节列表
        foreach(pq('.course-chapter>li') as $chapter) {
            // 解析章节标题
            $info['title'] = pq($chapter)->find('h3')->text();
            $info['chapter'] = [];
            // 遍历
            foreach(pq($chapter)->find('ul>li') as $k => $section) {
                $sectionTemp['title'] =  pq($section)->find('a')->text();
                $sectionTemp['href'] = pq($section)->find('a')->attr('href');
                $info['chapter'][] = $sectionTemp;
            }
            $chapterList[] = $info;
        }
        return $chapterList;
    }
}