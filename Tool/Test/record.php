<?php

define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$model = Framework_Model_Db_Blogs::getInstance();

$result = $model->selectBy(1);

// var_export($result);

$record = $model->getRecord($result[0]);

var_dump($record->taggings);
exit;
foreach($record->tags as $tags)
{
    echo $tags->name;
}

foreach($record->comments as $val)
{
    echo $val->content;
}

exit;

$data =  array (
    'status'  => 'draft',
    'title'   => 'BullSoft测试 Has One',
    'body'    => 'BullSoft测试',
    'authors' => array (
        'id' => '1',
        'name' => '顾伟刚',),
    'summaries' => array (),
    /* 'tags' => array( */
    /*     array('name' => "test",), */
    /*     array('name' => 'hahahah'), */
    /* ), */
);

$record = $model->newRecord($data);

$record->save();

