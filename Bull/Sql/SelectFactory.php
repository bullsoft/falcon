<?php

class Bull_Sql_SelectFactory
{
    public function newInstance(Bull_Sql_Adapter_Abstract $sql)
    {
        return new Bull_Sql_Select($sql);
    }
}
