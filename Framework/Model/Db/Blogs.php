<?php
class Framework_Model_Db_Blogs extends Bull_Model_Abstract
{
     protected $table = "blogs";

     protected $name  = "db";

     protected function postConstruct()      
     {
         $this->cols = array (
             'id' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'id',
                 'type' => 'int',
                 'size' => 11,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => true,
                 'primary' => true,
             )),
             'created' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'created',
                 'type' => 'datetime',
                 'size' => NULL,
                 'scale' => NULL,
                 'notnull' => false,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'updated' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'updated',
                 'type' => 'datetime',
                 'size' => NULL,
                 'scale' => NULL,
                 'notnull' => false,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'status' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'status',
                 'type' => 'varchar',
                 'size' => 15,
                 'scale' => NULL,
                 'notnull' => false,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'title' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'title',
                 'type' => 'varchar',
                 'size' => 63,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'body' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'body',
                 'type' => 'mediumtext',
                 'size' => NULL,
                 'scale' => NULL,
                 'notnull' => false,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'author_id' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'author_id',
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

     protected function buildRelated()
     {
         $this->belongsTo('authors');
         $this->hasOne('summaries');
         $this->hasMany('comments');
         $this->hasMany('taggings');
         $this->hasManyThrough('tags', 'taggings');
         $this->foreign_col = "blog_id";
     }
}
