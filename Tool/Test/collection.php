<?php
define("ROOT", dirname(dirname(__DIR__)));
require ROOT. '/Tool/cli.php';

$model = Framework_Model_Db_Blogs::getInstance();

// --------------------------------------------------------------
// Blog与其他模型的关系
// 
// $this->belongsTo('authors');
// $this->hasOne('summaries');
// $this->hasMany('comments');
// $this->hasMany('taggings');
// $this->hasManyThrough('tags', 'taggings');
// 
// --------------------------------------------------------------




// --------------------------------------------------------------
//
// 使用Collection读取, has-one, has-many, has-many through
//
// --------------------------------------------------------------
$result = $model->selectAll();

$collection = $model->getCollection($result);

echo (count($collection));
var_dump($collection[0]->summaries->toArray());

var_dump($collection[0]->tags->toArray());

foreach($collection as $key => $value)
{
    echo $value->title;
    var_dump($value->comments->toArray());
}

exit;

// --------------------------------------------------------------
//
// 使用Collection插入，has-one, has-many, has-many through
//
// --------------------------------------------------------------

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

$record->save();

var_dump($record->comments->toArray());