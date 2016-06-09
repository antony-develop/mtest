<?php

class Router{
	
    private $routes;
	
    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
		
    }
    
    /**
     * returnes request string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    
    public function run()
    {
	//get url        
        $uri = $this->getURI();	
        
        //check if url exists in routes.php
        foreach($this->routes as $uriPattern => $path){
            
            //compare $uriPattern and $uri
            if (preg_match("~$uriPattern~", $uri)){
                
                //get internal path
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri); 
                
                //define controller, action, paramenters
                $segments = explode('/', $internalRoute);
                
                $controllerName = array_shift($segments).'Controller';
                $controllerName = ucfirst($controllerName);
                
                $actionName = 'action'.ucfirst(array_shift($segments));
                
                $parameters = $segments;
                
                //connect file of controller 
                $controllerFile = ROOT . '/controllers/' .
                        $controllerName . '.php';
                
                if(file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                
                //Create object and call method (action)
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                if($result != null){
                    break;
                }
            }
        }
        
       
    }
}