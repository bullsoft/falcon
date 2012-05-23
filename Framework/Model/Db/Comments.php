<?php
class Framework_Model_Db_Comments extends Bull_Model_Abstract
{
     protected $table = "comments";

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
                 'type' => 'smallint',
                 'size' => 6,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'title' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'title',
                 'type' => 'varchar',
                 'size' => 60,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'content' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'content',
                 'type' => 'text',
                 'size' => NULL,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'author' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'author',
                 'type' => 'varchar',
                 'size' => 20,
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
     }
}