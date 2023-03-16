
 app.controller("ProductList" , function($scope, $http , GetAllOrder , $timeout , $q ,  $rootScope , svsProductsOrderData , svsOrdersItemsCount , svsPrepareSendOrder, svsCancelOrder ){
	
	
	$scope.request = {request: "all_orders" , page :  1 , per_page : 20 , s : "" };
	$scope.orders   = [{id: 0}];
	// $scope.tableDisplay   = false;
	$scope.requestPromise = [];


 
	$scope.loader = true;

	$scope.noData = false;

	// START PAGINATION FUNCTION
	$scope.leftPageHide = function(){
		if($scope.request.page <= 1){
			return true;
		}else{
			return false;
		}
	}

	$scope.rightPageHide = function(){
		if($scope.request.page == $scope.totalPage || $scope.totalPage == 0){
			return true;
		}else{
			return false;
		}
	}

	$scope.firstPage = function(){
			$scope.loader = false;
			$scope.tbody = false;
			$scope.request.page = 1;
			$scope.getapiNoTimeOut();
	}

	$scope.previousPage = function(){
		$scope.tbody = false;
		$scope.request.page--;
		$scope.getapiNoTimeOut();
	}

	$scope.nextPage = function(){
			$scope.loader = false;
			$scope.tbody = false;
			$scope.request.page++;
			$scope.getapiNoTimeOut();
	}
	
	$scope.lastPage = function(){
		$scope.tbody = false;
		$scope.request.page  = $scope.totalPage;
		$scope.getapiNoTimeOut();
	}
	
	// $scope.changePage = function(){
	// 	$scope.tbody = false;
	// 	if($scope.request.page > $scope.totalPage){
	// 		$scope.tbody = false;
	// 		$scope.request.page  = $scope.totalPage;
	// 		$scope.getapiNoTimeOut();
	// 	}
	// 	else if($scope.request.page <= 0){
	// 		$scope.tbody = false;
	// 		$scope.request.page = 1;
	// 		$scope.getapiNoTimeOut();
	// 	}else{
	// 		$scope.tbody = false;
	// 		$scope.getapiNoTimeOut();
	// 	}
	// }
	// END PAGINATION FUNCTION

	// TEST FUNCTION FOR DISPLAY NO DATA TABLE

 

	// DEFAULT GET API
	$scope.getapi = function(){
	 
		// $scope.loader = true;
		$scope.request.ts = Date.now();
		GetAllOrder.get($scope.request, function(obj ) {
			// $scope.names = obj.data;
			// console.log(obj);
		
			$scope.tbody = true;
			$scope.loader = false;

		}).$promise.then(function successCallback(data) {

			$scope.today = new Date();
			$scope.totalOrders = parseInt(data.total);
			$scope.totalPage = parseInt(data.total_page);
			$scope.allTotal = parseInt(data.all_total);
			$scope.orders = data.data.map((item , index) => {
				// console.log(item);
				
				var findSameObject = $scope.orders.find(x => x.id === item.id);
				
				// console.log(findSameObject);
					if(findSameObject !== undefined &&  findSameObject.is_Selected == true ){
					return {...item , is_Selected : true};
				}
				return {...item , is_Selected : false}; 
			});

			if($scope.request.page === 1){
				$scope.countOne = 1;
				$scope.countTotal = parseInt(data.all_total);
			}else if($scope.request.page === $scope.totalPage){
				$scope.countOne = parseInt(data.total) - parseInt(data.all_total);
				$scope.countTotal = parseInt(data.total) + 1;
			}else if($scope.request.page > 1){
				$scope.countTotal = parseInt(data.all_total) * $scope.request.page;
				$scope.countOne =  $scope.countTotal - parseInt(data.all_total) + 1;
			}

			svsOrdersItemsCount.refreshData({request: "all_orders"});
			$timeout($scope.getapi, 5000);
				// $scope.tableDisplay   = true;
		}, function errorCallback(error) {
			//you can add anything else you want inside this function
			// promise.resolve(dataFactory.daysOfWeek);
			
		});
		

	};

	// GET API NO TIME OUT
	$scope.getapiNoTimeOut =  function(promise){
		$scope.loader = true;
		// var date = Date.now();
		$scope.request.ts = Date.now();
		GetAllOrder.get($scope.request, function(obj) {
			$scope.tbody = true;
			$scope.loader = false;
			
		}).$promise.then( function successCallback(data) {
			$scope.today = new Date();
			$scope.totalOrders = Number(data.total);
			$scope.totalPage = Number(data.total_page);
			$scope.allTotal = Number(data.all_total);
			$scope.orders = data.data.map((item , index) => {

				var findSameObject = $scope.orders.find(x => x.id === item.id);
				
				 if(findSameObject !== undefined &&  findSameObject.is_Selected == true ){
					return {...item , is_Selected : true};
				}
				return {...item , is_Selected : false}; 
			});

			if($scope.request.page === 1){
				$scope.countOne = 1;
				$scope.countTotal = parseInt(data.all_total);
			}else if($scope.request.page === $scope.totalPage){
				$scope.countOne = (parseInt(data.total) - parseInt(data.all_total)) + 1;
				$scope.countTotal = parseInt(data.total);
			}else if($scope.request.page > 1){
				$scope.countTotal = parseInt(data.all_total) * $scope.request.page;
				$scope.countOne =  $scope.countTotal - parseInt(data.all_total) + 1;
			}
			
				for(var i = 0; i < $scope.orders.length; i++){
				$scope.orderId = $scope.orders[i].id
				// console.log($scope.orders[i])

				var getorder = $scope.orders.filter((item) => {
					return parseInt($scope.orderId) === item.id;
				 });

				 for(var j = 0; j < getorder[0].line_items.length; j++){
					// console.log(getorder[0].line_items[j].id)
						$scope.productId = getorder[0].line_items[j].id

				 var getitem = (getorder[0].line_items).filter((item ) => {
					return $scope.productId  === item.id;
				 }) 

				 if(getitem[0].branch_HAMI !== null){
					$scope.getHamiSOH = getitem[0].branch_HAMI.SOH;
					// console.log($scope.getHamiSOH);
				 }
				 else{
					// console.log("No data found!");	
				 }

				 if(getitem[0].branch_PNTH !== null){
					$scope.getPnthSOH = getitem[0].branch_PNTH.SOH;
					// console.log($scope.getPnthSOH);
				 }
				 else{
					// console.log("No data found!");	
				 }

				 if(getitem[0].branch_CHCH !== null){
					$scope.getChchSOH = getitem[0].branch_CHCH.SOH;
					// console.log($scope.getChchSOH);
				 }
				 else{
					// console.log("No data found!");	
				 }
				
				//    var getHAMI = ([getitem[0].branch_HAMI]).find((item ) =>{
				// 	if(item !== null || item !== undefined){
				// 		return true;
				// 	}else{
				// 		return false;
				// 	}
				//    });
				
				// console.log(getHAMI);
				// console.log(getHAMI.SOH);
				// var getArrayHami = (getitem[0].branch_HAMI).filter(function(element){
				// 	if(element !== null){
				// 		return "HAMI" === element.SendingDC;
				// 	}else{
				// 		return false;
				// 	}
				// })




				//  console.log(getitem[0].branch_HAMI)
				//  console.log(getitem[0].branch_PNTH)
				//  console.log(getitem[0].branch_chch)
				//  console.log(getitem[0].branch_HAMI)
				//  console.log(getitem[0].id.branch_HAMI.SOH)
				//  console.log(getitem[0].branch_PNTH.SOH)
				//  console.log(getitem[0].branch_CHCH.SOH)

				//  $scope.SOHHami = getitem[0].branch_HAMI.SOH;

				 }
			}

			svsOrdersItemsCount.refreshData({request: "all_orders"});
	   }, function errorCallback(error) {
			//you can add anything else you want inside this function
			// promise.resolve(data);
	   });

	};

	$scope.checkOrderList = function(status, line_items) {
		// line_items[0].sku.length;

		// console.log(line_items[0].sku.match("VP-"));

		// console.log(line_items[0].sku.match("VP") != null && line_items.length == line_items.length);

		if(line_items[0].sku.match("VP") != null && line_items.length == 1 && line_items.length === line_items.length){
			return false;
		}

		if(line_items[0].sku.match("VP") != null && line_items.length == line_items.length){
			return false;
		}

		// console.log(line_items.length)
		// if(line_items[0].sku.match(/^[0-9]+$/) != null && line_items.length == 1){
			
		// }

		if(status == "processing"){
			return true;
		}




	}

	$scope.colorValue = function(){
		$scope.redValue = {
			"background-color" : "#cf6763",
		}

		$scope.yellowValue = {
			"background-color" : "#eebe40",
		}

		$scope.greenValue = {
			"background-color" : "#5bb176",
		}

		$scope.grayValue = {
			"background-color" : "#b3b3b3",
		}

		$scope.blueValue = {
			"background-color" : "#5b99ea",
		}

		$scope.orangeValue = {
			"background-color" : "#ffad33",
		}
	};

	$scope.changeStatusColor = function(status){
	
		$scope.colorValue();

		if(status == "on-hold"){
			return $scope.grayValue;
		}
		if(status == "ready-send"){
			return $scope.orangeValue;
		}
		if(status == "svs-candidates"){
			return $scope.blueValue;
		}
		if(status == "svs-processing"){
			return $scope.blueValue;
		}
		if(status == "refunded"){
			return $scope.grayValue;
		}
		if(status == "pending"){
			return $scope.orangeValue;
		}
		if(status == "processing"){
			return $scope.orangeValue;
		}
		if(status == "partial"){
			return $scope.orangeValue;
		}
		if(status == "cancelled"){
			return $scope.redValue;
		}
		if(status == "failed"){
			return $scope.redValue;
		}
		if(status == "completed"){
			return $scope.greenValue;
		}

	}

	$scope.changeHamiColor = function(hamiColorID){

		$scope.colorValue();

		if(hamiColorID  == null){
			return $scope.grayValue;
		}
		if(hamiColorID  == 0){
			return $scope.redValue;
		}
		if(hamiColorID <= 10){
			return $scope.yellowValue;
		}
		if(hamiColorID >= 11){
			return $scope.greenValue;
		}
	};

	$scope.changePnthColor = function(pnthColorID){

		$scope.colorValue();

		if(pnthColorID  == null){
			return $scope.grayValue;
		}
		if(pnthColorID  == 0){
			return $scope.redValue;
		}
		if(pnthColorID <= 10){
			return $scope.yellowValue;
		}
		if(pnthColorID >= 11){
			return $scope.greenValue;
		}
	};

	$scope.changeChchColor = function(chchColorID){

		$scope.colorValue();

		if(chchColorID  == null){
			return $scope.grayValue;
		}
		if(chchColorID  == 0){
			return $scope.redValue;
		}
		if(chchColorID <= 10){
			return $scope.yellowValue;
		}
		if(chchColorID >= 11){
			return $scope.greenValue;
		}
	};

	// HTTP GET API PROMISE
	$scope.httpApiPromise = function(){

		$scope.loader = true;
		$scope.tbody = false;
		$scope.noData = false;
		$scope.request.page = 1;
		 var httpPromise = GetAllOrder.get($scope.request );
			  httpPromise.$promise.then(function successCallback(data) {

					$scope.totalOrders = parseInt(data.total);
					$scope.totalPage = parseInt(data.total_page);
					$scope.allTotal = parseInt(data.all_total);
					$scope.orders = data.data.map((item , index) => {
						
						var findSameObject = $scope.orders.find(x => x.id === item.id);
						
						 if(findSameObject !== undefined &&  findSameObject.is_Selected == true ){
							return {...item , is_Selected : true};
						}
						return {...item , is_Selected : false}; 
					});
					
					if($scope.request.page === 1){
						$scope.countOne = 1;
						$scope.countTotal = parseInt(data.all_total);
					}else if($scope.request.page === $scope.totalPage){
						$scope.countOne = parseInt(data.total) - parseInt(data.all_total);
						$scope.countTotal = parseInt(data.total) + 1;
					}else if($scope.request.page > 1){
						$scope.countTotal = parseInt(data.all_total) * $scope.request.page;
						$scope.countOne =  $scope.countTotal - parseInt(data.all_total) + 1;
					}

					$scope.requestPromise.splice($scope.requestPromise.indexOf(0), 1);

					if($scope.allTotal == 0)
					{
						
						// console.log("TRUE")
						// console.log($scope.allTotal);
						$scope.loader = false;
						$scope.noData = true;
					}else{
						// console.log("FALSE")
						$scope.noData = false;
					}

				}, function errorCallback(error) {
			   }); 

	}
	
	// STATUS ALL
	$scope.statusAll = () => {
		$scope.searchF = "";
		$scope.request.s = null;
		delete $scope.request.status;
		$scope.noData = false;
		$scope.httpApiPromise();
		// console.log($scope.request.s);

	}
	// STATUS LIST
	$scope.statuslist = (status) => {
		$scope.searchF = "";
		$scope.request.s = null;
		$scope.request.status = status;
		$scope.noData = false;
		$scope.httpApiPromise();	
		// console.log($scope.request.s);
	}
 
	// SHOW MODAL PRODUCT ORDER
	$scope.showModalSVSPRoductOrder = function( OrderID, ProductId){

		$rootScope.showModal = true;
		svsProductsOrderData.setProductID(ProductId);
		// console.log(OrderID);
		// console.log(ProductId);


		console.log($scope.orders.filter((item) => {
			return parseInt(OrderID) === item.id;
		 }));


		var getorder = $scope.orders.filter((item) => {
			return parseInt(OrderID) === item.id;
		 })

		 
		var getitem = (getorder[0].line_items).filter((item ) => {
			return ProductId === item.id;
		 }) 
 
		if(getitem[0].branch_HAMI != null && getitem[0].branch_PNTH != null && getitem[0].branch_CHCH != null)
		{
			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode(getitem[0].branch_HAMI.SupplierCode);
			svsProductsOrderData.setHAMIWarehouse(getitem[0].branch_HAMI.SendingDC);	
			svsProductsOrderData.setHAMIStock(getitem[0].branch_HAMI.SOH);
			
			svsProductsOrderData.setPNTHSupplierCode(getitem[0].branch_PNTH.SupplierCode);
			svsProductsOrderData.setPNTHWarehouse(getitem[0].branch_PNTH.SendingDC);	
			svsProductsOrderData.setPNTHStock(getitem[0].branch_PNTH.SOH);	
	
			svsProductsOrderData.setCHCHSupplierCode(getitem[0].branch_CHCH.SupplierCode);
			svsProductsOrderData.setCHCHWarehouse(getitem[0].branch_CHCH.SendingDC);	
			svsProductsOrderData.setCHCHStock(getitem[0].branch_CHCH.SOH);	
		} 

		else if(getitem[0].branch_HAMI != null && getitem[0].branch_PNTH == null && getitem[0].branch_CHCH == null)
		{
			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode(getitem[0].branch_HAMI.SupplierCode);
			svsProductsOrderData.setHAMIWarehouse(getitem[0].branch_HAMI.SendingDC);	
			svsProductsOrderData.setHAMIStock(getitem[0].branch_HAMI.SOH);
			
			svsProductsOrderData.setPNTHSupplierCode("N/A");
			svsProductsOrderData.setPNTHWarehouse("PNTH");	
			svsProductsOrderData.setPNTHStock("N/A");	
	
			svsProductsOrderData.setCHCHSupplierCode("N/A");
			svsProductsOrderData.setCHCHWarehouse("CHCH");	
			svsProductsOrderData.setCHCHStock("N/A");	
			
		} else if (getitem[0].branch_HAMI != null && getitem[0].branch_PNTH != null && getitem[0].branch_CHCH == null){

			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode(getitem[0].branch_HAMI.SupplierCode);
			svsProductsOrderData.setHAMIWarehouse(getitem[0].branch_HAMI.SendingDC);	
			svsProductsOrderData.setHAMIStock(getitem[0].branch_HAMI.SOH);
			
			svsProductsOrderData.setPNTHSupplierCode(getitem[0].branch_PNTH.SupplierCode);
			svsProductsOrderData.setPNTHWarehouse(getitem[0].branch_PNTH.SendingDC);	
			svsProductsOrderData.setPNTHStock(getitem[0].branch_PNTH.SOH);

			svsProductsOrderData.setCHCHSupplierCode("N/A");
			svsProductsOrderData.setCHCHWarehouse("CHCH");	
			svsProductsOrderData.setCHCHStock("N/A");

		} else if (getitem[0].branch_HAMI == null && getitem[0].branch_PNTH != null && getitem[0].branch_CHCH == null){

			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode("N/A");
			svsProductsOrderData.setHAMIWarehouse("HAMI");	
			svsProductsOrderData.setHAMIStock("N/A");
			
			svsProductsOrderData.setPNTHSupplierCode(getitem[0].branch_PNTH.SupplierCode);
			svsProductsOrderData.setPNTHWarehouse(getitem[0].branch_PNTH.SendingDC);	
			svsProductsOrderData.setPNTHStock(getitem[0].branch_PNTH.SOH);	
	
			svsProductsOrderData.setCHCHSupplierCode("N/A");
			svsProductsOrderData.setCHCHWarehouse("CHCH");	
			svsProductsOrderData.setCHCHStock("N/A");

		} else if (getitem[0].branch_HAMI == null && getitem[0].branch_PNTH != null && getitem[0].branch_CHCH != null){

			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode("N/A");
			svsProductsOrderData.setHAMIWarehouse("HAMI");	
			svsProductsOrderData.setHAMIStock("N/A");
			
			svsProductsOrderData.setPNTHSupplierCode(getitem[0].branch_PNTH.SupplierCode);
			svsProductsOrderData.setPNTHWarehouse(getitem[0].branch_PNTH.SendingDC);	
			svsProductsOrderData.setPNTHStock(getitem[0].branch_PNTH.SOH);	
	
			svsProductsOrderData.setCHCHSupplierCode(getitem[0].branch_CHCH.SupplierCode);
			svsProductsOrderData.setCHCHWarehouse(getitem[0].branch_CHCH.SendingDC);	
			svsProductsOrderData.setCHCHStock(getitem[0].branch_CHCH.SOH);	

		} else if (getitem[0].branch_HAMI == null && getitem[0].branch_PNTH == null && getitem[0].branch_CHCH != null){

			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode("N/A");
			svsProductsOrderData.setHAMIWarehouse("HAMI");	
			svsProductsOrderData.setHAMIStock("N/A");
			
			svsProductsOrderData.setPNTHSupplierCode("N/A");
			svsProductsOrderData.setPNTHWarehouse("PNTH");	
			svsProductsOrderData.setPNTHStock("N/A");	
	
			svsProductsOrderData.setCHCHSupplierCode(getitem[0].branch_CHCH.SupplierCode);
			svsProductsOrderData.setCHCHWarehouse(getitem[0].branch_CHCH.SendingDC);	
			svsProductsOrderData.setCHCHStock(getitem[0].branch_CHCH.SOH);	

		}
		else
		{
			svsProductsOrderData.setOrderSKU(getitem[0].sku);
			svsProductsOrderData.setHAMISupplierCode("N/A");
			svsProductsOrderData.setHAMIWarehouse("HAMI");	
			svsProductsOrderData.setHAMIStock("N/A");
			
			svsProductsOrderData.setPNTHSupplierCode("N/A");
			svsProductsOrderData.setPNTHWarehouse("PNTH");	
			svsProductsOrderData.setPNTHStock("N/A");	
	
			svsProductsOrderData.setCHCHSupplierCode("N/A");
			svsProductsOrderData.setCHCHWarehouse("CHCH");	
			svsProductsOrderData.setCHCHStock("N/A");	
		} 
	}

	// Show Send Product Modal
	$scope.showModalSendProduct = function (ordernumber){
		svsPrepareSendOrder.setOrderNumber(ordernumber);
		$rootScope.showSendProductModal = true;
	}

	// Show Cancel Order Product Modal
	$scope.showCancelProductModal = function (ordernumber){
		svsCancelOrder.setOrderNumber(ordernumber);
		$rootScope.showCancelProductModal = true;
	}

	// Refresh Data
	$scope.refreshData = function(){
		// $scope.today = new Date();
		$scope.loader = false;
		$scope.tbody = false;
		$scope.getapiNoTimeOut();
	}

	// SEARCH TRIGGER
	$scope.searchTrigger = function(){

		$scope.request.s = $scope.searchF;
		console.log($scope.request.s);
		$scope.request.page = 1;
		if($scope.allTotal == 0)
		{
			console.log("TRUE")
			console.log($scope.allTotal);
			$scope.noData = true;
			$scope.httpApiPromise();
		}else{
			console.log("FALSE")
			$scope.httpApiPromise();
			$scope.noData = false;
		}

	}

	// CHANGE PAGE
	$scope.changePage = function(){

		if(parseInt($scope.request.page) > parseInt($scope.totalPage)){
			$scope.request.page  = $scope.totalPage;
			console.log($scope.request);
		}
		else if(parseInt($scope.request.page) <= 0){
			$scope.request.page = 1;
		}else{ 
			$scope.httpApiPromise();
		}
	}

	// $scope.arrlist = [{
	// 	"userid": 1,
	// 	"name": "Suresh"
	// 	}, {
	// 	"userid": 2,
	// 	"name": "Rohini"
	// 	}, {
	// 	"userid": 3,
	// 	"name": "Praveen"
	// 	}];

	// SELECT ITEM
	$scope.selectItem = function(event){

		$scope.orders  = $scope.orders.map((item , index) => {
			return {...item , is_Selected : !item.is_Selected};
		})
		
		
	}

	//CALL FUNCTION FOR DATATABLE (IMPORTANT)
    $scope.getapi();

	// $scope.getapiNoTimeOut();
   
   
   console.log($scope.orders);
	

	$scope.dataTableOpt = {
   //custom datatable options 
  // or load data through ajax call also
  "aLengthMenu": [[2 ,10, 50, 100,-1], [2 ,10, 50, 100,'All']],
  "aoSearchCols": [
      null
    ],
	"columnDefs": [
      { orderable: false, "targets": 0 ,"width": "10px",},
      { orderable: false, "targets": 11 },
      { "width": "30%", "targets": 1 },
      { "width": "10%", "targets": 2 },
      { "width": "35%", "targets": 3 },
      { "width": "45px", "targets": 7 },
    ],
	"autoWidth": true
  };

    /* angular.element(document).ready( function () {

         dTable = jQuery('#user_table')
         dTable.DataTable();
     });
	  */
 })

