<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$model = Framework_Model_Db_Blogs::getInstance();

$result = $model->selectAll();

// var_export($result);
// $collection = $model->getCollection($result);
// var_export($record->authors());
// var_export($record->summaries());
// echo (count($collection));
// var_dump($collection[0]);

/* foreach($collection as $key => $value) */
/* { */
/*     echo $value->title; */
/*     var_dump($value->comments()); */
/*     exit; */
/* } */

$data =  array (
    'status'  => 'draft',
    'title'   => 'BullSoft测试333asdfl;kjasld;fja;sldkjf;',
    'body'    => 'BullSoft测试',
    'authors' => array (
        'id' => '1',
        'name' => '顾伟刚',),
    'summaries' => array (
        'comment_count' => 0,),
    'comments' => array(
        array('title' => "给BullSoft",
              'content' => "给BullSoft",
              'author' => '顾伟刚哈哈',
        ),
        array('title' => "给BullFramework",
              'content' => "给BullSoft111",
              'author' => '顾伟刚哈哈111',
        ),
    ),
    'tags' => array(
        array('name' => "test",),
        array('name' => 'hahahah'),
    ),
);

$record = $model->newRecord($data);

// var_dump($collection);
$record->save();