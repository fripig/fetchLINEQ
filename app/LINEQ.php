<?php
/**
 * Created by PhpStorm.
 * User: fripig
 * Date: 2017/5/2
 * Time: 14:37
 */

namespace App;

use phpQuery;

class LINEQ
{
    protected $GCS;
    public function __construct(GCSFile $GCS)
    {
        $this->GCS = $GCS;
    }

    public function fetchQ($id)
    {
        $url = 'http://lineq.tw';
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url.'/q/'.$id);
        $html = phpQuery::newDocument($res->getBody());

        $title = pq('h1')->text();

        $author = [];
        $author['photo'] = pq('.question_header .header_photo img')->attr('src');
        $author['name'] = trim(pq('.question_header .header_name')->text());
        $author['time'] = pq('.question_header .header_time')->text();

        if($img = $this->GCS->fetchImg($author['photo'])){
            var_dump($img);
            $this->GCS->write($author['photo']);

        }



        $question = [];
        $question['completed']  = pq('.question_completed')->text();
        $question['point'] = pq('.question_point')->text();
        $question['content'] = pq('.question_content')->html();

        $question['relations'] =[];
        foreach(pq('.question_relations a') as $item)
        {
            $question['relations'][] = [
                'url' => pq($item)->attr('href'),
                'category' => pq($item)->text(),
            ];
        }

        $question['as_metoo'] = pq('#questionList > div > div.in_card_middle > div > div.question_footer._footer > div > a > b')->text();

        $answer = [];
        $answer['band'] = [];

        foreach(pq('.content_reply_featured .reply') as $band)
        {
            $row = $this->getAnswer($band);
            $answer['band'][] = $row;
        }

        $answer['list_count'] = pq('.content_reply_list .list_title b')->text();
        $answer['list'] = [];

        foreach(pq('#answerInnerList .reply') as $item)
        {
            $row = $this->getAnswer($item);
            $answer['list'][] = $row;
        }



        $data = compact('title','author','question','answer');

        return $data;
    }

    public function getAnswer($target)
    {
        $row = [];
        $target = pq($target);
        $row['aid'] = $target->find('.reply_like')->attr('data-aid');
        $row['status'] = trim($target->find('.reply_band_wrap')->text());
        $row['status_class'] = $target->find('.reply_band_wrap')->attr('class');
        $row['author']['photo'] = $target->find('.header_photo > a > img')->attr('src');
        $row['author']['link'] = $target->find('.header_photo > a')->attr('href');
        $row['author']['name'] = trim($target->find('.reply_info_name a')->text());
        $row['time'] = $target->find('.reply_info_sub .sub_date')->text();
        $row['like'] = trim($target->find('.reply_like b')->text());
        $row['contnet'] =$target->find('.content_text')->html();
        $row['good_list'] = [];

        return $row;
    }

    public function fetchNote($id)
    {
        $url = 'http://lineq.tw';
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url.'/note/'.$id);
        $html = phpQuery::newDocument($res->getBody());

        $banner = [];
        $banner['ico_pickup'] = pq('.banner_title .ico_pickup')->text();
        $banner['title'] = pq('.banner_title .title')->text();

        $cover = [];
        $cover['title'] = pq('.cover_title')->text();
        $cover['tag'] = [];
        foreach(pq('.cover_tags a') as $tag)
        {
            $cover['tag'][] = [
                'url' => pq($tag)->attr('href'),
                'name' => pq($tag)->text(),
            ];
        }
        $cover['profile'] = [] ;
        $cover['profile']['url'] = pq('.cover_profile a')->attr('href');
        $cover['profile']['name'] = trim(pq('.profile_name')->text());
        $cover['profile']['photo'] = pq('.profile_photo img')->attr('src');
        $cover['profile']['date'] = trim(pq('.profile_meta .date')->text());

        $cover['view'] = trim(pq('.cover_view')->text());
        $cover['metoo_count'] = trim(pq('.cover_metoo .count')->text());
        $cover['comment_count'] = trim(pq('.cover_comment  .count')->text());
        $cover['source'] = trim(pq('.sub_source  .src')->text());

        $page = [];
        foreach(pq('.note_list .list_item') as $item)
        {
            $row = pq($item);
            $url = $row->find('.list_text a')->attr('href');
            $page[] = [
                'order' => trim($row->find('.list_order')->text()),
                'text' => trim($row->find('.list_text .text')->html()),
                'url' => $url,
                'image' => $row->find('.list_image img')->attr('src'),
                'image_src' => $row->find('.list_image .list_source .src')->text(),
                'content' =>fetchNotePage($url)
            ];
        }

        $comment = [];
        foreach(pq('.comment_list .list_item') as $item)
        {
            $row = pq($item);
            $comment[] = [
                'text' =>  $row->find('.commentText')->text(),
                'username' => $row->find('.meta_data_wrap .username')->text(),
                'date' => $row->find('.meta_data_wrap .date')->text(),

            ];
        }


        return compact('id','banner','cover','page','comment');
    }

    public function fetchNotePage($path)
    {
        $url = 'http://lineq.tw';
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url.$path);
        $html = phpQuery::newDocument($res->getBody());
        $content = [];
        $content['image'] = pq('.content_image img')->attr('src');
        $content['image_src'] = pq('.content_image .content_source')->text();
        $content['text'] = pq('.content_text .text')->html();

        return $content;
    }
}