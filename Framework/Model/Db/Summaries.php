<?php
class Framework_Model_Db_Summaries extends Bull_Model_Abstract
{
     protected $table = "summaries";

     protected $name  = "db";

     protected function postConstruct()      
     {
         $this->cols = array (
             'id' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'id',
                 'type' => 'mediumint',
                 'size' => 6,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => true,
                 'primary' => true,
             )),
             'blog_id' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'blog_id',
                 'type' => 'mediumint',
                 'size' => 6,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'comment_count' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'comment_count',
                 'type' => 'mediumint',
                 'size' => 6,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
         );
     }

     public function buildRelated()
     {
         $this->belongsTo('blogs');
     }
}