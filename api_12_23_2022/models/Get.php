<?php



class getFunctions
{

	 public function item_statuses_count($req)
    {
		/* global $woocommerce;
		$status_counts = $woocommerce->get('orders/count'); */
		// echo "fasdf";
		 return array("data" => 'fasdfsd' );
 
	}
    public function all_orders($req)
    {
	 global $woocommerce;
		
		/* 	print_r($woocommerce);
		$lastResponse = $woocommerce->http->getResponse();
		$headers = $lastResponse->getHeaders();
		$total_pages = $headers['X-WP-TotalPages'];
		$total = $headers['X-WP-Total'];
		print_r( $totalPages );  */
		
		$query = array();
		if(isset($req['per_page']))$query['per_page'] = $req['per_page'];
		if(isset($req['page']))$query['page'] = $req['page'];
		if(isset($req['s']))$query['search'] = $req['s'];
		if(isset($req['status']))$query['status'] = $req['status'];
		
		// $query['search'] = "SampleGuar";
			$query = http_build_query($query);
			// print_r($query);
       
		// ?page='.$page.'&per_page'.$per_page
		// echo 'orders?'.$query;
		$orders = $woocommerce->get('orders?'.$query);
		
			// print_r($woocommerce);
		$lastResponse = $woocommerce->http->getResponse();
		$headers = $lastResponse->getHeaders();
		/* echo "<pre>";
		print_r($headers); */
		$total_pages = $headers['x-wp-totalpages'];
		$total = $headers['x-wp-total'];
		// print_r( $totalPages );
		
		// $total = $woocommerce->get('orders');
		// echo count($orders);
		// echo (isset($req['per_page'])? $req['per_page']: 0);
		// $total_pages =ceil(count($total) / (isset($req['per_page'])? $req['per_page']: count($total)));
		 // 									$total??0					$total_pages??0 
        return array("data" => $orders , "total" => $total??0, "total_page" => $total_pages??0 , "all_total" => count($orders)   );
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