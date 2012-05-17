<?php
class Framework_Controller extends Bull_Web_Controller
{
    /**
     *
     * Constructor.
     *
     * @return void
     *
     */
    public function __construct($context, array $params = array())
    {
        $this->preConstruct();
        parent::__construct($context, $params);
        $this->view = new Bull_View_Twig();
        $this->postConstruct();
    }
    
    /**
     * 
     * Determines the action method, then invokes it.
     * 
     * @return void
     * 
     */
    protected function action()
    {
        $name    = $this->inflect->underToStudly($this->action);
        $method  = 'action' . $name;
        
        if (! method_exists($this, $method)) {
            // throw new Bull_Web_Exception_NoMethodForAction($this->action);
            $this->data->message = "Method '" . get_called_class() . "::{$method}()' not exists.";
            $this->viewfile = "Error/common";
            return ;
        }
        $this->invokeMethod($method);
    }
    
    /**
     * 
     * Renders the view into the response and sets the response content-type.
     * 
     * N.b.: If the response content is already set, the view will not be
     * rendered.
     * 
     * @return void
     * 
     */
    protected function render()
    {
        $this->view->setFormat($this->getFormat());
        if (! $this->response->getContent()) {
            // set data
            $this->data->action = $this->action;
            $this->data->controller = $this->params['controller'];

            $viewpath = ROOT . DIRECTORY_SEPARATOR
                      . "Framework" . DIRECTORY_SEPARATOR . "Web";
            $this->view->setPath($viewpath);
            
            if ($this->context instanceof Bull_Web_Context) {
                // set accept headers
                $accept = $this->getContext()->getAccept();
                $this->view->setAccept($accept);                
            }

            // render view and set content
            $viewfile = "";
            if (empty($this->viewfile)) {
                $class = get_class($this);
                $dir   = $this->inflect->classToFile($class, "");
                if ($real_dir = Bull_Util_File::exists($dir)) {
                    $viewfile = basename($real_dir) . DIRECTORY_SEPARATOR . $this->action;
                }
            } else {
                $viewfile = $this->viewfile;
            }

            $data = (array) $this->getData();
            $this->view->setDatas($data);
            
            try {
                $content = $this->view->render($viewfile);
            } catch(Exception $e) {
                $this->view->setData('message', $e->getMessage());
                $content = $this->view->render("Error/common");
            }
            
            $this->response->setContent($content);
        }
        $this->response->setContentType($this->view->getContentType());
    }
}
