<?php



class ProductController {

    private $model = null;
    private $view = null;

    public function __construct($productModel, $productView)
    {
        $this->model = $productModel;
        $this->view =  $productView;
    }


    public function showProducts() {
        $this->view->outputAll($this->model->getProducts());
    }

    public function markAsSold($id, $datetime) {

        if(isset($id, $datetime['date_sold'])) {

            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $datetime = filter_var($datetime['date_sold'], FILTER_SANITIZE_STRING);

            $this->view->outputRegister($this->model->registerSale($id, $datetime));
        }

        
        

        else {
            http_response_code(422);
           }
     
    }

    public function regProduct(array $product) {
   
        if(isset($product['product_name'], $product['brand_id'], $product['product_types'], $product['colour_ids'], $product['selling_price'], $product['date_of_arrival'], $product['seller_id'])) {
                
            $name = filter_var($product['product_name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $brand = filter_var($product['brand_id'], FILTER_SANITIZE_NUMBER_INT);
            $types = filter_var_array($product['product_types'], FILTER_SANITIZE_NUMBER_INT);
            $colours= filter_var_array($product['colour_ids'], FILTER_SANITIZE_NUMBER_INT);
            $selling_price = filter_var($product['selling_price'], FILTER_SANITIZE_NUMBER_INT);
            $date_of_arrival = filter_var($product['date_of_arrival'], FILTER_SANITIZE_STRING);
            $seller_id = filter_var($product['seller_id'], FILTER_SANITIZE_NUMBER_INT);

            
            $this->view->outputRegister($this->model->registerProduct($name, $brand, $types, $colours, $selling_price, $date_of_arrival, $seller_id));

        }

       else {
        http_response_code(422);
       }

    }



}
