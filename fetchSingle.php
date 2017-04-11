<?php
require_once 'vendor/autoload.php';


function fetchQ($id)
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
        $row = getAnswer($band);
        $answer['band'][] = $row;
    }

      $answer['list_count'] = pq('.content_reply_list .list_title b')->text();
      $answer['list'] = [];

      foreach(pq('#answerInnerList .reply') as $item)
      {
        $row = getAnswer($item);
        $answer['list'][] = $row;
      }



    $data = compact('title','author','question','answer');

    return $data;
}

function getAnswer($target)
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