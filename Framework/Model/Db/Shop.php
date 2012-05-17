<?php
class Framework_Model_Db_Shop extends Bull_Model_Abstract
{
     protected $table = "shop";

     protected $name  = "db";

     protected function postConstruct()      
     {
         $this->cols = array (
             'shop_id' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'shop_id',
                 'type' => 'int',
                 'size' => 11,
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
                 'size' => 30,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'address' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'address',
                 'type' => 'mediumtext',
                 'size' => NULL,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
             'comment' => 
             Bull_Sql_Column::__set_state(array(
                 'name' => 'comment',
                 'type' => 'mediumtext',
                 'size' => NULL,
                 'scale' => NULL,
                 'notnull' => true,
                 'default' => NULL,
                 'autoinc' => false,
                 'primary' => false,
             )),
         );
     }
}