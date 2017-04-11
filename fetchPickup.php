<?php
require 'vendor/autoload.php';
$url = 'http://lineq.tw/technology/pickup';
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

    $items =  pq('.pickup_list  li');

    foreach($items as $item)
    {
        $li = pq($item);
        $title =  trim($li->find('.contents_innr p.text')->text());
        $cover = $li->find('.smallthum  img')->attr('src');
        $link = $li->find('a.contents_link')->attr('href');
        $contents_source = trim($li->find('.contents_source span.src')->text());
        $meta_count = $li->find('.meta_data_wrap .attend em')->text();

        $data = compact('title','cover','link','contents_source','meta_count');

        $all_q[] = $data;
    }

    $next_page = count(pq('.btn_go.next'));
    if($next_page){
        $page++;
    } else {
        break;
    }
}

file_put_contents('q/pickup.json',json_encode($all_q,true));



