<?php
require 'vendor/autoload.php';
require './fetchSingle.php';


$all_q = file_get_contents('q/hotanswer.json');

$all_q = json_decode($all_q,true);

foreach($all_q as $q)
{
    echo $q['link']."\n";
    $temp = explode('/',$q['link']);
    if(count($temp) >1 && $temp[1] == 'q'){
        $id = $temp[2];
        $data = fetchQ($id);
        file_put_contents('q/single/'.$id.'.json',json_encode($data));
    }
    if(count($temp) >1 && $temp[1] == 'note'){
        $id = $temp[2];
        $data = fetchNote($id);
        file_put_contents('note/'.$id.'.json',json_encode($data));
    }
    if(count($temp) >1 && $temp[2] == 'q'){
        $id = $temp[3];
        $data = fetchQ($id);
        file_put_contents('q/single/'.$id.'.json',json_encode($data));
    }
    if(count($temp) >1 && $temp[2] == 'note'){
        $id = $temp[3];
        $data = fetchNote($id);
        file_put_contents('note/'.$id.'.json',json_encode($data));
    }
}

