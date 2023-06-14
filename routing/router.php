<?php

require './vendor/autoload.php';

require_once './models/seller-model.php';
require_once './views/api-view.php';
require_once './controllers/seller-controller.php';
require_once './index.php';
require_once './views/error-view.php';
require_once './controllers/product-controller.php';
require_once './models/product-model.php';


class Router {
    public function start() {

        $pdo = require 'utils/connect.php';

        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
         
            // SELLER ROUTES
        $r->addRoute('GET', '/rebirth/sellers', ['SellerController', 'ApiView', 'SellerModel', 'showSellers']);
        $r->addRoute('GET', '/rebirth/sellers/{id:\d+}', ['SellerController', 'ApiView', 'SellerModel', 'showSellerInfo']);
        $r->addRoute('POST', '/rebirth/sellers', ['SellerController', 'ApiView', 'SellerModel', 'regSeller']);

            // PRODUCT ROUTES
        $r->addRoute('GET', '/rebirth/products', ['ProductController', 'ApiView', 'ProductModel', 'showProducts']);
        $r->addRoute('POST', '/rebirth/products', ['ProductController', 'ApiView', 'ProductModel', 'regProduct']);
        $r->addRoute('PUT', '/rebirth/products/{id:\d+}', ['ProductController', 'ApiView', 'ProductModel', 'markAsSold']);
    
    });
    
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);
 
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

    switch ($routeInfo[0]) {
       
        case FastRoute\Dispatcher::NOT_FOUND:
            ErrorView::notFound();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            ErrorView::notAllowed();
            break;
        case FastRoute\Dispatcher::FOUND:
         
            $controllerName = $routeInfo[1][0]; 
            $action = $routeInfo[1][3]; 
            $view = $routeInfo[1][1];
            $model = $routeInfo[1][2];

             $json = json_decode(file_get_contents('php://input'), true);
             $parameters = $routeInfo[2];
             $parameters = array_values($parameters);
    
            array_push($parameters, $json);

             $controller = new $controllerName(new $model($pdo), new $view); 

             call_user_func_array(
              [$controller, $action]  , $parameters
             );
              
          break;
    }

}
}

