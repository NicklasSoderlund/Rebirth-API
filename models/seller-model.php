<?php

require_once './controllers/db-controller.php';
require_once './utils/utils.php';

class SellerModel extends DBController
{

    protected $table = 'sellers';

   public function getSellers()
    {    
        return $this->customFetch('SELECT first_name, last_name, phone, Email FROM `sellers` ORDER BY last_name');
    }



    public function getSellerInfo($id)
    {

        // Seller information
        $stmt = $this->pdo->prepare('SELECT s.id, s.first_name, s.last_name, s.phone, s.email, COUNT(p.id) AS AmountOfListed, COUNT(P.date_sold) AS SoldProducts
        FROM `sellers` AS s JOIN products AS p ON s.id = p.seller_id WHERE s.id = ?');
        $stmt->execute([$id]);
        $sellerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Amount sold for
        $stmt = $this->pdo->prepare('SELECT SUM(p.selling_price) AS AmountSoldFor FROM `products` AS p JOIN sellers AS s ON s.id = p.seller_id WHERE s.id = ? AND p.date_sold IS NOT NULL;');
        $stmt->execute([$id]);
        $amountSoldFor = $stmt->fetch(PDO::FETCH_ASSOC);

        $result = array_merge($sellerInfo, $amountSoldFor);
        
        // Seller products
        $stmt = $this->pdo->prepare('SELECT p.id, p.name, p.selling_price, p.date_of_arrival, p.date_sold, b.brand AS brand FROM products AS p JOIN sellers AS s ON s.id = p.seller_id JOIN brands as b ON p.brand_id = b.id WHERE s.id = ?');
        $stmt->execute([$id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $updatedProducts = [];

        // Add all types & colours to the products
        foreach($products as $product) {
              
        $productId = $product['id'];
        
        // ADD COLOURS INFO TO PRODUCT
        $stmt = $this->pdo->prepare('SELECT c.colour FROM colours AS c JOIN product_colours AS pc ON c.id = pc.colour_id JOIN products AS p ON p.id = pc.product_id WHERE p.id = ?');
        $stmt->execute([$productId]);
        $colours = $stmt->fetchAll(PDO::FETCH_ASSOC);

           $colourList = [];
           foreach($colours as $colour) {
           array_push($colourList, $colour['colour']);
         }
          array_push($product, $colourList);
          _rename_arr_key('0', 'colours', $product);
      

       // ADD TYPES INFO TO PRODUCT
          $stmt = $this->pdo->prepare('SELECT t.clothing_type FROM type_of_clothing AS t JOIN product_types AS pt ON t.id = pt.type_id JOIN products AS p ON p.id = pt.product_id WHERE p.id = ?');
          $stmt->execute([$productId]);
          $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
             $typeList = [];
             foreach($types as $type) {
             array_push($typeList, $type['clothing_type']);
           }
            array_push($product, $typeList);
            _rename_arr_key('1', 'types', $product);

            array_push($updatedProducts, $product);
        }

  


        array_push($result, $updatedProducts);
        _rename_arr_key('0', 'Seller products', $result);

        return $result;
        



    }

    // REGISTER NEW SELLER
    public function registerSeller($firstname, $lastname, $phone, $email)
    {

        $stmt = $this->pdo->prepare('INSERT INTO `sellers` (`first_name`, `last_name`, `phone`, `email`) VALUES ( ?, ?, ?, ? )');
        $stmt->execute([$firstname, $lastname, $phone, $email]);

        $sellerId = $this->pdo->lastInsertId();

        return $this->getSellerInfo($sellerId);

        
       
    }


}

