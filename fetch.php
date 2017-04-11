<?php
require 'vendor/autoload.php';
$url = 'http://lineq.tw/technology/hotanswer';
$page = 1;

$all_q = [];
while(1)
{
    $client = new \GuzzleHttp\Client();
    if($page == 1)
        $res = $client->request('GET', $url);
    else
        $res = $client->request('GET', $url.'?page='.$page);

    $html = phpQuery::newDocument($res->getBody());

    $items =  pq('.question_list  .question');

    foreach($items as $item)
    {
        $question = pq($item);
        $qid = $question->attr('data-qid');
        $author = [];
        $author['photo'] = $question->find('.question_header .header_photo img')->attr('src');
        $author['name'] = trim($question->find('.question_header .header_name')->text());
        $author['time'] = $question->find('.question_header .header_time')->text();
        $relations =[];
        foreach($question->find('.question_relations a') as $item2)
        {
            $relations[] = [
                'url' => pq($item2)->attr('href'),
                'category' => pq($item2)->text(),
            ];
        }
        $content = $question->find('.content_text')->html();
        $data = compact('qid','author','relations','content');

        $all_q[] = $data;
    }

    $next_page = count(pq('.btn_go.next')); 
    if($next_page){
        $page++;
    } else {
        break;
    }
}

file_put_contents('q/hotanswer.json',json_encode($all_q,true));



