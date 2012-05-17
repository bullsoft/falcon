<?php
class Framework_Web_Demo extends Framework_Controller
{
    public function actionIndex()
    {
    }

    public function actionCreate()
    {
        if ($this->context->isPost()) {
            $this->disableView();
            $elements = new Bull_Form_Element();
            $posts = $this->context->getPost();
            
            foreach ($posts as $key => $value) {
                $elements->setElement($key);
            }
            
            $elements->addFilter("name",    "ValidateNotBlank");
            $elements->addFilter("address", "ValidateNotBlank");
            $elements->addFilter("comment", "ValidateNotBlank");
            $elements->setValues($posts)->validate();

            $output = '<html><head><meta charset="utf-8"></head>'
                    . '<body><script language="javascript"> if (parent) {';
            if ($elements->isFailure()) {
                $errors = array_filter($elements->getInvalids());
                foreach($errors as $id => $txt) {
                    $txt = $txt[0];
                    $output .=  "parent._form_exception('$id', '$txt');".PHP_EOL;
                }
            } else {
                $model = Framework_Model_Db_Shop::getInstance();
                $res = $model->insert($posts);
                if ($res) {
                    $output .= "parent._form_exception('', '插入成功！');".PHP_EOL;
                }
            }
            
            $output .= '}</script></body></html>';
            echo $output;
            return ;
        }
    }
    
    public function actionRead($id = null)
    {
        $mode = Framework_Model_Db_Shop::getInstance();
        if ($id === null) {
            $this->data->shops = $mode->selectAll();
        } else {
            $elements = new Bull_Form_Element();
            $elements->setElement('id');
            $elements->addFilter('id', 'ValidateNumeric');
            $elements->setValue('id', $id)->validate();
            if ($elements->isFailure()) {
                $invalid = array_filter($elements->getInvalids('id'));
                $txt = $invalid[0];
                $this->data->error = $txt;
                return ;
            } else {
                $this->data->shops = $mode->selectBy($id);
            }
        }
    }
}