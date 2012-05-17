<?php
/**
 * 
 * Takes an incoming web request (Context), then dispatches it, renders
 * content, and returns a response for it.
 * 
 * @package Bull.Web
 * 
 */
class Bull_Web_Front
{
    /**
     * 
     * The web request context.
     * 
     * @var Bull_Web_Context
     * 
     */
    protected $context;
    
    /**
     * 
     * The web reponse transfer object returned from the controller.
     * 
     * @var Bull_Web_Response
     * 
     */
    protected $transfer;
    
    /**
     * 
     * The full HTTP response created from the transfer object.
     * 
     * @var Bull_Http_Response
     * 
     */
    protected $response;
    
    /**
     * 
     * A page controller factory.
     * 
     * @var Bull_Web_ControllerFactory
     * 
     */
    protected $factory;
    
    /**
     * 
     * A router map.
     * 
     * @var Bull_Web_RouteMap
     * 
     */
    protected $router;
    

    /**
     *
     * Default controller and action
     *
     * @var array
     *
     * {{code: php
     *     array('controller' => 'home', 'action' => 'index');
     * }}
     *
     */
    protected $defaults;
    
    /**
     * 
     * Constructor.
     * 
     * @param Context $context The web context.
     * 
     * @param RouterMap $router The router map.
     * 
     * @param array $defaults Default controller and action.
     *
     */
    public function __construct($router=null, $context = null, array $defaults = array()) 
    {

        if ($router instanceof Bull_Web_RouteMap) {
            $this->router = $router;
        } else {
            $this->router = new Bull_Web_RouteMap();
        }
        $this->context  = $context;
        $this->defaults = $defaults;
        $this->factory  = new Bull_Web_ControllerFactory($this->defaults['error']['controller']);
        $this->response = new Bull_Http_Response();
    }
    
    /**
     * 
     * Magic read-only access to properties.
     * 
     * @param string $key The property to retrieve.
     * 
     * @return mixed
     * 
     */
    public function __get($key)
    {
        return $this->$key;
    }
    
    /**
     * 
     * Dispatches a Route to a web controller, renders a view into the
     * ReponseTransfer, and returns an HTTP response.
     * 
     * @return Bull_Http_Response
     * 
     */
    public function exec()
    {
        $this->request();
        $this->response();
        return $this->response;
    }
    
    /**
     * 
     * Handle the incoming request.
     * 
     * @return void
     * 
     */
    public function request()
    {
        // match to a route
        $path   = $this->context->getServer('PATH_INFO', '/');
        $server = $this->context->getServer();
        $route  = $this->router->match($path, $server);

        // was there a match?
        if ($route) {
            // retain info
            // does the route indicate a controller?
            if (isset($route->values['controller'])) {
                // take the controller class directly from the route
                $controller = $route->values['controller'];
            } else {
                // use a default controller
                $controller = $this->defaults['controller'];
            }
            // just merge it with defaults
            $params = array_merge($this->defaults, $route->values);
        } else {
            // no match
            $controller = null;
            $params     = array();
        }
        
        // create controller
        $obj = $this->factory->newInstance($this->context, $controller, $params);

        // output buffer
        ob_start();
        
        // execute and get back response transfer object
        $this->transfer = $obj->exec();
    }
    
    /**
     * 
     * Converts the web response transfer object into the HTTP response.
     * 
     * @return void
     * 
     */
    public function response()
    {
        $this->response->setVersion($this->transfer->getVersion());
        $this->response->setStatusCode($this->transfer->getStatusCode());
        $this->response->setStatusText($this->transfer->getStatusText());
        $this->response->headers->setAll($this->transfer->getHeaders());
        $this->response->cookies->setAll($this->transfer->getCookies());
        $this->response->setContent($this->transfer->getContent());
    }
}