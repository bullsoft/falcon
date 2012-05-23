<?php
class Framework_Model_Db_Authors extends Bull_Model_Abstract
{
     protected $table = "authors";

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
             'name' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'name',
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

     protected function buildRelated()
     {
         $this->foreign_col = "author_id";
     }
}