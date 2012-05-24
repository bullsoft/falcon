<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$model = Framework_Model_Db_Blogs::getInstance();

$result = $model->selectAll();

$collection = $model->getCollection($result);

/* echo (count($collection)); */
/* var_dump($collection[0]->summaries()->comment_count); */

/* foreach($collection as $key => $value) */
/* { */
/*     echo $value->title; */
/*     var_dump($value->comments()->toArray()); */
/* } */

/* exit; */

$data =  array (
    'status'  => 'draft',
    'title'   => 'BullSoft测试大家好大家好百度时代有限公司',
    'body'    => 'BullSoft测试',
    'authors' => array (
        'id' => '1',
        'name' => '顾伟刚',),
    'summaries' => array (
        'comment_count' => 0,),
    'comments' => array(
        array('title' => "给BullSoft",
              'content' => "留方给BullSoft",
              'author' => '顾伟刚',),
        array('title' => "给BullFramework",
              'content' => "留言给BullFramework",
              'author' => '顾伟刚',),
    ),
    'tags' => array(
        array('name' => "bullsoft",),
        array('name' => 'bullframework'),
    ),
);

$record = $model->newRecord($data);

// var_dump($collection);
$record->save();

var_dump($record->comments->toArray());