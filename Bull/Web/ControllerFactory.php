<?php
/**
 * 
 * A factory to create controller objects; these need not be only Page
 * controllers, but (e.g.) Resource or App controllers.
 * 
 * @package Bull.Web
 * 
 */
class Bull_Web_ControllerFactory
{
    /**
     * 
     * The controller class to instantiate when no mapping is found.
     * 
     * @var ForgeInterface
     * 
     */
    protected $not_found = null;
    
    /**
     * 
     * Constructor.
     * 
     * @param array $map A map of controller names to controller classes.
     * 
     * @param string $not_found The controller class to instantiate when no 
     * mapping is found.
     * 
     */
    public function __construct($not_found = null)
    {
        $this->not_found = $not_found;
    }
    
    /**
     * 
     * Creates and returns a controller class based on a controller name.
     *
     * @param Context $context The Context object.
     *
     * @param string $name The controller name.
     * 
     * @param array $params Params to pass to the controller.
     * 
     * @return Page A controller instance.
     * 
     */
    public function newInstance($context, $name, $params)
    {
        $inflect = new Bull_Util_Inflect();
        $name    = $inflect->dashesToUnder(strtolower($name));
        $name    = $inflect->underToStudly($name);
        $class   = "Framework_Web_".$name;
        $file    = $inflect->classToFile($class);
        if (Bull_Util_File::exists($file)) {
            // do nothing
        } elseif ($this->not_found) {
            $class  = $this->not_found;
            $params = array("controller" => "error", "action" => "not_found");
        } else {
            throw new Bull_Web_Exception_NoClassForController("'$name'");
        }
        return Bull_Di_Container::newInstance($class, array($context, 'params' => $params));
    }
}
