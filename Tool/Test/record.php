<?php

define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$model = Framework_Model_Db_Blogs::getInstance();

$result = $model->selectBy(1);

// var_export($result);

$record = $model->getRecord($result[0]);

// var_dump($record->tags[0]->name);

// exit;

$record->title = "BullSoft测试222rrrrfdasd";
$data =  array (
    'status'  => 'draft',
    'title'   => 'BullSoft测试222',
    'body'    => 'BullSoft测试',
    'authors' => array (
        'id' => '1',
        'name' => '顾伟刚',),
    'summaries' => array (
        'comment_count' => 0,),
    'tags' => array(
        array('name' => "test",),
        array('name' => 'hahahah'),
    ),
);


$record->save();

