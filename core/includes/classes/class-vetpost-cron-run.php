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
	//1 hour cron job for SVS Processing
	if( !wp_next_scheduled( 'isa_add_every_sixty_minutes' ) ) 
	{
		wp_schedule_event( time(), 'every_60_mins', 'isa_add_every_sixty_minutes' ); 
	}

	// if( !wp_next_scheduled( 'isa_add_every_five_minutes' ) ) 
	// {
	// 	wp_schedule_event( time(), 'every_five_mins', 'isa_add_every_five_minutes' ); 
	// }
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

add_filter( 'cron_schedules','isa_add_every_sixty_minutes');
function isa_add_every_sixty_minutes( $schedules ) 
{
	$schedules['every_60_mins'] = array(
		'interval' => 60*60,
		'display'  => __( 'Every 60 minutes' )
	);
	return $schedules;
}

// add_filter( 'cron_schedules','isa_add_every_five_minutes');
// function isa_add_every_five_minutes( $schedules ) 
// {
// 	$schedules['every_60_mins'] = array(
// 		'interval' => 60*5,
// 		'display'  => __( 'Every 5 minutes' )
// 	);
// 	return $schedules;
// }
/* ------------------------------------------ */
/* ------------------------------------------ */
/* ------------------------------------------ */




add_action( 'isa_add_every_fifteen_minutes','vetpost_update_products'); //CRON JOB ACTION

add_action( 'vetpost_update_products_action','vetpost_update_products'); 

function vetpost_update_products()
{
	// echo "fasdfa";
		// die();
	/* update_option( 'current_page_provet_sync_svs', 90);
	return ; */
			// $headerusermame = '752-01';
			// $headerpassword = 'abc123';
			// $url = 'http://103.18.118.38:9180/caseacc/services/rest/VetChannel.php';
			
			$headerusermame = '1021492-06';
			$headerpassword = 'VP2023!';
			$url = 'http://vetchannel.svs.co.nz:7001/caseacc/services/rest/VetChannel.php';
		   
		   
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
			$limit_per_page = 200;
			/* echo "<br>";
			echo get_option( 'current_page_provet_sync_svs' );
			echo "<br>"; */
			// Get all products id
			 $all_ids = get_posts( array(
				'post_type' => 'product',
				// 'numberposts' =>300,
				'paged'=> $current_page,
				'posts_per_page' => $limit_per_page,
				// 'post_status' => 'publish',
				'fields' => 'ids',
				
			 ) );
			 
			$results = array();
			
			$count_pages = wp_count_posts(  'product' );
			$total_pages = ceil ($count_pages->publish / $limit_per_page);
			/* echo "current_page".$current_page."current_page <br>";
			// print_r($count_pages->publish);
			echo "<pre>";
			echo "count ";
			print_r( count($all_ids));
			print_r( $all_ids);
			echo "count "; */
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
					$_sku = get_post_meta( $productid, '_sku', true );
					$product = wc_get_product($productid);

					if( $product->is_type( 'simple' ) ){
							// simple product
							// echo is_a( $productid, 'WC_Product_Variable' );
						// continue;
				
						if($_sku != null && false){
							
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
												"username" => $headerusermame,
												"pass" => $headerpassword,
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
											//print_r($dataSOH);
											/* Save data to Specific product */
											if(isset($dataSOH['SOH_Response'])){
												$SOH_Response = $dataSOH['SOH_Response']['Product'][0];
												
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
							
					} elseif( $product->is_type( 'variable' ) ){
					   // variable product
					   echo "<br>";
					   echo "<pre>";
					   echo $productid ;
					   print_r($product->get_children( false ));
					   
					   
					   $get_children = $product->get_children( false );
					   $_variation_i = 0;
					   while($_variation_i < count($get_children))
						{
							$_variation_id =  $get_children[$_variation_i];
							
							
							$variation = wc_get_product( $_variation_id); 
							 if ( ! $variation || ! $variation->exists() ) {
								$_variation_i++;
								continue;
							}
							
							 
							 if($variation->get_sku() != null){
								
								
								$_variation_sku = $variation->get_sku();
								$setting_productdata['body'] = json_encode(array(
													"SearchProduct" => array(
														 "Code" => $_variation_sku
													)
											));
								print_r($setting_productdata);
								
								
								
								$request_productdata = wp_remote_post( $url, $setting_productdata); 
								
								if ( is_wp_error( $request_productdata ) ) {
									$error_message = $request_productdata->get_error_message();
									// echo "Something went wrong: $error_message";
									$_variation_i++;
									continue;
								} else {
									if ( is_wp_error( $request_productdata ) ||
									wp_remote_retrieve_response_code( $request_productdata ) != 200 ) {
										$_variation_i++;
										continue;
									}
									
								}
								
								// Get the data of product with supplier ID
								$response =  json_decode(wp_remote_retrieve_body( $request_productdata ) ,true);
								
								if(isset($response['status']) && $response['status'] === "Failed"){
									$_variation_i++;
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
													"username" => $headerusermame,
													"pass" => $headerpassword,
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
											
											
											 print_r(get_post_meta( $_variation_id, 'branch_'.$branches[$branchIterationCount]));
							
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
												print_r($dataSOH);
												/* Save data to Specific product */
												if(isset($dataSOH['SOH_Response'])){
													$SOH_Response = $dataSOH['SOH_Response']['Product'][0];
													
													update_post_meta( $_variation_id, 'branch_'.$branches[$branchIterationCount], json_encode($SOH_Response ) );
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
							
							$_variation_i++;
						}
					   
					   
					}
					
					
					$i++;
				}
				while($i < count($all_ids));
				
				/* Update Next Page to update */
				 $next_page = (int)$current_page >= (int)$total_pages ? 1 :  ((int)$current_page+1);
				 // echo $next_page;
				// echo $next_page;
				update_option( 'current_page_provet_sync_svs', (int)$next_page ); 
				 // echo $next_page;
				
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
			
			// die();	
	// return;				
}
	// END OF CRON JOB BLOCK






/* ------------------------------------------ */
/* ------     CRON JOB Send Now      -------- */
/* ------------------------------------------ */
/* ------------------------------------------ */


 // add_action( 'isa_add_every_ten_minutes', 'update_ready_tosend' );
 
 add_action( 'update_ready_tosend_action','update_ready_tosend'); 
 function update_ready_tosend() {

		// if(isset($_GET["debugger"])){
		 /* $all_ids = get_posts( array(
					'post_type' => 'shop_order',
					'numberposts' =>300,
					'fields' => 'ids',
					
				 ) ); */
				 
			$headerusermame = '1021492-06';
			$headerpassword = 'VP2023!';
			$url = 'http://vetchannel.svs.co.nz:7001/caseacc/services/rest/VetChannel.php';
			
			//$headerusermame = '1021492-06';
			//$headerpassword = 'VP2023!';
			//$url = 'http://vetchannel.svs.co.nz:7001/caseacc/services/rest/VetChannel.php';
		   
		   
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
			 // echo "<pre>";
			 if(count($all_ids) > 0){
				do{
					$orderID = $all_ids[$i];
					
					$readySendDataJson = get_post_meta( $orderID, 'ready-send-datajson', true );

					if($readySendDataJson != null){
						$setting_productdata['body'] = json_encode(array(
									"SendOrder" => json_decode($readySendDataJson , true)
							) , true);
						
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

						if(!isset($response['SendOrderResponse'])){
							$i++;
							continue;
						}

						$result = $response['SendOrderResponse'];
						$purchaceOrder = $result['OrderNumber'];
						if($purchaceOrder !== null && !empty($purchaceOrder)){
							update_post_meta( $orderID, 'SVSOrderNumber', $purchaceOrder);
						}

						if(!isset($result['Product']) && $result['Product'] !== null && !empty($result['Product']) ){
							echo "Yes Not Update";
								wp_update_post(array(
										'ID'    =>  $orderID,
										'post_status'   =>  'wc-svs-processing',
									
									));
								plugin_log( [
									'Action' => 'Automation of update Send Now to SVS Processing' , 
									"Post_ID" => $orderID,
									"Status" => "Success"
								] );
											
						}else{
							if(isset($result['Result'])){
								$messageerror = $result['Result']['Message'];
								$similarword   = 'has already been received by SVS';

								if (strpos($messageerror, $similarword) !== false) {
									 wp_update_post(array(
										'ID'    =>  $orderID,
										'post_status'   =>  'wc-svs-processing',									
									));
									plugin_log( [
										'Action' => 'Automation of update Send Now to SVS Processing' , 
										"Post_ID" => $orderID,
										"Status" => "Success"
									] );

								}
							}
							$i++;
							continue;
						}
					}

					$i++;
				}
				while($i < count($all_ids));

			}

	}

/* ------------------------------------------ */
/* ------ CRON JOB Get Invoice Response ----- */
/* ------------------------------------------ */
/* ------------------------------------------ */


// add_action( 'isa_add_every_five_minutes', 'update_get_invoice' );
 
// add_action( 'update_get_invoice_action','update_get_invoice'); 
// function update_get_invoice() {

// 		   $headerusermame = '752-01';
// 		   $headerpassword = 'abc123';
// 		   $url = 'http://103.18.118.38:9180/caseacc/services/rest/VetChannel.php';
		   
// 		   //$headerusermame = '1021492-06';
// 		   //$headerpassword = 'VP2023!';
// 		   //$url = 'http://vetchannel.svs.co.nz:7001/caseacc/services/rest/VetChannel.php';
		  
// 		   // setting for fetching data of invoice
// 		   $setting_invoicedata = array(
// 			   'method'      => 'POST',
// 			   'timeout'     => 20000,
// 			   'redirection' =>10,
// 			   'httpversion' => '1.1',
// 			   'blocking'    => true,
// 			   'headers'     => array(
// 				   "username" => $headerusermame,
// 				   "pass" => $headerpassword ,
// 				   "Content-Type" => 'application/json'
// 			   ),
// 			   'body' =>  array(),
// 		   'cookies'     => array()
// 		   );

// 			$all_ids= get_posts(array(
// 			   'numberposts' =>300,
// 			   'post_type'=> 'shop_order',
// 			   'post_status'=> 'wc-svs-processing',
// 			   'fields' => 'ids',
// 		   ));
			   
// 		   $i = 0;	
// 			// echo "<pre>";
// 			if(count($all_ids) > 0){
// 			   do{
// 				   $orderID = $all_ids[$i];

// 				//    2408514 test data w/ data

// 				//    2804158 test data w/o data

// 				   $invoiceDataJson = get_post_meta( $orderID, 'SVSOrderNumber', true );

// 				   if($invoiceDataJson != null){
// 					   $setting_invoicedata['body'] = json_encode(array(
// 						   "GetInvoice" => array(
// 							"PurchaseOrder" => $invoiceDataJson
// 							)
// 					   ));

// 					   $request_invoicedata = wp_remote_post( $url, $setting_invoicedata); 
// 					   if ( is_wp_error( $request_invoicedata ) ) {
// 						   $error_message = $request_invoicedata->get_error_message();
// 						   // echo "Something went wrong: $error_message";
// 						   $i++;
// 						   continue;
// 					   } else {
// 						   if ( is_wp_error( $request_invoicedata ) ||
// 						   wp_remote_retrieve_response_code( $request_invoicedata ) != 200 ) {
// 							   $i++;
// 							   continue;
// 						   }

// 					   }	
// 					   $invoiceresponse =  json_decode(wp_remote_retrieve_body( $request_invoicedata ) ,true);

// 					   if(!isset($invoiceresponse['GetInvoiceResponse'])){
// 						   $i++;
// 						   continue;
// 					   }

// 					   $invoice_result = $invoiceresponse['GetInvoiceResponse']['Invoice'];
					   
// 					   if($invoice_result !== null && !empty($invoice_result)){

// 						$arrcount = count($invoice_result);

// 						for($j = 0; $j <  $arrcount; $j++){
// 							 $parse_invoice = $invoice_result[$j];
// 							 $invoiceCustomerOderNo = $parse_invoice['Header']['CustomerOrderNo'];
// 							//  echo "<pre>";
// 							//  print_r($invoiceCustomerOderNo);

// 							 update_post_meta( $orderID, 'Customer Order Number', $invoiceCustomerOderNo);
// 						 };

						 

// 						// $parse_result = $invoice_result['Consignment'][0];

// 					   }

// 				   }

// 				   $i++;
// 			   }
// 			   while($i < count($all_ids));
			   
// 			//    echo "<pre>";
// 			//    print_r($invoice_result);
// 			// // //    echo "<pre>";
// 			// //    print_r($parse_invoice);
// 			//    echo "<pre>";
// 			//    print_r($invoiceCustomerOderNo);
// 			//    die();
// 		   }
//    }

	


/* ------------------------------------------ */
/* ------  CRON JOB SVS Processing   -------- */
/* ------------------------------------------ */
/* ------------------------------------------ */


function filter_lines_send_data($n)
{
	return array("Code" => (int)$n['Code'] , "Quantity" => (int)$n['Quantity'] );
}
function filter_lines_invoice($n)
{
	return array("Code" => (int)$n['SupplierCode'] , "Quantity" => (int)$n['Quantity'] );
}

//add_action( 'isa_add_every_sixty_minutes', 'check_trackinginfo_svsprocessing' );

add_action( 'check_trackinginfo_svsprocessing_action','check_trackinginfo_svsprocessing'); 

function check_trackinginfo_svsprocessing() {
		global $wpdb;	 
		$headerusermame = '1021492-06';
		$headerpassword = 'VP2023!';
		$url = 'http://vetchannel.svs.co.nz:7001/caseacc/services/rest/VetChannel.php';
		
		//$headerusermame = '1021492-06';
		//$headerpassword = 'VP2023!';
		//$url = 'http://vetchannel.svs.co.nz:7001/caseacc/services/rest/VetChannel.php';
	   
	   
		// setting for fetching data of product
		$setting_trackinginfo = array(
			'method'      => 'POST',
			'timeout'     => 20000,
			'redirection' => 10,
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
			'post_type'=> 'shop_order',
			'post_status'=> 'wc-svs-processing',
			'fields' => 'ids',
			'nopaging' => true
		));

		$i = 0;	
	
		if(count($all_ids) > 0){
			do {
				$invoiceCustomerOderNo = null;
				$orderID = $all_ids[$i];
				
				$readysenddatajson = get_post_meta( $orderID, 'ready-send-datajson', true );
				$PurchaseOrderNo = get_post_meta( $orderID, 'SVSOrderNumber', true );
				$readysenddatajson  = json_decode($readysenddatajson ,true);
				
				if($readysenddatajson === null && metadata_exists( 'post', $orderID, 'SVSOrderNumber' )){
					echo "Yes Nulled";
					$i++;
					continue;
				}
				if(!isset($readysenddatajson['Product'])){
					$i++;
					continue;
				}else{
					$readysenddataproduct = $readysenddatajson['Product'];
				}
			
				/* foreach ($order->get_items() as $item_key => $item_values):
				   $product = new WC_Product($item_id);
				   $item_sku[] = $product->get_sku();
				endforeach; */
				
				/* $invoiceDataJson = get_post_meta( $orderID, 'SVSOrderNumber', true );

				if($invoiceDataJson != null){ */
					
					
					$setting_trackinginfo['body'] = json_encode(array(
						"GetInvoice" => array(
						 "PurchaseOrder" => $PurchaseOrderNo
						 )
					));
				
					$request_invoicedata = wp_remote_post( $url, $setting_trackinginfo); 
					if ( is_wp_error( $request_invoicedata )  ) {
						$error_message = $request_invoicedata->get_error_message();
						// echo "Something went wrong: $error_message";
						$i++;
						continue;
					} else {
						if ( is_wp_error( $request_invoicedata ) ||
						wp_remote_retrieve_response_code( $request_invoicedata ) != 200 ) {
							$i++;
							continue;
						}

					}	
					$invoiceresponse =  json_decode(wp_remote_retrieve_body( $request_invoicedata ) ,true);
					echo "<pre>";
					
					
					$readysenddataproduct = array_map('filter_lines_send_data', $readysenddataproduct);
					
					// $readysenddataproduct = array_map('filter_lines', $readysenddataproduct);
					
					
						echo $orderID;
						echo $PurchaseOrderNo;
		
					if(isset($invoiceresponse['GetInvoiceResponse'])){
						if(isset($invoiceresponse['GetInvoiceResponse']['Invoice']['Header'])){
							echo "true, Single Array";
							$GetInvoiceResponse = $invoiceresponse['GetInvoiceResponse']['Invoice'];			
							$invoiceCustomerOderNo = $GetInvoiceResponse['Header']['CustomerOrderNo'];
							$invoiceCustomerLineItems = $GetInvoiceResponse['Line'];
						}else{
							
							// $GetInvoiceResponse = json_decode('{"GetInvoiceResponse":{"Invoice":[{"Header":{"SupplierName":"SVS Veterinary Supplies","PurchaseOrder":"2408514","InvoiceNo":"2408514","Freight":"0.0000","TotalGST":"4.4970","CustomerOrderNo":"1054","Date":"2020-08-28 10:10:02","DueDate":"2020-09-20","ShippingAddress":"STD"},"Line":[{"SupplierCode":"12476","Description":"Advantage Dog XLarge 25kg+ 4","Ordered":"1","Quantity":"1","PricePerUnit":"29.9800","GSTPerUnit":"4.4970","ListPrice":"33.23"}]},{"Header":{"SupplierName":"SVS Veterinary Supplies","PurchaseOrder":"2408514","InvoiceNo":"2408514-1","Freight":"0.0000","TotalGST":"-4.4970","CustomerOrderNo":"1054","Date":"2020-08-28 14:14:36","DueDate":"2020-09-20","ShippingAddress":"STD"},"Line":[{"SupplierCode":"12476","Description":"Advantage Dog XLarge 25kg+ 4","Ordered":"1","Quantity":"-1","PricePerUnit":"29.9800","GSTPerUnit":"4.4970","ListPrice":"33.23"}]}]}}' , true);
							echo "False, Multi Array";
							$GetInvoiceResponse = $invoiceresponse['GetInvoiceResponse']['Invoice'][0];
							$invoiceCustomerOderNo = $GetInvoiceResponse['Header']['CustomerOrderNo'];
							$invoiceCustomerLineItems = $GetInvoiceResponse['Line'];
							// print_r($GetInvoiceResponse['GetInvoiceResponse']['Invoice'][0]['Header']['CustomerOrderNo']);		
							// print_r($invoiceresponse['GetInvoiceResponse']['Invoice'][0]);
						}
						
						$invoicelineitemproduct = array_map('filter_lines_invoice', $invoiceCustomerLineItems);
					
					/* echo "<br>";
						echo "invoicelineitemproduct";
						var_dump($invoicelineitemproduct);
						echo "<br>";	
						echo "readysenddataproduct";
						var_dump($readysenddataproduct ); */
					
						if($invoicelineitemproduct === $readysenddataproduct ){
								echo "Yes Equal";
								
								$CustomerOrderNo =  $invoiceCustomerOderNo;
								// print_r($CustomerOrderNo);
								
								// Dynamic Request for SVS Processing by Order ID - If tracking info is available
								$setting_trackinginfo['body'] = json_encode(array(
										"trackingInfo" => array(
										"orderIds" => $CustomerOrderNo
										)
								));
								print_r($setting_trackinginfo['body']); 
								$request_trackinginfo = wp_remote_post( $url, $setting_trackinginfo); 
								
								if ( is_wp_error( $request_trackinginfo ) ) {
									$error_message = $request_trackinginfo->get_error_message();
									$i++;
									continue;
								} else {
									if ( is_wp_error( $request_trackinginfo ) ||
									wp_remote_retrieve_response_code( $request_trackinginfo ) != 200 ) {
										$i++;
										continue;
									}
									
								}
								
								// Get the tracking info of the order if available
								$response =  json_decode(wp_remote_retrieve_body( $request_trackinginfo ) ,true);
								 echo "<pre>";
								print_r($response); 
								if(isset($response['status']) && $response['status'] === "Failed"){
									
									$i++;
									continue;
								}
								
								if(!isset($response['trackingInfoResponse'])){
									$i++;
									continue;
								}
								
								$result = $response['trackingInfoResponse'];
								
								$parse_result = $result['Consignment'][0];

								$trackinglink = $parse_result['trackingLink'];
								$consignmentNo =  $parse_result['consignmentNo'];
								$courierId =  $parse_result['courierId'];
								
								if(isset($parse_result) && $parse_result !== null && !empty($parse_result) ){
									if(count($parse_result) > 0){
										
										/* $meta_key = 'SVSOrderNumber';

										$data = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key) , ARRAY_N  );
										echo printf("SELECT * FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key);
										print_r($data); */
										
										$WP_post_id = $orderID ;
										
										
										if ( get_post_status( $WP_post_id ) === "wc-svs-processing") {
											// do stuff
											
											$order = new WC_Order($WP_post_id);

											if (!empty($order)) {
												$note_tracking_number = __("This order has been shipped with: ".$courierId.". The Tracking Number is: ".$consignmentNo.". The Url is: ".$trackinglink."");
												$note = __("Order ".$order->get_order_number()."status changed from SVS Processing to Completed.");
												$orderNo = $order->get_id(); 
																								
												// Append an array entry to the uploads/plugin.log file.
												plugin_log( [
													'Action' => 'Automation of update SVS Processing to Completed' , 
													"Post_ID" => $WP_post_id,
													"Order_ID" => $orderNo,
													"Status" => "Success"
												] );
												
												
												$order->update_status( 'completed' );
												
												// Add the note
												$order->add_order_note($note);
												$order->save();

												// specify the order_id so WooCommerce knows which to update
												$order_data = array(
													'order_id' => $WP_post_id,
													'customer_note' => $note_tracking_number
												);
												// update the customer_note on the order
												wc_update_order( $order_data );
												
											
											}

											// change order status from svs processing to completed
											//  wp_update_post(array(
											// 	'ID'    =>  $WP_post_id,
											// 	'post_status'   =>  'wc-completed'
											// ));

										}else{
											
											plugin_log( [
													'Action' => 'Automation of update SVS Processing to Completed' , 
													"Post_ID" => $WP_post_id,
													"Order_ID" => $orderNo,
													"Status" => "Failed",
													"Message" => "Not wc-svs-processing status"
												] );
										}
										
									}						
								}else{

									/* wp_update_post(array(
										'ID'    =>  $orderID
									));
									// If you don't have the WC_Order object (from a dynamic $order_id)
									$order = wc_get_order($orderID);
									// The text for the note
									$note = __("Unable to track due to No valid consignments available for you ");
									// Add the note
										$order->add_order_note($note);
										$order->save();

									// specify the order_id so WooCommerce knows which to update
									$order_data = array(
										'order_id' => $orderID,
										'customer_note' => $note
									);
									// update the customer_note on the order
									wc_update_order( $order_data ); */

									$i++;
									continue;
								}
						}
						else{
							echo "Not Equal";
							$WP_post_id = $orderID ;
											$order = new WC_Order($WP_post_id);
											$orderNo = $order->get_id(); 
							plugin_log( [
								'Action' => 'Automation of update SVS Processing to Completed' , 
								"Post_ID" => $WP_post_id,
								"Order_ID" => $orderNo,
								"Status" => "Failed",
								"Message" => "Not Equal Products"
							] );
							$i++;
							continue;
						}
				
					
				
				}
				
				$i++;
			}
			while($i < count($all_ids));
			// echo "<pre>";
			// print_r($request_trackinginfo['body']);
			// echo "<pre>";
			// print_r($parse_invoice);

			// echo "<pre>";
			// print_r($CustomerOrderNo);
			// echo "<pre>";
			// print_r($parse_result);
			// die();
		}
}


/**
 * Write an entry to a log file in the uploads directory.
 * 
 * @since x.x.x
 * 
 * @param mixed $entry String or array of the information to write to the log.
 * @param string $file Optional. The file basename for the .log file.
 * @param string $mode Optional. The type of write. See 'mode' at https://www.php.net/manual/en/function.fopen.php.
 * @return boolean|int Number of bytes written to the lof file, false otherwise.
 */
if ( ! function_exists( 'plugin_log' ) ) {
  function plugin_log( $entry, $mode = 'a', $file = 'SVSLOGS' ) { 
    // Get WordPress uploads directory.
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];
    // If the entry is array, json_encode.
    if ( is_array( $entry ) ) { 
      $entry = json_encode( $entry ); 
    } 
    // Write the log file.
    $file  = $upload_dir . '/' . $file . '.log';
	echo $file ;
    $file  = fopen( $file, $mode );
    $bytes = fwrite( $file, current_time( 'mysql' ) . "::" . $entry . "\n" ); 
    fclose( $file ); 
    return $bytes;
  }
}


?>