<?php
/**
 * 
 * A Web controller.
 * 
 * @package Bull.Web
 * 
 */
abstract class Bull_Web_Controller
{
    /**
     * 
     * The action to perform, typically discovered from the params.
     * 
     * @var string
     * 
     */
    protected $action;
    
    /**
     * 
     * The context of the request environment.
     * 
     * @var Context
     * 
     */
    protected $context;
    
    /**
     * 
     * Collection point for data, typically for rendering the page.
     * 
     * @var StdClass
     * 
     */
    protected $data;
    
    /**
     * 
     * The page format to render, typically discovered from the params.
     * 
     * @var string
     * 
     */
    protected $format;
    
    /**
     * 
     * Path-info parameters, typically from the route.
     * 
     * @var array
     * 
     */
    protected $params;
    
    /**
     * 
     * A data transfer object for the eventual HTTP response.
     * 
     * @var Response
     * 
     */
    protected $response;

    /**
     *
     * View Object
     *
     * @var null | Bull_View_Abstract
     *
     */
    protected $view;

    /**
     *
     * Inflect Object
     *
     * @var Bull_Util_Inflect
     *
     */
    protected $inflect;
    
    /**
     *
     * View file
     *
     * @var string | null
     *
     */
    protected $viewfile = null;

    /**
     *
     * Default action.
     *
     * @var string
     *
     */
    protected $defaction = "index";

    /**
     * 
     * Constructor.
     * 
     * @param Context $context The request environment.
     * 
     * @param Response $response A response transfer object.
     * 
     * @param array $params The path-info parameters.
     * 
     */
    public function __construct($context, array $params = array())
    {
        $this->inflect  = new Bull_Util_Inflect();
        $this->context  = $context;
        $this->response = new Bull_Web_Response();
        $this->params   = $params;
        $this->data     = new StdClass();
        
        $this->action   = isset($this->params['action'])
                        ? $this->params['action']
                        : null;
        $this->action   = $this->action === null
                        ? $this->defaction
                        : $this->action;
        $this->action   = $this->inflect->dashesToUnder(strtolower($this->action));
        
        $this->format   = isset($this->params['format'])
                        ? $this->params['format']
                        : null;
    }

    /**
     *
     * Hook before construct
     *
     */
    protected function preConstruct() {}

    /**
     *
     * Hook after construct
     *
     */
    protected function postConstruct() {}

    /* ====================Getters==================== */
    
    /**
     * 
     * Returns the action, typically discovered from the params.
     * 
     * @return string
     * 
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * 
     * Returns the Context object.
     * 
     * @return Context
     * 
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * 
     * Returns the data collection object.
     * 
     * @return StdClass
     * 
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * 
     * Returns the page format, typically discovered from the params.
     * 
     * @return StdClass
     * 
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * 
     * Returns the params.
     * 
     * @return array
     * 
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * 
     * Returns the Response object.
     * 
     * @return Response
     * 
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /* ====================The Execution Cycle==================== */
    
    /**
     * 
     * Executes the action and all hooks:
     * 
     * - calls `preExec()`
     * 
     * - calls `preAction()`
     * 
     * - calls `action()` to find and invoke the action method
     * 
     * - calls `postAction()`
     * 
     * - calls `preRender()`
     * 
     * - calls `render()` to generate a presentation (does nothing by default)
     * 
     * - calls `postRender()`
     * 
     * - calls `postExec()` and then returns the Response transfer object
     * 
     * @return Response
     * 
     */
    public function exec()
    {
        // prep
        $this->preExec();
        
        // the action cycle
        $this->preAction();
        $this->action();
        $this->postAction();

        if ($this->view instanceof Bull_View_Abstract) {
            // the render cycle
            $this->preRender();
            $this->render();
            $this->postRender();
        } else {
            $content = ob_get_clean();
            $this->response->setContent($content);
        }
        // done
        
        $this->postExec();
        return $this->response;
    }
    
    /**
     * 
     * Runs at the beginning of `exec()` before `preAction()`.
     * 
     * @return void
     * 
     */
    public function preExec()
    {
    }
    
    /**
     * 
     * Runs after `preExec()` and before `action()`.
     * 
     * @return void
     * 
     */
    public function preAction()
    {
    }

    abstract protected function action();
    
    /**
     * 
     * Invokes a method by name, matching method params to `$this->params`.
     * 
     * @param string $name The method name to execute, typcially an action.
     * 
     * @return void
     * 
     */
    protected function invokeMethod($name)
    {
        $args = array();
        $method = new ReflectionMethod($this, $name);
        foreach ($method->getParameters() as $param) {
            if (isset($this->params[$param->name])) {
                $args[] = $this->params[$param->name];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $args[] = null;
            }
        }
        $method->invokeArgs($this, $args);
    }
    
    /**
     * 
     * Runs after `action()` and before `preRender()`.
     * 
     * @return void
     * 
     */
    public function postAction()
    {
    }
    
    /**
     * 
     * Runs after `postAction()` and before `render()`.
     * 
     * @return void
     * 
     */
    public function preRender()
    {
    }
    
    /**
     * 
     * Runs after `render()` and before `postExec()`.
     * 
     * @return void
     * 
     */
    public function postRender()
    {
    }
    
    /**
     * 
     * Runs at the end of `exec()` after `postRender()`.
     * 
     * @return mixed
     * 
     */
    public function postExec()
    {
    }

    public function setView($viewfile)
    {
        $this->viewfile = $viewfile;
    }

    public function disableView()
    {
        $this->view = null;
    }
    
    abstract protected function render();
}
