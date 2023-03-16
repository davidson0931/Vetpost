<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// require VETPOSTAPI_PLUGIN_DIR . 'vendor/autoload.php';

use Automattic\WooCommerce\Client;

/**
 * Class Vetpost_Api_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		VETPOSTAPI
 * @subpackage	Classes/Vetpost_Api_Run
 * @author		Jon Doe
 * @since		1.0.0
 */
class Vetpost_Api_Run{

	/**
	 * Our Vetpost_Api_Run constructor 
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
	
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts_and_styles' ), 20 );
		add_action( 'wp_ajax_nopriv_my_demo_ajax_call', array( $this, 'my_demo_ajax_call_callback' ), 20 );
		add_action( 'wp_ajax_my_demo_ajax_call', array( $this, 'my_demo_ajax_call_callback' ), 20 );
		add_action( 'admin_menu', array($this, 'add_submenu_to_woocommerce'), 100 );

		
        
        //Custom Metabox
        add_action( 'woocommerce_product_options_general_product_data', array($this,'woocommerce_product_custom_fields' ));
        add_action( 'woocommerce_process_product_meta', array($this,'product_custom_save_fields'), 10, 2);

        add_filter( 'woocommerce_rest_prepare_shop_order_object', array($this,'wc_rest_prepare_order_custom_field'), 10, 3 );


	}


    public function woocommerce_product_custom_fields () {
        global $woocommerce, $post;
        echo '<div class=" product_custom_field ">';

        // echo get_post_meta(get_the_ID(), 'branch_HAMI', true);


        // Branch HAMI
        woocommerce_wp_text_input(
            array(
                'id' => 'branch_HAMI',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'branch_HAMI', true ),
                'label' => __('Branch HAMI', 'woocommerce'),
            ),
        );
        // Branch PNTH
        woocommerce_wp_text_input(
            array(
                'id' => 'branch_PNTH',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'branch_PNTH', true ),
                'label' => __('Branch PNTH', 'woocommerce'),
            ),
        );
        // Branch CHCH
        woocommerce_wp_text_input(
            array(
                'id' => 'branch_CHCH',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'branch_CHCH', true ),
                'label' => __('Branch CHCH', 'woocommerce'),
            ),
        );

    // Product Code
       /*  woocommerce_wp_text_input(
            array(
                'id' => 'product_Code',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_Code', true ),
                'label' => __('Code', 'woocommerce'),
            )
        ); */
        /* // Product Description
        woocommerce_wp_textarea_input(
            array(
                'id' => 'product_Description',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_Description', true ),
                'label' => __('Description', 'woocommerce'),
            )
        );
        // Product UnitsPerOuter
        woocommerce_wp_text_input(
            array(
                'id' => 'product_UnitsPerOuter',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_UnitsPerOuter', true ),
                'label' => __('Units Per Outer', 'woocommerce'),
            )
        );
        // Product UnitOfMeasure
        woocommerce_wp_text_input(
            array(
                'id' => 'product_UnitOfMeasure',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_UnitOfMeasure', true ),
                'label' => __('Unit of Measure', 'woocommerce'),
            )
        );
        // Product List Price
        woocommerce_wp_text_input(
            array(
                'id' => 'product_ListPrice',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_ListPrice', true ),
                'label' => __('List Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
            )
        );
        // Product Contact Price
        woocommerce_wp_text_input(
            array(
                'id' => 'product_ContractPrice',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_ContractPrice', true ),
                'label' => __('Contact Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')',
            )
        );
        // Product On Hand
        woocommerce_wp_text_input(
            array(
                'id' => 'product_OnHand',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_OnHand', true ),
                'label' => __('On Hand', 'woocommerce'),
            )
        );
        // Product Barcode
        woocommerce_wp_text_input(
            array(
                'id' => 'product_Barcode',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_Barcode', true ),
                'label' => __('Barcode', 'woocommerce'),
            )
        );
        // Product Last Updated
        woocommerce_wp_text_input(
            array(
                'id' => 'product_LastUpdated',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_LastUpdated', true ),
                'label' => __('Last Updated', 'woocommerce'),
                'type' => 'date',
            )
        );
        // Product SVS_EndOfLife
        woocommerce_wp_text_input(
            array(
                'id' => 'product_SVS_EndOfLife',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_SVS_EndOfLife', true ),
                'label' => __('SVS End of Life', 'woocommerce') ,
                
            )
        );
        // Product SVS_TherapeuticClass
        woocommerce_wp_text_input(
            array(
                'id' => 'product_SVS_TherapeuticClass',
                'placeholder' => '',
                'value'  => get_post_meta( get_the_ID(), 'product_SVS_TherapeuticClass', true ),
                'label' => __('SVS Therapetic Class', 'woocommerce'),
            )
        ); */
        echo '</div>';
        }

        public function product_custom_save_fields( $id, $post ){



 
            // if( !empty( $_POST['branch_HAMI'] || $_POST['branch_PNTH'] || $_POST['branch_CHCH']) )
            
            update_post_meta( $id, 'branch_HAMI', $_POST['branch_HAMI']);
            update_post_meta( $id, 'branch_PNTH', $_POST['branch_PNTH']);
            update_post_meta( $id, 'branch_CHCH', $_POST['branch_CHCH']);

            //if( !empty( $_POST['super_product'] ) ) {
                // update_post_meta( $id, 'branch_HAMI', $attributes);
               /*  update_post_meta( $id, 'product_SupplierCode', $_POST['product_SupplierCode'] );
                update_post_meta( $id, 'product_Code', $_POST['product_Code'] );
                update_post_meta( $id, 'product_Description', $_POST['product_Description'] );
                update_post_meta( $id, 'product_UnitsPerOuter', $_POST['product_UnitsPerOuter'] );
                update_post_meta( $id, 'product_UnitOfMeasure', $_POST['product_UnitOfMeasure'] );
                update_post_meta( $id, 'product_ListPrice', $_POST['product_ListPrice'] );
                update_post_meta( $id, 'product_ContractPrice', $_POST['product_ContractPrice'] );
                update_post_meta( $id, 'product_OnHand', $_POST['product_OnHand'] );
                update_post_meta( $id, 'product_Barcode', $_POST['product_Barcode'] );
                update_post_meta( $id, 'product_LastUpdated', $_POST['product_LastUpdated'] );
                update_post_meta( $id, 'product_SVS_EndOfLife', $_POST['product_SVS_EndOfLife'] );
                update_post_meta( $id, 'product_SVS_TherapeuticClass', $_POST['product_SVS_TherapeuticClass'] );
                            */
            //} else {
            //	delete_post_meta( $id, 'super_product' );
            //}
         
        }
    
        function wc_rest_prepare_order_custom_field( $response, $order, $request ) {

            if( empty( $response->data ) )
              return $response;
          
              $order_data = $response->get_data();
              
              foreach ( $order_data['line_items'] as $key => $item ) {
                  $_product_id = $item['product_id'];
                  $_weight = get_post_meta( $_product_id, '_weight', true );
                  $_branch_hami = get_post_meta( $_product_id, 'branch_HAMI', true );
                  $_branch_pnth = get_post_meta( $_product_id, 'branch_PNTH', true );
                  $_branch_chch = get_post_meta( $_product_id, 'branch_CHCH', true );
                  // $order_data['line_items'][ $key ]['_weight'] = $_stock_status;
				  $_terms = get_the_term_list( $_product_id, 'product_cat' );
				  
				  if(strlen( $_terms) >3 ){
					$_terms = preg_replace('/<a.*?>/i', '', $_terms);
					$_terms = preg_replace('/<\/a>/i', ' ', $_terms);
					$response->data["line_items"][$key]["categories"] =  explode(" " , $_terms);
				  }
                   $response->data["line_items"][$key]["weight"] =  $_weight;
				   
                   $response->data["line_items"][$key]["branch_HAMI"] =  json_decode($_branch_hami);
                   $response->data["line_items"][$key]["branch_PNTH"] =  json_decode($_branch_pnth);
                   $response->data["line_items"][$key]["branch_CHCH"] =  json_decode($_branch_chch);

              }
              
              
          
            return $response;
          }





	// ADDING SUBMENU TO WOOCOMMERCE
	public function add_submenu_to_woocommerce()
	{

		add_submenu_page(
			'woocommerce',' Order List', 'Vetpost Orders', // Menu Title
			'manage_woocommerce',
			'orders-page-sub-menu',
			array($this, 'vetpost_orders_callback'),
			1
	
		);

	}
	//  END OF ADDING SUBMENU TO WOOCOMMERCE


	// DISPLAYING OF HTML CONTENT
	function vetpost_orders_callback() 
	{
		
		// wp_enqueue_style( 'vetpostapi-select2-css', "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", array(), VETPOSTAPI_VERSION);
		// wp_enqueue_script( 'vetpostapi-select2-js', "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", VETPOSTAPI_VERSION);
		wp_enqueue_style( 'vetpostapi-bscdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css', array(), VETPOSTAPI_VERSION);
		wp_enqueue_script( 'vetpostapi-bsscriptcdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', VETPOSTAPI_VERSION, true );
		wp_enqueue_style( 'vetpostapi-bsSelect-css', "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css", array(), VETPOSTAPI_VERSION);
		wp_enqueue_script( 'vetpostapi-bsSelect-js', "https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js", VETPOSTAPI_VERSION);
		wp_enqueue_style( 'vetpostapi-jqueryDataTable-css', "https://cdn.datatables.net/v/bs5/dt-1.12.1/r-2.3.0/datatables.min.css", array(), VETPOSTAPI_VERSION);
		// wp_enqueue_script( 'vetpostapi-jqueryDataTable-js', "https://cdn.datatables.net/v/bs5/dt-1.12.1/r-2.3.0/datatables.min.js", VETPOSTAPI_VERSION, true );
		wp_enqueue_script( 'vetpostapi-feathericons', 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js', VETPOSTAPI_VERSION, true );
		wp_enqueue_script( 'vetpostapi-sweetalert', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js', VETPOSTAPI_VERSION, true );

		wp_enqueue_style( 'vetpostapi-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css', array(), VETPOSTAPI_VERSION);
	
		
		?>

<div class="_container" ng-app="FormApp" ng-controller="vetpostMainCont">

    <div class="_side-nav">

        <!--<div class="_sideNavContent">
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

        </div>-->

        <svs-products-order-modal>
        </svs-products-order-modal>

        <svs-send-product-modal>
        </svs-send-product-modal>

        <svs-cancel-product-modal>
        </svs-cancel-product-modal>



        <div class="_main-content" ng-controller="ProductList">
            <div class="_top-nav"></div>

            <div class="_table-contents container">
                <div class="test-container">
                    <div>
                        <img src="" alt="" srcset="">
                        <img src="" alt="" srcset="">
                        <img src="" alt="" srcset="">
                    </div>
                    <nav class="_nav-contents">
                        <svs-status-list-menu>
                        </svs-status-list-menu>
                    </nav>
                    <span style="font-size: 12px; opacity: 80%;"> This status includes all orders that include only SVS
                        products and which are in a Processing state in the Vet Post Website</span>
                </div>


                <!-- ORDER PAGES -->
                <section class="pages _tableContainer" id="all_orders">
                    <div class="">
                        <!-- 	<table id="_mainTable" class="table table-responsive" style="width: 100% !important;">
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
					</table> -->


                        <!--<button type="button" class="btn btn-primary" ng-click="refresh()">load</button>{{orders[0]}} list="menus"-->


                        <div class="container pagination-container">
                            <div class="search-container" style="text-align: end; padding: 2px;">
                                <!-- <input type="search" ng-model="searchF" name="" value=""
                                    style="width: 150px; font-size: 12px; border-radius: 5px;">
                                <button class="custom-pagination-btn" ng-click="searchTrigger()">
                                    Search orders
                                </button> -->
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-6" style="padding-left: 0;">
                                    <input type="search" ng-model="searchF" name="" value=""
                                        style="width: 150px; font-size: 12px; border-radius: 5px;">
                                    <button class="custom-pagination-btn" ng-click="searchTrigger()">
                                        Search orders
                                    </button>
                                    <!-- <select style="width: 150px; font-size: 12px; border-radius: 5px;">
                                        <option selected="">Bulk Actions</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    <button class="custom-pagination-btn">
                                        Apply
                                    </button> -->
                                    <span ng-click="refreshData()" style="
												padding: 5px 10px;
												border-radius: 5px;
												background: #5b99ea;
												color: white;
												font-size: 15px;
												display: inline;
                                                vertical-align: middle;
												width: 32px;
                                                cursor: pointer;
											"><i class="bi bi-arrow-repeat" style="margin-right: 0px;"></i></span>
                                    <svs-check-product>
                                    </svs-check-product>
                                </div>
                                <div class="col-6">
                                    <!-- HEADER PAGINATION -->
                                    <!-- angular-view/ProductListPagination.html -->
                                    <product-list-pagination>
                                    </product-list-pagination>
                                </div>
                            </div>

                        </div>

                        <!-- angular-view/ProductList.html -->
                        <product-list></product-list>


                        <!-- LOADER -->
                        <!-- <div class="loader-container" style="padding: 50px 0px;">
                            <div class="loader" id="loader" ng-show="loader">
                            </div>
                        </div> -->

                        <!-- FOOTER CONTAINER PAGINATION -->
                        <div class="container pagination-container">
                            <div class="row">
                                <div class="col-6">
                                    <show-orders-footer>
                                    </show-orders-footer>
                                </div>
                                <div class="col-6">
                                    <!-- FOOTER PAGINATION -->
                                    <!-- angular-view/ProductListPagination.html -->
                                    <product-list-pagination>
                                    </product-list-pagination>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
                <!-- END OF ORDER PAGES  -->



            </div>


            <div ng-controller="EditProductForm">
                <div class="_editForm m-3 p-5" hidden>


                    <div class="_formHeader ">
                        <h3>Order #<span class="_orderNumberDetails"></span> Details</h3>
                        <button type="button" class="btn btn-primary _btnBack" ng-click="ClearForm()"><i
                                class="bi bi-arrow-return-left"></i></button>
                    </div>

                    <div class="_editFormContent ">

                        <form id="updateForm">
                            <!-- GENERAL BILLING SHIPPING -->
                            <input type="text" class="form-control" style="display:none" id="productID"
                                ng-model="productid" ng-change="updatePopulation()">

                            <div class="row">

                                <div class="col-4">

                                    <div class="row p-1">

                                        <h5>General</h5>

                                        <div class="col-12 mb-2">
                                            <label for="date_created">Date Created</label>
                                            <input type="datetime-local" class="form-control" readonly>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <div>
                                                <label for="status">Status</label>
                                                <input type="text" class="form-control" id="status" readonly
                                                    ng-model="info.data.status">
                                                <!-- <select readonly class="form-control" id="status"  ng-model="info.data.status">
											<option value="processing">Processing</option>
											<option value="pending">Pending</option>
											<option value="on-hold">OnHold</option>
											<option value="completed">Completed</option>
											<option value="refunded">Refunded</option>
											<option value="failed">Failed</option>
											<option value="checkout-draft">Draft</option>
                                        </select> -->
                                            </div>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <div>
                                                <label for="customer">Customer</label>
                                                <input readonly type="text" class="form-control" id="customer">
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
                                                    <label for="first_name">First Name</label>

                                                    <input readonly type="text" ng-model="info.data.billing.first_name"
                                                        class="form-control" id="first_name">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="last_name">Last Name</label>
                                                    <input readonly type="text" class="form-control" id="last_name"
                                                        ng-model="info.data.billing.last_name">
                                                </div>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <div>
                                                    <label for="company">Company</label>
                                                    <input readonly type="text" class="form-control" id="company"
                                                        ng-model="info.data.billing.company">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="address_1">Address 1</label>
                                                    <input readonly type="text" class="form-control" id="address_1"
                                                        ng-model="info.data.billing.address_1">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="address_2">Address 2</label>
                                                    <input readonly type="text" class="form-control" id="address_2"
                                                        ng-model="info.data.billing.address_2">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="city">City</label>
                                                    <input readonly type="text" class="form-control" id="city"
                                                        ng-model="info.data.billing.city">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="postcode">Postcode</label>
                                                    <input readonly type="text" class="form-control" id="postcode"
                                                        ng-model="info.data.billing.postcode">
                                                </div>
                                            </div>



                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="payment_method">Country/Region</label>

                                                    <input readonly type="text" class="form-control" id="country"
                                                        ng-model="info.data.billing.country">

                                                    <!-- <select readonly class="form-control" id="country" ng-model="info.data.billing.country">
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select> -->
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="payment_method">State/County</label>
                                                    <input readonly type="text" class="form-control" id="state"
                                                        ng-model="info.data.billing.state">

                                                    <!-- <select readonly class="form-control" id="country" ng-model="info.data.billing.state">
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select> -->
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="email">Email Address</label>
                                                    <input readonly type="text" class="form-control" id="email"
                                                        ng-model="info.data.billing.email">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="phone">Phone</label>
                                                    <input readonly type="text" class="form-control" id="phone"
                                                        ng-model="info.data.billing.phone">
                                                </div>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <div>
                                                    <label for="payment_method">Payment Method</label>
                                                    <input readonly type="text" class="form-control" id="payment_method"
                                                        ng-model="info.data.payment_method">

                                                    <!--   <select readonly class="form-control" id="payment_method" ng-model="info.data.payment_method" >
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select> -->
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div>
                                                    <label for="transaction_id">Transaction ID</label>
                                                    <input readonly type="text" class="form-control" id="transaction_id"
                                                        ng-model="info.data.payment_method">

                                                    <!--  <select readonly class="form-control" id="transaction_id" >
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                            <option value="">1</option>
                                        </select> -->
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
                                                    <label for="first_name">First Name</label>
                                                    <input readonly type="text" class="form-control" id="first_name"
                                                        ng-model="info.data.shipping.first_name">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="last_name">Last Name</label>
                                                    <input readonly type="text" class="form-control" id="last_name"
                                                        ng-model="info.data.shipping.last_name">
                                                </div>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <div>
                                                    <label for="company">Company</label>
                                                    <input readonly type="text" class="form-control" id="company"
                                                        ng-model="info.data.shipping.company">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="address_1">Address 1</label>
                                                    <input readonly type="text" class="form-control" id="address_1"
                                                        ng-model="info.data.shipping.address_1">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="address_2">Address 2</label>
                                                    <input readonly type="text" class="form-control" id="address_2"
                                                        ng-model="info.data.shipping.address_2">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="city">City</label>
                                                    <input readonly type="text" class="form-control" id="city"
                                                        ng-model="info.data.shipping.city">
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="postcode">Postcode</label>
                                                    <input readonly type="text" class="form-control" id="postcode"
                                                        ng-model="info.data.shipping.postcode">
                                                </div>
                                            </div>



                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="payment_method">Country/Region</label>
                                                    <input readonly type="text" class="form-control" id="country"
                                                        ng-model="info.data.shipping.country">

                                                    <!-- <select readonly class="form-control" id="country" ng-model="info.data.shipping.country">
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
									</select> -->
                                                </div>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="payment_method">State/County</label>
                                                    <input readonly type="text" class="form-control" id="state"
                                                        ng-model="info.data.shipping.state">

                                                    <!-- <select readonly class="form-control" id="state" ng-model="info.data.shipping.state">
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
									</select> -->
                                                </div>
                                            </div>


                                            <div class="col-6 mb-2">
                                                <div>
                                                    <label for="phone">Phone</label>
                                                    <input readonly type="text" class="form-control" id="phone"
                                                        ng-model="info.data.shipping.phone">
                                                </div>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <div>
                                                    <label for="payment_method">Payment Method</label>
                                                    <input readonly type="text" class="form-control" id="payment_method"
                                                        ng-model="info.data.payment_method">

                                                    <!-- <select readonly class="form-control" id="payment_method" ng-model="info.data.payment_method" >
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
										<option value="">1</option>
									</select> -->
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
                                            <tr class="_addedTaxesHeader">
                                                <th scope="col" hidden col>ID</th>
                                                <th scope="col" class="w-75" col>Product</th>
                                                <th scope="col">Cost</th>
                                                <th scope="col" class="text-center">Qty</th>
                                                <th scope="col" class="text-center">Total</th>
                                                <!-- <th id="_actionButtonHeader"> </th> -->
                                            </tr>
                                        </thead>

                                        <tr>
                                            <tbody id="_addedProductsDataNg">






                                                <tr ng-repeat="line_item in products " class="m-2"
                                                    id="tr-{{line_item.id}}" data-rowid={{line_item.id}}>
                                                    <th scope="row" hidden="">{{line_item.id}}</th>
                                                    <th scope="row" class="pt-4 pb-4">
                                                        <div class="_productColumn">
                                                            <div class="_productColumnImg">
                                                                <img src={{line_item.image.src}} class="img-fluid"
                                                                    alt="">
                                                            </div>

                                                            <div class="_productColumnText">
                                                                <p>{{line_item.name}}</p>
                                                                <p>SKU:{{line_item.sku}} <span></span></p>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>{{info.data.currency_symbol}}<span
                                                            class="_productPrice-{{line_item.id}}">{{line_item.price}}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="_cellContainer">
                                                            <div class="_productTableQuantity">
                                                                <span>x</span>
                                                                <span
                                                                    id="_editProductQuantity-{{line_item.id}}">{{line_item.quantity}}</span>
                                                                <br>
                                                            </div>
                                                            <small hidden=""
                                                                class="_defaultValues _defaultQuantity-{{line_item.id}} _defaultValue-{{line_item.id}}">Old
                                                                Quantity</small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="_cellContainer ">
                                                            <div
                                                                class="_productTableTotal _cellContainer-{{line_item.id}}">
                                                                <span>{{info.data.currency_symbol}}</span>
                                                                <span
                                                                    id="_editProductTotal-{{line_item.id}}">{{line_item.subtotal}}</span>
                                                            </div>
                                                            <small hidden=""
                                                                class="_defaultValues _defaultTotal-{{line_item.id}} _defaultValue-{{line_item.id}}"></small>
                                                            <small hidden=""
                                                                class="_discountTotal _discountTotal-{{line_item.id}}">
                                                                <span>{{info.data.currency_symbol}}<span
                                                                        class="_discountValue-{{line_item.id}}"></span>
                                                                    Discount</span>
                                                            </small>
                                                        </div>
                                                    </td>


                                                    <!--  <td id="_productAction-{{line_item.id}}">
                                <div class="_productActionButtons">
                                    <span class="_editProductRow _editProductRow-{{line_item.id}}"><i class="bi bi-pencil"></i></span>
                                    <span hidden="" class="_saveProductRow _saveProductRow-{{line_item.id}}"><i class="bi bi-check"></i></span>
                                    <span class="_deleteProductRow _deleteProductRow-{{line_item.id}}"><i class="bi bi-cart-dash"></i></span>
                                    <span hidden="" class="_cancelEditProductRow _cancelEditProductRow-{{line_item.id}}"> <i class="bi bi-x"></i> </span>
                                </div>
                            </td> -->
                                                </tr>


















                                            </tbody>
                                        </tr>
                                        <tr class="_addFeeRow" hidden>
                                            <tbody id="_addedFeeData">

                                            </tbody>

                                        </tr>
                                        <tr class="_shippingRow" hidden>
                                            <tbody id="_addedShippingData">

                                            </tbody>

                                        </tr>

                                    </table>



                                </div>
                                <div class="card-body _productTableList">

                                    <table class="table _tableList">
                                        <tbody id="_addedItems">




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
                                                        <th><span
                                                                id="_itemSubtotal">{{info.data.currency_symbol}}{{info.data.total}}</span>
                                                        </th>
                                                        <td> </td>
                                                    </tr>
                                                    <tr id="_voucherRow" hidden>
                                                        <td>Voucher(s):</td>
                                                        <th id="_vouchers">- $<span id="_voucherValue">00.00</span></th>
                                                        <td> </td>
                                                    </tr>
                                                    <tr>
                                                <tbody class="_addedFee" hidden>
                                                    <td>Fees:</td>
                                                    <th>- $<span id="_itemFee">0.00</span></th>
                                                    <td> </td>
                                                </tbody>

                                                </tr>
                                                <tr>
                                                    <tbody class="_addedShippingTotal">

                                                    </tbody>
                                                </tr>
                                                <tr>
                                                    <tbody class="_addedTaxesTotal">

                                                    </tbody>
                                                </tr>

                                                <tr>
                                                    <td>Order Total:</td>
                                                    <th><span
                                                            id="_itemOrderTotal">{{info.data.currency_symbol}}{{info.data.total}}</span>
                                                    </th>
                                                </tr>


                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>



                                <!-- <div class="_footerContainer">
								<div class="card-footer _productFooterStyle">
									<div class="_productCardFooter">
										<button type="button" class="btn btn-sm btn-outline-primary _showAddProductOption">Add Item(s)</button>
									</div>

									<div class="_productCardOption" hidden>
										<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#_addProductModal">Add product(s)</button>
										
										<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#_addFeeModal" >Add fee</button>

										<button type="button" class="btn btn-sm btn-outline-primary _addShippingTrigger">Add shipping</button>

										<button type="button" class="btn btn-sm btn-outline-primary"  data-bs-toggle="modal" data-bs-target="#_addTaxModal">Add tax</button>
										
										<button type="button" class="btn btn-sm btn-outline-primary _hideAddProductOption">Cancel</button>

										<button type="button" class="btn btn-sm btn-primary">Save</button>

										
									</div>
									
								</div>
								
							</div> -->

                            </div>


                            <!-- END OF PRODUCTS -->

                        </form>

                    </div>


                </div>


                <!-- SPINNER 
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

        </div>-->
                <!-- END OF SPINNER -->


                <!-- ADD FEE(S) -->
                <!-- <div class="modal fade" id="_addFeeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog  modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="staticBackdropLabel">Add Fee(s)</h1>
					
					<button type="button" id="_closeAddFee" class="btn-close" aria-label="Close"></button>
				</div>

				<div class="modal-body">
				<h2 class="fs-6">Enter a fixed amount or percentage to apply as a fee.</h2>
				<div class="row">
					<div class="col-12">
						<input type="text" class="form-control" min="0" name="" id="_addFeeAmountInput">
					</div>
				</div>



				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="_closeAddFee">Cancel</button>
					<button type="button" class="btn btn-primary _addFeeTrigger">Add</button>
				</div>
				</div>
			</div>
			</div> -->

                <!-- END OF FEE(S) -->

                <!-- ADD PROUCT(S) -->

                <!-- 	<div class="modal fade" id="_addProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" ng-controller="AddProductOrder">
			<div class="modal-dialog  modal-dialog-centered modal-xl">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="staticBackdropLabel">Add Product(s)</h1>
					<button type="button" id="_closeAddProduct" class="btn-close" aria-label="Close"></button>
				</div>
				<div class="modal-body">

				<div class="row">
					<div class="_productTable p-2">

						<table class="table table-responsive table-borderless" style="100%">
							<thead>
								<tr>
									<th colspan="2">Product</th>
									<th>Quantity </th>
									<th class="text-center"></th>
								</tr>
							</thead>
							<tbody class="_selectedProducts">
								
								
								
								
								
								
								<tr ng-repeat="newItem in newItemList track by $index">
                                            <td>
                                                <div class="_productColumnImg">
                                                <img src={{newItem.image.src}} class="img-fluid _product_image " id="_image{{newItem.product_id}}" alt="">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown bootstrap-select form-control _productUpdateImage _readOnlyInput name-{{newItem.product_id}} dropup disabled" disabled="disabled">{{newItem.name}}</div>
                                            </td>
                                            <td>
											{{newItem.quantity}}
                                                <input class="_readOnlyInput quant-{{newItem.product_id}}" type="number" id="_procductQuantity" ng-model="newItem.quantity" > 
                                            </td>
                                            <td class="text-center">
                                               
                                               
                                                <button type="button" class="btn btn-small _removeButton" id="_removeProduct" data-productid="{{newItem.product_id}}" ng-click="removeProductList($event)">
                                                        <i class="bi bi-cart-dash"></i>
                                                </button>
                                            </td></tr>
								
								
								
								
								
								
							</tbody>
						</table>

					</div>

					<div class="col-6">
						<select class="form-control selectpicker" data-live-search="true" id="_productDropDown" data-size="5" ng-model="newItemListID"  ng-change="addProductInList()">
							
						</select>
					</div>

					<div class="col-6">
						<input type="number" class="form-control _numberInput" min="0" name="" id="_procductQuantityInput" value = 1>
					</div>
				</div>



				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="_closeAddProduct">Cancel</button>
					<button type="button" class="btn btn-primary _addProducts" ng-click="addProductEvent()">Add</button>
				</div>
				</div>
			</div>
			</div> -->
                <!-- END OF ADD PRODUCTS(S) -->

                <!-- ADD TAX-->
                <!-- <div class="modal fade" id="_addTaxModal">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">

	
			<div class="modal-header">
				<h4 class="modal-title">Add Tax</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

	
			<div class="modal-body">
				
			<table class="table table-responsive _taxTable">
				<thead>
				<tr>
					<th></th>
					<th>Rate Name</th>
					<th class="text-start">Tax class</th>
					<th>Rate code</th>
					<th class="text-end">Rate %</th>
				</tr>
				</thead>
				<tbody class="_taxRatesRows p-2">
				</tbody>
			</table>

			</div>


			<div class="modal-footer">
			<button type="button" class="btn btn-danger btn-sm _removeTaxes">Remove Taxes</button>
				<button type="button" class="btn btn-secondary btn-sm _applyTaxRate" data-bs-dismiss="modal">Close</button>
			</div>

			</div>
		</div>
		</div> -->
                <!-- END OF ADD TAX -->


            </div>

        </div>


    </div>



    <?php
	}
	// END OF DISPLAYING OF HTML CONTENT


	public function enqueue_backend_scripts_and_styles() 
	{
		
		
		
		wp_enqueue_style( 'vetpostapi-backend-styles', VETPOSTAPI_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), VETPOSTAPI_VERSION, 'all' );
		wp_enqueue_script( 'vetpostapi-backend-scripts', VETPOSTAPI_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array( 'jquery' ), VETPOSTAPI_VERSION, true );
		wp_localize_script( 'vetpostapi-backend-scripts', 'vetpostapi', array(
			'plugin_name'   	=> __( VETPOSTAPI_NAME, 'vetpost-api' ),
			'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
			'security_nonce'	=> wp_create_nonce( "your-nonce-name" ),
			'vetpostApiUrl'		=> VETPOSTAPI_PLUGIN_URL.'api/?request='
		));
		
		wp_enqueue_style( 'vetpostapi-frontend-styles', VETPOSTAPI_PLUGIN_URL . 'core/includes/assets/css/frontend-styles.css', array(), VETPOSTAPI_VERSION, 'all' );
		wp_enqueue_script( 'vetpostapi-frontend-scripts', VETPOSTAPI_PLUGIN_URL . 'core/includes/assets/js/frontend-scripts.js', array( 'jquery' ), VETPOSTAPI_VERSION, true );
		wp_localize_script( 'vetpostapi-frontend-scripts', 'vetpostapi', array(
			'plugin_name'   	=> __( VETPOSTAPI_NAME, 'vetpost-api' ),
			'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
			'security_nonce'	=> wp_create_nonce( "your-nonce-name" ),
			'vetpostApiUrl'		=> VETPOSTAPI_PLUGIN_URL.'api/?request='
		));
		
		

		
		wp_enqueue_script( 'angular-min-js',  'https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js', array(), VETPOSTAPI_VERSION, true  );
		wp_enqueue_script( 'angular-resource-min-js',  'https://cdnjs.cloudflare.com/ajax/libs/angular-resource/1.8.3/angular-resource.min.js', array(), VETPOSTAPI_VERSION, true  );
		
		wp_enqueue_script( 'angular-ui-bootstrap-tpls',  'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.0.0/ui-bootstrap-tpls.min.js', array(), VETPOSTAPI_VERSION, true  );
		
		wp_enqueue_script( 'angular-ui-utils-min-js',  'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-utils/0.1.1/angular-ui-utils.min.js', array(), VETPOSTAPI_VERSION, true  );
		
		wp_enqueue_script( 'angular-datatable-min-js',  'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js', array(), VETPOSTAPI_VERSION, true  );
		
		wp_enqueue_script( 'angular-boots-datatable-min-js',  'https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js', array(), VETPOSTAPI_VERSION, true  );
		 
		
		wp_register_script( 'vetpostapi-backend-scripts-angular', VETPOSTAPI_PLUGIN_URL . 'core/includes/assets/js/backend-scripts-angular.js', array(), VETPOSTAPI_VERSION, true );
		 wp_enqueue_script('vetpostapi-backend-scripts-angular');
		
		$path_plugin_vetpost_custom = array( 'template_url' => VETPOSTAPI_PLUGIN_URL );
		
    wp_localize_script( 'vetpostapi-backend-scripts-angular', 'path_plugin_vetpost_custom', $path_plugin_vetpost_custom );
		
		wp_enqueue_script( 'vetpostapi-backend-scripts-angular-ProductList', VETPOSTAPI_PLUGIN_URL . 'core/includes/assets/js/backend-script-angular-ProductList.js', array(), VETPOSTAPI_VERSION, true );
		
		
		
		
	}



	
	public function enqueue_frontend_scripts_and_styles() 
	{
		
	
	}



	

}