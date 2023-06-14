----------------------------------------REBIRTH CLOTHING STORE API----------------------------------------

This directory contains an API for communicating with the Rebirth database

Run composer install in the terminal to install FastRoute

--------------------------------------PRODUCTS--------------------------------------

See all prodcuts:

GET localhost/rebirth/products

-

Adding Products:

POST to localhost/rebirth/products with a body as such:

{
    "product_name" : "Levis summer jacket",
    "brand_id" : "1",
    "selling_price" : "800",
    "date_of_arrival" : "2001-11-04 13:45:50",
    "seller_id" : "8",
    "product_types": [5, 8],
    "colour_ids": [1, 2, 6]
}

-

Mark a product as sold:

PUT to localhost/rebirth/products/{id} with a body as such:

{
    "date_sold" : "2008-01-15 10:25:40"
}

--------------------------------------SELLERS--------------------------------------

See all sellers (Alphabetical order by lastname):

GET localhost/rebirth/sellers

-

See specific seller info

GET localhost/rebirth/sellers/{id}

-

Register new seller

POST to localhost/rebirth/sellers with a body as such:

{
  "firstname": "Jerry",
  "lastname": "Kringlestein",
  "phone": "070-700-54-16",
  "email": "splurtmail@gmail.com"
}

-


Enjoy.

