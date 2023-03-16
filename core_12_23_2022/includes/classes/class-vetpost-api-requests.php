<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

require VETPOSTAPI_PLUGIN_DIR . 'vendor/autoload.php';

use Automattic\WooCommerce\Client;


/**
 * Class Vetpost_Api_Requests
 *
 * Thats where we bring the plugin to life
 *
 * @package		VETPOSTAPI
 * @subpackage	Classes/Vetpost_Api_Requests
 * @author		Jon Doe
 * @since		1.0.0
 */
class Vetpost_Api_Requests{

	/**
	 * Our Vetpost_Api_Requests constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		

	}


	

    public function vetpost_orders_ui()
    {
        // wp_enqueue_style( 'vetpostapi-select2-css', "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", array(), VETPOSTAPI_VERSION);
		// wp_enqueue_script( 'vetpostapi-select2-js', "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", VETPOSTAPI_VERSION);
		wp_enqueue_style( 'vetpostapi-bscdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css', array(), VETPOSTAPI_VERSION);
		wp_enqueue_script( 'vetpostapi-bsscriptcdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', VETPOSTAPI_VERSION, true );
		wp_enqueue_style( 'vetpostapi-bsSelect-css', "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css", array(), VETPOSTAPI_VERSION);
		wp_enqueue_script( 'vetpostapi-bsSelect-js', "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js", VETPOSTAPI_VERSION);
		wp_enqueue_style( 'vetpostapi-jqueryDataTable-css', "https://cdn.datatables.net/v/bs5/dt-1.12.1/r-2.3.0/datatables.min.css", array(), VETPOSTAPI_VERSION);
		wp_enqueue_script( 'vetpostapi-jqueryDataTable-js', "https://cdn.datatables.net/v/bs5/dt-1.12.1/r-2.3.0/datatables.min.js", VETPOSTAPI_VERSION, true );
		wp_enqueue_script( 'vetpostapi-feathericons', 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js', VETPOSTAPI_VERSION, true );
		?>

<div class="_container">

        <div class="_side-nav">

		<div class="_sideNavContent">
		<h1 class="_logo">vetpost</h1>	
		<div>
            <div class="_side-button _side-button-active" id="all_orders" data-bs-toggle="collapse" data-bs-target="#demo"><span class="_biIcons"><i class="bi bi-table"></i></span> Orders</div>
                <div class="_order-links collapse" id="demo">
                    
                    <div class="_order-link _order-link-active" id="all_orders">All</div>
                    <div class="_order-link" id="processing">Processing</div>
					<div class="_order-link" id="pe-nding">Pending</div>
                    <div class="_order-link" id="on-hold">On Hold</div>
                    <div class="_order-link" id="completed">Completed</div>
                    <div class="_order-link" id="refunded">Refunded</div>
                    <div class="_order-link" id="failed">Failed</div>
                    <div class="_order-link" id="checkout-draft">Draft</div>


                </div>

            <div class="_side-button" id="track_order"><span class="_biIcons"><i class="bi bi-truck"></i></span> Track Order</div>


        	</div>
		</div>
            

         


        <div class="_main-content">
            <div class="_top-nav"></div>

			<div class="_table-contents p-4 container">



				<!-- ORDER PAGES -->
				<section class="pages p-4 _tableContainer" id="all_orders">
				<div class="">
					<table id="_mainTable" class="table table-responsive" style="width: 100% !important;">
						<thead>
							<tr>
								<th>Order No.</th>
								<th>Status</th>
								<th>Date Send</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				</section>
				<!-- END OF ORDER PAGES  -->

				

			</div>


			<div class="_editForm m-3 p-5" hidden>


				<div class="_formHeader ">
					<h3>Order #<span class="_orderNumberDetails"></span> Details</h3>
					<button type="button" class="btn btn-primary _btnBack"><i class="bi bi-arrow-return-left"></i></button>
				</div>
				
				<div class="_editFormContent ">

					<form id="updateForm">
						<!-- GENERAL BILLING SHIPPING -->
						<div class="row">

						<div class="col-4">

							<div class="row p-1">

								<h5 ng-click="EditProduct()">General</h5>

								<div class="col-12 mb-2">
									<label  for="date_created">Date Created</label>
									<input type="datetime-local"  class="form-control">
								</div>

								<div class="col-12 mb-2">
                                    <div>
										<label for="status">Status</label>
										<select class="form-control" id="status" >
											<option value="processing">Processing</option>
											<option value="pending">Pending</option>
											<option value="on-hold">OnHold</option>
											<option value="completed">Completed</option>
											<option value="refunded">Refunded</option>
											<option value="failed">Failed</option>
											<option value="checkout-draft">Draft</option>
                                        </select>
                                    </div>
                                </div>

								<div class="col-12 mb-2">
                                    <div>
                                        <label  for="customer">Customer</label>
                                        <input type="text" class="form-control" id="customer">
                                    </div>
                                </div>


							</div>

						</div>

						<div class="col-4">

							<div class="row">

								<h5>Billing</h5>
								
								<div class="row">

								<div class="col-6 mb-2">
                                    <div>
                                        <label  for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name">
                                    </div>
                                </div>
								
								<div class="col-6 mb-2">
                                    <div>
                                        <label  for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name">
                                    </div>
                                </div>

								<div class="col-12 mb-2">
                                    <div>
                                        <label  for="company">Company</label>
                                        <input type="text" class="form-control" id="company" >
                                    </div>
                                </div>

								<div class="col-6 mb-2">
                                    <div>
                                        <label  for="address_1">Address 1</label>
                                        <input type="text" class="form-control" id="address_1" >
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <div>
										<label  for="address_2">Address 2</label>
                                        <input type="text" class="form-control" id="address_2" >
                                    </div>
                                </div>
								
                                <div class="col-6 mb-2">
                                    <div>
										<label  for="city">City</label>
                                        <input type="text" class="form-control" id="city" >
                                    </div>
                                </div>
								
                                <div class="col-6 mb-2">
                                    <div>
										<label  for="postcode">Postcode</label>
                                        <input type="text" class="form-control" id="postcode" >
                                    </div>
                                </div>

                             
								
                                <div class="col-6 mb-2">
                                    <div>
										<label for="payment_method">Country/Region</label>
										<select class="form-control" id="country" >
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select>
                                    </div>
                                </div>

								<div class="col-6 mb-2">
                                    <div>
										<label for="payment_method">State/County</label>
										<select class="form-control" id="country" >
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select>
                                    </div>
                                </div>

								<div class="col-6 mb-2">
                                    <div>
                                        <label for="email">Email Address</label>
                                        <input type="text" class="form-control" id="email">
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-2">
                                    <div>
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone">
                                    </div>
                                </div>

								<div class="col-12 mb-2">
                                    <div>
										<label for="payment_method">Payment Method</label>
                                        <select class="form-control" id="payment_method" >
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select>
                                    </div>
                                </div>

								<div class="col-12">
								<div>
										<label for="transaction_id">Transaction ID</label>
                                        <select class="form-control" id="transaction_id" >
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select>
                                    </div>
								</div>

								<!-- <div class="col-12">
                                    <div>
                                        <select type="text" class="form-control" id="transaction_id">
                                        <label for="transaction_id">Transaction ID</label>
                                    </div>
                                </div> -->

								</div>

							</div>

						</div>

						<div class="col-4">

						<div class="row">

							<h5>Shipping</h5>

							<div class="row">

							<div class="col-6 mb-2">
								<div>
									<label  for="first_name">First Name</label>
									<input type="text" class="form-control" id="first_name">
								</div>
							</div>

							<div class="col-6 mb-2">
								<div>
									<label  for="last_name">Last Name</label>
									<input type="text" class="form-control" id="last_name">
								</div>
							</div>

							<div class="col-12 mb-2">
								<div>
									<label  for="company">Company</label>
									<input type="text" class="form-control" id="company" >
								</div>
							</div>

							<div class="col-6 mb-2">
								<div>
									<label  for="address_1">Address 1</label>
									<input type="text" class="form-control" id="address_1" >
								</div>
							</div>

							<div class="col-6 mb-2">
								<div>
									<label  for="address_2">Address 2</label>
									<input type="text" class="form-control" id="address_2" >
								</div>
							</div>

							<div class="col-6 mb-2">
								<div>
									<label  for="city">City</label>
									<input type="text" class="form-control" id="city" >
								</div>
							</div>

							<div class="col-6 mb-2">
								<div>
									<label  for="postcode">Postcode</label>
									<input type="text" class="form-control" id="postcode" >
								</div>
							</div>



							<div class="col-6 mb-2">
								<div>
									<label for="payment_method">Country/Region</label>
									<select class="form-control" id="country" >
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
									</select>
								</div>
							</div>

							<div class="col-6 mb-2">
								<div>
									<label for="payment_method">State/County</label>
									<select class="form-control" id="country" >
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
									</select>
								</div>
							</div>

							
							<div class="col-6 mb-2">
								<div>
									<label for="phone">Phone</label>
									<input type="text" class="form-control" id="phone">
								</div>
							</div>

							<div class="col-12 mb-2">
								<div>
									<label for="payment_method">Payment Method</label>
									<select class="form-control" id="payment_method" >
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
									</select>
								</div>
							</div>

							<div class="col-12">
							<div>
									
							</div>
							</div>

							<!-- <div class="col-12">
								<div>
									<select type="text" class="form-control" id="transaction_id">
									<label for="transaction_id">Transaction ID</label>
								</div>
							</div> -->

							</div>

							</div>

						</div>

						


						</div>

						<!-- END OF GENERAL BILLING SHIPPING -->

						<!-- PRODUCTS -->
				

							<div class="card _productCard">
							
							
							<div class="card-body _productTableList">
								

							<table class="table _tableList">

									<thead style="background-color: #F1F3FA;">
										<tr>
											<th scope="col" hidden col>ID</th>
											<th scope="col" class="w-75" col>Product</th>
											<th scope="col">Cost</th>
											<th scope="col" class="text-center">Qty</th>
											<th scope="col">Total</th>
											<th scope="col">GST</th>
										</tr>
									</thead>
								<tbody id="_addedItems">
									<!-- <tr class="m-2">
										<th scope="row" hidden>1</th>
										<th scope="row" class="w-75 pt-4 pb-4">
											<div class="_productColumn">
												<div class="_productColumnImg">
													<img 
														src="https://kruuse.com/Admin/Public/GetImage.ashx?width=800&height=800&crop=5&FillCanvas=True&DoNotUpscale=true&Compression=75&image=/Files/Images/products/372095.jpg" 
														class="img-fluid"
														alt=""
													>
												</div>

												<div class="_productColumnText">
													<p>Oxbow Critical Care Fine Grind 100g</p>
													<p>SKU: <span>17720-1</span></p>
												</div>
											</div>
										</th>
										<td>$0.00</td>
										<td class="text-center">0</td>
										<td>$0.00</td>
										<td>$0.00</td>

									</tr>
									<tr class="m-2">
										<th scope="row" hidden>1</th>
										<th scope="row" class="w-75 pt-4 pb-4">
											<div class="_productColumn">
												<div class="_productColumnImg">
													<img 
														src="https://kruuse.com/Admin/Public/GetImage.ashx?width=800&height=800&crop=5&FillCanvas=True&DoNotUpscale=true&Compression=75&image=/Files/Images/products/372095.jpg" 
														class="img-fluid"
														alt=""
													>
												</div>

												<div class="_productColumnText">
													<p>Oxbow Critical Care Fine Grind 100g</p>
													<p>SKU: <span>17720-1</span></p>
												</div>
											</div>
										</th>
										<td>$0.00</td>
										<td class="text-center">0</td>
										<td>$0.00</td>
										<td>$0.00</td>

									</tr>
									<tr class="m-2">
										<th scope="row" hidden>1</th>
										<th scope="row" class="w-75 pt-4 pb-4">
											<div class="_productColumn">
												<div class="_productColumnImg">
													<img 
														src="https://prescriptionfood.com.hk/wp-content/uploads/2016/05/CALM-RIGHT.png" 
														class="img-fluid"
														alt=""
													>
												</div>

												<div class="_productColumnText">
													<p>Oxbow Critical Care Fine Grind 100g</p>
													<p>SKU: <span>17720-1</span></p>
												</div>
											</div>
										</th>
										<td>$0.00</td>
										<td class="text-center">0</td>
										<td>$0.00</td>
										<td>$0.00</td>
									</tr> -->
								</tbody>
							</table>


							</div>


							<div class="_footerContainer">
								<div class="card-footer _productTotalStyle">
								
									<div class="_productCardTotal p-2">
										
										<table class="table table-responsive text-end _cardTotalTable">
											<tbody>
												<tr>
													<td>Items Subtotal:</td>
													<th><span id="_itemSubtotal">$0.00</span></th>
												</tr>
												<tr>
													<td>GTS:</td>
													<th><span id="_itemGts">$0.00</span></th>
												</tr>
												<tr>
													<td>Order Total:</td>
													<th><span id="_itemOrderTotal">$0.00</span></th>
												</tr>
											</tbody>
										</table>

								</div>
								</div>
							</div>



							<div class="_footerContainer">
								<div class="card-footer _productFooterStyle">
									<div class="_productCardFooter">
										<button type="button" class="btn btn-sm btn-outline-primary _showAddProductOption">Add Item(s)</button>
									</div>

									<div class="_productCardOption" hidden>
										<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#_addProductModal">Add product(s)</button>
										
										<button type="button" class="btn btn-sm btn-outline-primary">Add fee</button>

										<button type="button" class="btn btn-sm btn-outline-primary">Add shipping</button>

										<button type="button" class="btn btn-sm btn-outline-primary">Add tax</button>
										
										<button type="button" class="btn btn-sm btn-outline-primary _hideAddProductOption">Cancel</button>

										<button type="button" class="btn btn-sm btn-primary">Save</button>


									</div>

								</div>
								
							</div>
							
							</div>
					

						<!-- END OF PRODUCTS -->
						
					</form>

				</div>


			</div>


			<!-- SPINNER -->
			<div class="modal bounceIn" id="spinnerModal">
            <div class="modal-dialog modal-dialog-centered">

              <div class="modal-content spinner-content">
  
                <div class="modal-body">

                  <div class="spinner-container">
                    <div class="spinner-border text-primary"></div>
                  </div>

                </div>
          
              </div>

            </div>

        </div>
        <!-- END OF SPINNER -->

		


		<!-- ADD PROUCT(S) -->
		
			<div class="modal fade" id="_addProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog  modal-dialog-centered modal-lg">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="staticBackdropLabel">Add Product(s)</h1>
					<button type="button" id="_closeAddProduct" class="btn-close" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<div class="row">
					<div class="_productTable  table-responsive p-2">

						<table class="table table-responsive table-borderless">
							<thead>
								<tr>
									<th colspan="2">Product</th>
									<th>Quantity</th>
									<th class="text-center"></th>
								</tr>
							</thead>
							<tbody class="_selectedProducts">
								
							</tbody>
						</table>

					</div>

					<div class="col-6">
						<select class="form-control selectpicker" data-live-search="true" id="_productDropDown" data-size="5">
							
						</select>
					</div>

					<div class="col-6">
						<input type="number" class="form-control" name="" id="_procductQuantityInput" value = 1>
					</div>
				</div>



				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="_closeAddProduct">Close</button>
					<button type="button" class="btn btn-primary _addProducts">Add</button>
				</div>
				</div>
			</div>
			</div>
		<!-- END OF ADD PRODUCTS(S) -->
		



        </div>

		


		

    </div>

	

		<?php
    }


	public function vetpost_send_request()
	{
		die();
		$woocommerce = new Client(
			'https://ndomain.ml/vetpost/',
			'ck_4c4f25c9f968df2732510305541b1d48ead13062',
			'cs_830b4920c6403d5f6e6d70cddb5a01d0e4680268',
			[
				'version' => 'wc/v3',
			]
			);

		
		echo '<script>get_products();</script>';

		
			
		date_default_timezone_set("America/New_York");
		// $single_product = array('ListofAllProducts' => array( 'Products' => $woocommerce->get('products/'.$products_array[array_rand($products_array,1)])));
		// file_put_contents(WP_CONTENT_DIR . '/my-debug.txt', "======================\n".date("h:i:sa"). " - Response: \nProduct id :". $single_product['ListofAllProducts']['Products']->id."\nProduct name: ".$single_product['ListofAllProducts']['Products']->name."\n======================\n",FILE_APPEND);
		$products = array('ListofAllProducts' => array( 'Products' => $woocommerce->get('products')));
		file_put_contents(WP_CONTENT_DIR . '/vetpost-get-all-products-response.json',"".json_encode($products, JSON_PRETTY_PRINT)."");
		$response = new WP_REST_Response($products);
		$response->set_status(200);
		wp_send_json_success( $response );

	}


	// END OF CRON JOB BLOCK


}