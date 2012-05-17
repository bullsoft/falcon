<?php

class Framework_Web_Home extends Framework_Controller
{
    public function actionIndex()
    {
        $this->data->hello = "hello, world!";
    }

    public function actionAbout()
    {
        $this->disableView();
        echo "About Bull Framework";
    }

    public function actionContact()
    {
        $this->disableView();
        echo "Contact us";
    }
}