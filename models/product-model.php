<?php

require_once './controllers/db-controller.php';

class ProductModel extends DBController
{

    protected $table = 'products';


    // MARK A PRODUCT AS SOLD

    public function registerSale($id, $datetime)
    {
        $stmt = $this->pdo->prepare('UPDATE `products` SET `date_sold` = ? WHERE id = ?');
        return $stmt->execute([$datetime, $id]);
    }

    // REGISTER A NEW PRODUCT

    public function registerProduct($name, $brand,array $types,array $colours, $selling_price, $date_of_arrival, $seller_id)
    {

        $stmt = $this->pdo->prepare('INSERT INTO `products` (`name`, `brand_id`, `selling_price`, `date_of_arrival`, `seller_id`) VALUES ( ?, ?, ?, ?, ? )');
        $stmt->execute([$name, $brand, $selling_price, $date_of_arrival, $seller_id]);
        $productId = $this->pdo->lastInsertId();
         

        foreach($types as $type) {
          
         $stmt = $this->pdo->prepare('INSERT INTO `product_types` (`product_id`, `type_id`) VALUES (?, ?)');
         $stmt->execute([$productId, $type]);

        }

        foreach($colours as $colour) {
          
            $stmt = $this->pdo->prepare('INSERT INTO `product_colours` (`product_id`, `colour_id`) VALUES (?, ?)');
            $stmt->execute([$productId, $colour]);
   
           }

           return $this->getById($this->table, $productId);
        

    }


// SHOW ALL PRODUCTS

public function getProducts() {
    $stmt = $this->pdo->prepare('SELECT p.id, p.name, p.selling_price, p.date_of_arrival, p.date_sold, b.brand AS brand FROM products AS p JOIN brands as b ON p.brand_id = b.id');
    $stmt->execute();
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

    return $updatedProducts;
    

}

}


