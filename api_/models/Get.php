<?php



class getFunctions
{

    public function all_orders($d)
    {
        global $woocommerce;

        $orders = $woocommerce->get('orders');
        return array("data" => $orders);
    }

    public function all_products($d)
    {
        global $woocommerce;

        $products = $woocommerce->get('products', array('per_page'=>100));
        return array("data" =>$products);
    }
	 public function get_product_byId($d , $id)
    {
		 global $woocommerce;

        $orders = $woocommerce->get('products/'.$id);
        return array("data" => $orders);
    }
	 public function get_order_byId($d , $id)
    {
		 global $woocommerce;

        $orders = $woocommerce->get('orders/'.$id);
        return array("data" => $orders);
    }
    public function get_tax_rates($d)
    {
        global $woocommerce;

        $taxes = $woocommerce->get('taxes', array('per_page'=>100));
  
        return $taxes;
    }


}


?>