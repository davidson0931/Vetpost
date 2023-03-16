<?php 


/* ------------------------------------------ */
/* ----------     CRON JOB      ------------- */
/* ------------------------------------------ */
/* ------------------------------------------ */

add_action( 'init', 'vetpost_api_cron' ); // REGISTER CRON JOB

 function vetpost_api_cron()
{
	
	if( !wp_next_scheduled( 'isa_add_every_fifteen_minutes' ) ) 
	{
		wp_schedule_event( time(), 'every_15_mins', 'isa_add_every_fifteen_minutes' ); 
	}
	if( !wp_next_scheduled( 'isa_add_every_ten_minutes' ) ) 
	{
		wp_schedule_event( time(), 'every_10_mins', 'isa_add_every_ten_minutes' ); 
	}
}

add_filter( 'cron_schedules','isa_add_every_fifteen_minutes');


 function isa_add_every_fifteen_minutes( $schedules ) 
{
	$schedules['every_15_mins'] = array(
		'interval' => 60*15,
		'display'  => __( 'Every 15 minutes' )
	);
	return $schedules;
}

add_filter( 'cron_schedules','isa_add_every_ten_minutes');
function isa_add_every_ten_minutes( $schedules ) 
{
	$schedules['every_10_mins'] = array(
		'interval' => 60*10,
		'display'  => __( 'Every 10 minutes' )
	);
	return $schedules;
}
/* ------------------------------------------ */
/* ------------------------------------------ */
/* ------------------------------------------ */




add_action( 'isa_add_every_fifteen_minutes','vetpost_update_products'); //CRON JOB ACTION



function vetpost_update_products()
{
		// die();
	/* update_option( 'current_page_provet_sync_svs', 90);
	return ; */
			$headerusermame = '752-01';
			$headerpassword = 'abc123';
			$url = 'http://103.18.118.38:9180/caseacc/services/rest/VetChannel.php';
		   
		   
			// setting for fetching data of product
			$setting_productdata = array(
				'method'      => 'POST',
				'timeout'     => 20000,
				'redirection' =>10,
				'httpversion' => '1.1',
				'blocking'    => true,
				'headers'     => array(
					"username" => $headerusermame,
					"pass" => $headerpassword ,
					"Content-Type" => 'application/json'
				),
				'body' =>  array(),
			'cookies'     => array()
			);
			
			
			$branches = array(
				'PNTH',
				'HAMI',
				'CHCH'
			);
			$branchlength = count($branches);
			$current_page = get_option( 'current_page_provet_sync_svs' )??1;
			$limit_per_page = 100;
			/* echo "<br>";
			echo get_option( 'current_page_provet_sync_svs' );
			echo "<br>"; */
			// Get all products id
			 $all_ids = get_posts( array(
				'post_type' => 'product',
				// 'numberposts' =>300,
				'paged'=> $current_page,
				'posts_per_page' => $limit_per_page,
				'post_status' => 'publish',
				'fields' => 'ids',
				
			 ) );
			 
			$results = array();
			
			$count_pages = wp_count_posts(  'product' );
			$total_pages = ceil ($count_pages->publish / $limit_per_page);
			// echo $count_pages;
			// print_r($count_pages->publish);
			// echo "<br>";
			// print_r(count( $all_ids));
			// echo "<br>";
			// print_r($all_ids );
			
			
			
			// echo ($count_pages->publish / 82);
			
			   
		/* 	echo $current_page . "current_page";
			echo "<br>";
			echo "<br>";
			 echo $total_pages . "total_pages"; */
			// die();
			
			$i = 0;
			
			if(count($all_ids) > 0){
				
				/* Do while of all product to process all update product at once */
				do
				{
					$productid = $all_ids[$i];
					$_sku = get_post_meta( $productid, '_sku', true );;
				echo $productid;
					if($_sku != null){
						
						// Dynamic Request for search of product by SKU
						$setting_productdata['body'] = json_encode(array(
								"SearchProduct" => array(
									 "Code" => $_sku
								)
						));
						
						$request_productdata = wp_remote_post( $url, $setting_productdata); 
						
						if ( is_wp_error( $request_productdata ) ) {
							$error_message = $request_productdata->get_error_message();
							// echo "Something went wrong: $error_message";
							$i++;
							continue;
						} else {
							if ( is_wp_error( $request_productdata ) ||
							wp_remote_retrieve_response_code( $request_productdata ) != 200 ) {
								$i++;
								continue;
							}
							
						}
						
						// Get the data of product with supplier ID
						$response =  json_decode(wp_remote_retrieve_body( $request_productdata ) ,true);
						
						if(isset($response['status']) && $response['status'] === "Failed"){
							$i++;
							continue;
						}
						
						/* Search Procedure for SVS Stock  */
						if(isset($response['SearchProductResponse'])){
							
							$keep_going = true;
							$branchIterationCount = 0;
							$branch = $branches[0];
							$supplierCode = $response['SearchProductResponse']['Product'][0]['SupplierCode'] ;
						
							
							/* Iteration request for SOH */
							while ( $keep_going ) {
								
								$branchIterationCount = $branchIterationCount++;
								
								
								if ( !isset($branches[$branchIterationCount]) ) {
									$keep_going = false;
								}
								else{
									
									/* Dynamic Setting request for SOH */
									$settingSOH = array(
										'method'      => 'POST',
										'timeout'     => 2000,
										'redirection' =>10,
										'httpversion' => '1.1',
										'blocking'    => true,
										'headers'     => array(
											"username" => '752-01',
											"pass" => 'abc123',
											"Content-Type" => 'application/json'
										), 
										 'body'        =>  json_encode(array(
												
											"SOH_Lookup" => array(
												 "SupplierCode" => $supplierCode,
												"Branch" => $branches[$branchIterationCount]
											)
										)), 
										'cookies'     => array()
									);
									
									
									 
									$request = wp_remote_post( $url, $settingSOH); // This assumes you've set $args previously
									
									
									if ( is_wp_error( $request ) ) {
										// Error out.
										$keep_going = false;
									}
									if ( $keep_going ) {
										$status =  wp_remote_retrieve_response_code( $request );
										if ( 200 != $status ) {
											// Not a valid response.
											$keep_going = false;
										}
									}
									if ( $keep_going ) {
										
										// Get data 
										$dataSOH =  json_decode(wp_remote_retrieve_body( $request ) ,true);
										
										/* Save data to Specific product */
										if(isset($dataSOH['SOH_Response'])){
											$SOH_Response = $dataSOH['SOH_Response']['Product'][0];
											print_r($SOH_Response);
											update_post_meta( $productid, 'branch_'.$branches[$branchIterationCount], json_encode($SOH_Response ) );
										}
										/* End Save data to Specific product */
										
										$branchIterationCount++; 
										
									   }
									 
								}
								
							}
							/* End Iteration request for SOH */
						}
						/* End Search Procedure for SVS Stock  */
					}
					$i++;
				}
				while($i < count($all_ids));
				
				/* Update Next Page to update */
				 $next_page = (int)$current_page >= (int)$total_pages ? 1 :  ((int)$current_page+1);
				 // echo $next_page;
				// echo $next_page;
				update_option( 'current_page_provet_sync_svs', (int)$next_page ); 
				 
				
				/*  $file = plugin_dir_path(__FILE__) . 'errors.txt';
				$open = fopen($file, "a");
				$write = fputs($open, $response);
				fclose($open); */
				
				// die();
				/* End Do while of all product to process all update product at once */
			}	
			
			/* Update Next Page to update */
			/* $next_page = (int)$current_page >= (int)$total_pages ? 1 :  ((int)$current_page+1);
			// echo $next_page;
			update_option( 'current_page_provet_sync_svs', (int)$next_page );  */
			
			// die(s);	
	// return;			
}
	// END OF CRON JOB BLOCK






/* ------------------------------------------ */
/* ------     CRON JOB Send Now      -------- */
/* ------------------------------------------ */
/* ------------------------------------------ */


 add_action( 'isa_add_every_ten_minutes', 'update_ready_tosend' );
 function update_ready_tosend() {

		// if(isset($_GET["debugger"])){
		if(true){
		 /* $all_ids = get_posts( array(
					'post_type' => 'shop_order',
					'numberposts' =>300,
					'fields' => 'ids',
					
				 ) ); */
				 
			$headerusermame = '752-01';
			$headerpassword = 'abc123';
			$url = 'http://103.18.118.38:9180/caseacc/services/rest/VetChannel.php';
		   
		   
			// setting for fetching data of product
			$setting_productdata = array(
				'method'      => 'POST',
				'timeout'     => 20000,
				'redirection' =>10,
				'httpversion' => '1.1',
				'blocking'    => true,
				'headers'     => array(
					"username" => $headerusermame,
					"pass" => $headerpassword ,
					"Content-Type" => 'application/json'
				),
				'body' =>  array(),
			'cookies'     => array()
			);
			 $all_ids= get_posts(array(
				'numberposts' =>300,
				'post_type'=> 'shop_order',
				'post_status'=> 'wc-ready-send',
				'fields' => 'ids',
			));
				
			$i = 0;	
			 echo "<pre>";
			do{
				$orderID = $all_ids[$i];
				
				$readySendDataJson = get_post_meta( $orderID, 'ready-send-datajson', true );;
				if($readySendDataJson != null){
					$setting_productdata['body'] = json_encode(array(
								"SendOrder" => json_decode($readySendDataJson , true)
						) , true);
					print_r($setting_productdata);
					$request_productdata = wp_remote_post( $url, $setting_productdata); 
					if ( is_wp_error( $request_productdata ) ) {
						$error_message = $request_productdata->get_error_message();
						// echo "Something went wrong: $error_message";
						$i++;
						continue;
					} else {
						if ( is_wp_error( $request_productdata ) ||
						wp_remote_retrieve_response_code( $request_productdata ) != 200 ) {
							$i++;
							continue;
						}
						
					}	
					$response =  json_decode(wp_remote_retrieve_body( $request_productdata ) ,true);
						 echo "<pre>";
				 // print_r( $response);
				 
					if(!isset($response['SendOrderResponse'])){
						$i++;
						continue;
					}
					$result = $response['SendOrderResponse'];
					 echo $orderID;
					echo "<br>";
					 print_r( $result); 
					if(!isset($result['Product']) && $result['Product'] !== null && !empty($result['Product']) ){
						echo "Yest Not Update";
						  wp_update_post(array(
								'ID'    =>  $orderID,
								'post_status'   =>  'wc-svs-processing'
							));
					}else{
						if(isset($result['Result'])){
							$messageerror = $result['Result']['Message'];
							$similarword   = 'has already been received by SVS';

							if (strpos($messageerror, $similarword) !== false) {
								 wp_update_post(array(
									'ID'    =>  $orderID,
									'post_status'   =>  'wc-svs-processing'
								));
							}
						}
						$i++;
						continue;
					}
				}
				/* */
				$i++;
			}
			while($i < count($all_ids));

				 echo "<pre>";
				 print_r( $all_ids);
				 die();
			}
	}







?>