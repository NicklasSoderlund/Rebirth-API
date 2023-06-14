<?php

require_once './views/error-view.php';

class SellerController {

    private $model = null;
    private $view = null;

    public function __construct($sellerModel, $sellerView)
    {
        $this->model = $sellerModel;
        $this->view =  $sellerView;
    }


    public function showSellers() {
       $this->view->outputAll($this->model->getSellers());
    }


    public function regSeller(array $seller) {
   
        
       if(isset($seller['firstname'], $seller['lastname'], $seller['phone'], $seller['email'])) {
          
        $firstname = filter_var($seller['firstname'], FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_var($seller['lastname'], FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_var($seller['phone'], FILTER_SANITIZE_NUMBER_INT);
        $email = filter_var($seller['email'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ErrorView::notAllowed();
         }
 
        $this->view->outputRegister($this->model->registerSeller($firstname, $lastname, $phone, $email));
           
       }

       else {
        http_response_code(422);
        return http_response_code();
       }

    }

   public function showSellerInfo($id) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $this->view->outputSeller($this->model->getSellerInfo($id));
    }



}
