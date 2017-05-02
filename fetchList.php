<?php
require 'vendor/autoload.php';

$LINEQ = new \app\LINEQ();


$all_q = file_get_contents('q/hotanswer.json');

$all_q = json_decode($all_q,true);

foreach($all_q as $q)
{
    echo $q['link']."\n";
    $temp = explode('/',$q['link']);
    if(count($temp) >1 && $temp[1] == 'q'){
        $id = $temp[2];
        $data = $LINEQ->fetchQ($id);
        file_put_contents('q/single/'.$id.'.json',json_encode($data));
    }
    if(count($temp) >1 && $temp[1] == 'note'){
        $id = $temp[2];
        $data = $LINEQ->fetchNote($id);
        file_put_contents('note/'.$id.'.json',json_encode($data));
    }
    if(count($temp) >1 && $temp[2] == 'q'){
        $id = $temp[3];
        $data = $LINEQ->fetchQ($id);
        file_put_contents('q/single/'.$id.'.json',json_encode($data));
    }
    if(count($temp) >1 && $temp[2] == 'note'){
        $id = $temp[3];
        $data = $LINEQ->fetchNote($id);
        file_put_contents('note/'.$id.'.json',json_encode($data));
    }
}

