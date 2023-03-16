var app = angular.module('FormApp', ['ngResource' , 'ui.bootstrap','ui.utils']);

/* app.factory('ProductOrder', function($resource) 
{
	console.log("sample");
     return {}; // Note the full endpoint address
});  
  */


app.factory('GetAllOrder', function($resource ,  $q , $cacheFactory) 
{ 
	
	// console.log(date , "the date");
	
	// var cache = $cacheFactory('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/');
	// cache.remove('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/');
     return $resource('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/?', 
	 {},
	 {	// Let's make the `query()` method cancellable
		query: {
			method: 'GET',
			
			dataType: 'json',
			headers: {
				"Content-Type": "application/json",
				'Cache-Control' : 'no-cache',
				'cache': false,
				'Pragma' : 'no-cache'
			},
			isArray: true,
			cancellable:  true
		}
		
	 }
	);  // Note the full endpoint address
});  

app.directive('productList',function(){
var date = new Date().getTime().toString();
	return {
		replace: true,
		restrict:'E',
		'cache': false,
		// controller: 'menuctrl',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/ProductList.html'+'?t='+ date,
	
    }
})

app.directive('productListPagination',function(){

	return {
		replace: true,
		restrict:'E',
		// controller: 'menuctrl',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/ProductListPagination.html',
	
    }
})

app.directive('showOrdersFooter',function(){

	return {
		replace: true,
		restrict:'E',
		// controller: 'menuctrl',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/ShowOrdersFooter.html',
	
    }
})

app.directive('svsStatusListMenu',function(){

	return {
		replace: true,
		restrict:'E',
		// controller: 'menuctrl',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/SVSStatusListMenu.html',
	
    }
})

app.directive('svsSendProductModal',function(){

	return {
		replace: true,
		restrict:'E',
		controller: 'SVSSendProduct',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/SVSSendProductModal.html',
	
    }
})

app.directive('svsCancelProductModal',function(){

	return {
		replace: true,
		restrict:'E',
		controller: 'SVSCancelProductModal',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/SVSCancelProductModal.html',
	
    }
})

app.directive('svsCheckProduct',function(){

	return {
		replace: true,
		restrict:'E',
		// controller: 'SVSCheckProduct',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/SVSCheckProduct.html',
	
    }
})


app.factory('svsPrepareSendOrder', function ($q , $http) {
	var data = [];
	return { 
		getOrderData: function () {
			return data;
		},
		setOrderNumber: function (orderNumber) {
			
			
			var deferred = $q.defer();  
			var config = {
				params : {request: "get_order" , id : orderNumber},
				headers : {'Accept' : 'application/json'}
			};

			$http.get('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/', config)
			.then(function(response) {
			   // process response here..
				deferred.resolve(response);
				console.log(response, "counting")
			 }, function(response) {
				  deferred.reject("Error! @factory.getAllConsultedClientsLogs");
			});

		  data = deferred.promise;
				
				// data = orderNumber;
			}
	}
})

app.factory('svsCancelOrder', function ($q , $http) {
	var data = [];
	return { 
		getOrderData: function () {
			return data;
		},
		setOrderNumber: function (orderNumber) {
			
			
			var deferred = $q.defer();  
			var config = {
				params : {request: "get_order" , id : orderNumber},
				headers : {'Accept' : 'application/json'}
			};

			$http.get('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/', config)
			.then(function(response) {
			   // process response here..
				deferred.resolve(response);
				console.log(response, "test")
			 }, function(response) {
				  deferred.reject("Error! @factory.getAllConsultedClientsLogs");
			});

		  data = deferred.promise;
				
				// data = orderNumber;
			}
	}
})


app.factory('svsProductsOrderData', function () {

		var data = {SKU : 0, ProductID : 0, HAMISupplierCode : null, HAMIWarehouse : null, HAMIStock : null, PNTHSupplierCode : null, PNTHWarehouse : null,  PNTHStock : null, CHCHSupplierCode : null, CHCHWarehouse : null, CHCHStock : null};

		return {
			// DATA SKU
			getOrderSKU: function () {
				return data.SKU;
			},
			setOrderSKU: function (SKU) {
				data.SKU = SKU;
			},
			// DATA ORDER ID
			getOrderProductID: function () {
				return data.ProductID;
			},
			setProductID: function (ProductID) {
				data.ProductID = ProductID;
			},

			// DATA HAMI
			getOrderHAMISupplierCode: function () {
				return data.HAMISupplierCode;
			},
			setHAMISupplierCode: function (HAMISupplierCode) {
				data.HAMISupplierCode = HAMISupplierCode;
			},
			getOrderHAMIWarehouse: function () {
				return data.HAMIWarehouse;
			},
			setHAMIWarehouse: function (HAMIWarehouse) {
				data.HAMIWarehouse = HAMIWarehouse;
			},
			getOrderHAMIStock: function () {
				return data.HAMIStock;
			},
			setHAMIStock: function (HAMIStock) {
				data.HAMIStock = HAMIStock;
			},

			// PNTH
			getOrderPNTHSupplierCode: function () {
				return data.PNTHSupplierCode;
			},
			setPNTHSupplierCode: function (PNTHSupplierCode) {
				data.PNTHSupplierCode = PNTHSupplierCode;
			},
			getOrderPNTHWarehouse: function () {
				return data.PNTHWarehouse;
			},
			setPNTHWarehouse: function (PNTHWarehouse) {
				data.PNTHWarehouse = PNTHWarehouse;
			},

			getOrderPNTHStock: function () {
				return data.PNTHStock;
			},
			setPNTHStock: function (PNTHStock) {
				data.PNTHStock = PNTHStock;
			},

			// CHCH
			getOrderCHCHSupplierCode: function () {
				return data.CHCHSupplierCode;
			},
			setCHCHSupplierCode: function (CHCHSupplierCode) {
				data.CHCHSupplierCode = CHCHSupplierCode;
			},
			getOrderCHCHWarehouse: function () {
				return data.CHCHWarehouse;
			},
			setCHCHWarehouse: function (CHCHWarehouse) {
				data.CHCHWarehouse = CHCHWarehouse;
			},

			getOrderCHCHStock: function () {
				return data.CHCHStock;
			},
			setCHCHStock: function (CHCHStock) {
				data.CHCHStock = CHCHStock;
			},

		};
	});



app.factory('svsOrdersItemsCount', function($q, $http) {
	
	var data = ["nodata"];
	
  return {
    getAllItemsCount: function() {
      return data;
    },
	refreshData : function() {
		
      var deferred = $q.defer();  
		var config = {
		 headers : {'Accept' : 'application/json'}
		};

		$http.get('https://vetpost.co.nz/wp-json/order/counts_per/status/', config)
		.then(function(response) {
		   // process response here..
		    deferred.resolve(response);
			// console.log(response, "counting")
		 }, function(response) {
			  deferred.reject("Error! @factory.getAllConsultedClientsLogs");
		});

      data = deferred.promise;
    }
  }
});
	
app.directive('svsProductsOrderModal',function(){

	return {
		replace: true,
		restrict:'E',
		controller: 'SVSProductsOrder',
		transclude:true,
        // scope :{menus:'=list'}, 
			// template: "<h1>Sample Good</h1>"
       templateUrl: path_plugin_vetpost_custom.template_url+'core/includes/assets/angular-views/SVSProductsOrderModal.html',
	
    }
})
app.run( function($rootScope) {
    // $rootScope.titleapp ="app running file";
	
	$rootScope.showModal = false;
	$rootScope.showModalID = 0;

	$rootScope.showSendProductModal = false;
	$rootScope.showCancelProductModal = false;
});

app.controller("vetpostMainCont" , function($scope, $http , $document ,  $rootScope , svsOrdersItemsCount){
	
	
	 $scope.OrdersItemsCount = {
		 "statusAll" : 0,
		 "onHold" : 0,
		 "readySend" : 0,
		 "svsCandidates" : 0,
		 "svsProcessing" : 0,
		 "refunded" : 0,
		 "pending" : 0,
		 "processing" : 0,
		 "partial" : 0,
		 "completed" : 0,
		 "removed" : 0,
		 "error" : 0,
	};
	
	 
	 $scope.$watch(function() {
			return svsOrdersItemsCount.getAllItemsCount();
		}, function(value) {
		  // console.log(value);
		  value.then(function(respo) {
				console.log(respo.data,"itemcount");
								

				 var itemsCount = {
					"statusAll" : 0,
					"onHold" : respo.data["wc-on-hold"],
					"readySend" : respo.data["wc-ready-send"],
					"svsCandidates" : respo.data["wc-svs-candidates"],
					"svsProcessing" : respo.data["wc-svs-processing"],
					"refunded" : respo.data["wc-refunded"],
					"pending" : respo.data["wc-pending"],
					"processing" : respo.data["wc-processing"],
					"partial" : respo.data["wc-partial"],
					"completed" : respo.data["wc-completed"],
					"removed" : respo.data["wc-cancelled"],
					"error" : respo.data["wc-failed"],
				};
				
				const values = Object.values(itemsCount);

				const sum = values.reduce((accumulator, value) => {
					return parseInt(accumulator) + parseInt(value);
				  }, 0);

				 itemsCount.statusAll  = sum;
				 
				// console.log(sum);
				
				// if(respo.status == "onHold")itemsCount.onHold++;
				// if(respo.status == "svsCandidates")itemsCount.svsCandidates++;
				// if(respo.status == "readySend")itemsCount.readySend++;
				// if(respo.status == "partial")itemsCount.partial++;
				// if(respo.status == "completed")itemsCount.completed++;
				// if(respo.status == "removed")$itemsCount.removed++;
				// if(respo.status == "error")itemsCount.error++;
				// if(respo.status == "svsProcessing")itemsCount.svsProcessing++;
				
				
				// console.log(itemsCount);
				
				 $scope.OrdersItemsCount = itemsCount;
				
				
			});
		}); 
	 
	
/* 	$scope.spammerkiller = ()=>{
		
		 
	 } */	
})

 app.controller("SVSProductsOrder" , function($scope, $http , $document ,  $rootScope , svsProductsOrderData){
	 
	
	 // $scope.requestProduct = DataTransfer.getUserDetails();
	 
	  $scope.SKU = svsProductsOrderData.getOrderSKU();
	  $scope.ProductID = svsProductsOrderData.getOrderProductID();
	  $scope.HAMISupplierCode = svsProductsOrderData.getOrderHAMISupplierCode();
	  $scope.HAMIWarehouse = svsProductsOrderData.getOrderHAMIWarehouse();
	  $scope.HAMIStock = svsProductsOrderData.getOrderHAMIStock();

	  $scope.PNTHSupplierCode = svsProductsOrderData.getOrderPNTHSupplierCode();
	  $scope.PNTHWarehouse = svsProductsOrderData.getOrderPNTHWarehouse();
	  $scope.PNTHStock = svsProductsOrderData.getOrderPNTHStock();

	  $scope.CHCHSupplierCode = svsProductsOrderData.getOrderCHCHSupplierCode();
	  $scope.CHCHWarehouse = svsProductsOrderData.getOrderCHCHWarehouse();
	  $scope.CHCHiStock = svsProductsOrderData.getOrderCHCHStock();
	  
	  $scope.$watch(function() {
			return svsProductsOrderData.getOrderSKU();
		}, function(value) {
		   $scope.SKU = value;
		});

		$scope.$watch(function() {
		  $scope.ProductID = svsProductsOrderData.getOrderProductID();
		}, function(value) {
		   $scope.ProductID = value;
		});
		
		// HAMI
		$scope.$watch(function() {
			$scope.HAMISupplierCode = svsProductsOrderData.getOrderHAMISupplierCode();
		  }, function(value) {
			 $scope.HAMISupplierCode = value;
		  });

		  $scope.$watch(function() {
			$scope.HAMIWarehouse = svsProductsOrderData.getOrderHAMIWarehouse();
		  }, function(value) {
			 $scope.HAMIWarehouse = value;
		  });

		  $scope.$watch(function() {
			$scope.HAMIStock = svsProductsOrderData.getOrderHAMIStock();
		  }, function(value) {
			 $scope.HAMIStock = value;
		  });


		//	PNTH
		$scope.$watch(function() {
			$scope.PNTHSupplierCode = svsProductsOrderData.getOrderPNTHSupplierCode();
		  }, function(value) {
			 $scope.PNTHSupplierCode = value;
		  });

		  $scope.$watch(function() {
			$scope.PNTHWarehouse = svsProductsOrderData.getOrderPNTHWarehouse();
		  }, function(value) {
			 $scope.PNTHWarehouse = value;
		  });

		  $scope.$watch(function() {
			$scope.PNTHStock = svsProductsOrderData.getOrderPNTHStock();
		  }, function(value) {
			 $scope.PNTHStock = value;
		  });

		//	PNTH
		$scope.$watch(function() {
			$scope.CHCHSupplierCode = svsProductsOrderData.getOrderCHCHSupplierCode();
		  }, function(value) {
			 $scope.CHCHSupplierCode = value;
		  });

		  $scope.$watch(function() {
			$scope.CHCHWarehouse = svsProductsOrderData.getOrderCHCHWarehouse();
		  }, function(value) {
			 $scope.CHCHWarehouse = value;
		  });

		  $scope.$watch(function() {
			$scope.CHCHStock = svsProductsOrderData.getOrderCHCHStock();
		  }, function(value) {
			 $scope.CHCHStock = value;
		  });

	  /* $scope.$watch('DataTransfer', function (newVal) {
		  $scope.requestProduct = newVal;
		  console.log(functionItem);
	  }); */
	 
	/* $scope.svsProductDetails = function () {
		console.log('Going to call fullname method from second controller');
		//Reading the method in first controller inside the second one
		var functionItem = DataTransfer.getUserDetails();
		// var details = functionItem();
		console.log(functionItem);
		
		return functionItem;
	}
	 */
	 
	  
	 $scope.closeModal = ()=>{
		  $rootScope.showModal = false;
		  // console.log($scope.request);  
	 }
 })

// SVS SEND PRODUCT CONTROLLER
 app.controller("SVSSendProduct", function($scope, $http , $document ,  $rootScope , svsPrepareSendOrder){
	$scope.orderData = 0;
	$scope.branches = [
		{metaKey : "branch_HAMI" , city : "Hamilton" , symbol : "H" , abbreviation : "HAMI"},
		{metaKey : "branch_PNTH" , city : "Palmerston North " , symbol : "P" , abbreviation : "PNTH"},
		{metaKey : "branch_CHCH" , city : "Christchurch " , symbol : "S" , abbreviation : "CHCH"} ,
	];
	$scope.selectedBranch = $scope.branches[0];
	
	
	$scope.addOnProduct = [
		{
			addsOn :  true,
			isDisable : true,
			name: "Human Treats" ,
			sku: "19146",
			category : "Human",
			isSelected : true,
			branch_HAMI: {
				SOH : "100"
			},
			branch_PNTH: {
				SOH : "100"
			},
			branch_CHCH: {
				SOH : "100"
			}
		},{
			addsOn :  true,
			name: "Dog Treats" ,
			sku: "19145",
			category : "Dogs",
			isSelected : false,
			branch_HAMI: {
				SOH : "100"
			},
			branch_PNTH: {
				SOH : "100"
			},
			branch_CHCH: {
				SOH : "100"
			}
		},{
			addsOn :  true,
			name: "Cat Treats" ,
			sku: "19144",
			category : "Cats",
			isSelected : false,
			branch_HAMI: {
				SOH : "100"
			},
			branch_PNTH: {
				SOH : "100"
			},
			branch_CHCH: {
				SOH : "100"
			}
		},{
			addsOn :  true,
			name: "Horse Treats" ,
			sku: "19156",
			isSelected : false,
			category : "Horses",
			branch_HAMI: {
				SOH : "100"
			},
			branch_PNTH: {
				SOH : "100"
			},
			branch_CHCH: {
				SOH : "100"
			}
		}
	];
	
	$scope.modalTbody = false;
	$scope.modalLoader = true;

	$scope.$watch(function() {
		return svsPrepareSendOrder.getOrderData();
	}, function(value) {
		$scope.modalTbody = true;
		$scope.modalLoader = false;
		value.then(function(respo) {
			console.log(respo.data.data , "svsPrepareSendOrder");
			
			if(respo.data.data !== undefined){
				$scope.orderData = respo.data.data ;
				var includedAddOn = [0];
				if($scope.orderData.line_items.length != 0){
					
					var line_items = ($scope.orderData.line_items).map(item => {
						
					  $scope.addOnProduct.map((subitem , key) =>{
						   
						  console.log(item.categories); 
						  console.log(subitem.category , "category"); 
						 if(item.categories !== undefined && item.categories.includes(subitem.category)){
							// if(!includedAddOn.includes(key))
							includedAddOn.push(parseInt(key));
						}
						 /*  if(item.categories !== undefined) return  item.categories.includes($scope.addOnProduct[key].category);; */
						 
					   });
					   /* console.log($scope.addOnProduct[keys]); */
					   
					  /*  if(!includedAddOn.includes(key))includedAddOn.push(parseInt(key)); */
					   return {...item , isSelected : true};
					   
					}).concat(($scope.addOnProduct).filter((item , index) => includedAddOn.includes(index)));
					
					$scope.orderData["line_items"] = line_items.filter(item => {
						console.log(item , 'line_items');
						
					   return item['sku'] != null && item['sku'].match("^[0-9\(\)]+$") != null;
					} );
					
					console.log(includedAddOn , "includedAddOn");
				}
			}
			
		})
	   // $scope.orderNo = value;
	});
	
	$scope.refreshSendOrder = (ordernumber) =>{
		console.log($scope.addOnProduct);
		svsPrepareSendOrder.setOrderNumber(ordernumber);
	}


	$scope.sendOrder = (ordernumber) =>{
		
		// var r=confirm("Press a button");
		// if (r==true)
		//   {
			
			  x="You pressed OK!";
			  console.log("Send Status");
			  console.log($scope.orderData);
			  
			  // {{orderData.shipping.address_1}}, {{orderData.shipping.city}}, {{orderData.shipping.state}} {{orderData.shipping.postcode}}
			  // {{orderData.shipping.address_2}}, {{orderData.shipping.city}}, {{orderData.shipping.state}} {{orderData.shipping.postcode}}
			
			 var readySendDataJson =  {
				Date: $scope.orderData.date_created,
				OrderNumber: $scope.orderData.id,
				Comments: $scope.orderData.customer_note,
				DropShip: 'Y',
				DropName: ($scope.orderData.billing.first_name??"") +" "+ ($scope.orderData.billing.last_name??"") ,
				address1: $scope.orderData.shipping.address_1,
				address2: $scope.orderData.shipping.address_2,
				city: $scope.orderData.shipping.city,
				zip: $scope.orderData.shipping.postcode,
				Product: [],
			}
			var objectProductselected = ($scope.orderData).line_items.reduce((item , row , index) => {
				/* console.log(item.quantity);
				console.log(item.sku);
				console.log(index); */
				if(row.isSelected)item.push({
					"Line" : index,
					"Code" : row.sku,
					"Quantity" : row.quantity??1,
				});
				
				return item;
			} , []);
			readySendDataJson.Product = objectProductselected;
			console.log(objectProductselected);
			/* console.log(readySendDataJson); */
			 var data = {
				"data" : {
					"status" : "ready-send",
					"meta_data": [
						{
						"key": "ready-send-datajson",
						"value": JSON.stringify(readySendDataJson)
						}
					]
				},
				"orderId": ordernumber
			   
			};
				$http.post('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/?request=update_order', JSON.stringify(data)).then(function (response) {
					console.log(response);
					// if (response.data)
					// $scope.msg = "Post Data Submitted Successfully!";
			
				}, function (response) {
				/* 	
					$scope.msg = "Service not Exists";
					$scope.statusval = response.status;
					$scope.statustext = response.statusText;
					$scope.headers = response.headers(); */
				
				});
		//   }
		// else
		//   {
		// 		x="You pressed Cancel!";
		//   }
		
		$rootScope.showSendProductModal = false;
		swal("Success", "You orderd are ready to send!", "success");
	}
	$scope.closeSendProductModal = () =>{
		$rootScope.showSendProductModal = false;
   }
 })
 
 app.controller("SVSCancelProductModal" , function($scope, $http , $document ,  $rootScope , svsCancelOrder){

	$scope.$watch(function() {
		return svsCancelOrder.getOrderData();
	}, function(value) {
		value.then(function(respo) {
			console.log(respo.data.data , "getCancel");
			
			if(respo.data.data !== undefined){
				$scope.orderData = respo.data.data ;
				var includedAddOn = [0];
				if($scope.orderData.line_items.length != 0){
					
					$scope.orderData["line_items"] = ($scope.orderData.line_items).map(item => {
						
					   var key = Object.keys($scope.addOnProduct).find(key =>{
						   
						  if(item.categories !== undefined) return  item.categories.includes($scope.addOnProduct[key].category);;
						 
					   });
					   /* console.log($scope.addOnProduct[keys]); */
					   
					   if(!includedAddOn.includes(key))includedAddOn.push(parseInt(key));
					   return {...item , isSelected : true};
					}).concat(($scope.addOnProduct).filter((item , index) => includedAddOn.includes(index)));
					console.log(includedAddOn , "includedAddOn");

				}
			}
			
		})
	   // $scope.orderNo = value;
	});
	
	$scope.cancelOrder = (ordernumber) =>{

			//   x="You pressed OK!";
			  console.log("Send Status");
			  console.log($scope.orderData);
			  
			  // {{orderData.shipping.address_1}}, {{orderData.shipping.city}}, {{orderData.shipping.state}} {{orderData.shipping.postcode}}
			  // {{orderData.shipping.address_2}}, {{orderData.shipping.city}}, {{orderData.shipping.state}} {{orderData.shipping.postcode}}
			
			 var readySendDataJson =  {
				Date: $scope.orderData.date_created,
				OrderNumber: $scope.orderData.id,
				Comments: $scope.orderData.customer_note,
				DropShip: 'Y',
				DropName: ($scope.orderData.billing.first_name??"") +" "+ ($scope.orderData.billing.last_name??"") ,
				address1: $scope.orderData.shipping.address_1,
				address2: $scope.orderData.shipping.address_2,
				city: $scope.orderData.shipping.city,
				zip: $scope.orderData.shipping.postcode,
				Product: [],
			}
			var objectProductselected = ($scope.orderData).line_items.reduce((item , row , index) => {
				/* console.log(item.quantity);
				console.log(item.sku);
				console.log(index); */
				if(row.isSelected)item.push({
					"Line" : index,
					"Code" : row.sku,
					"Quantity" : row.quantity??1,
				});
				
				return item;
			} , []);
			readySendDataJson.Product = objectProductselected;
			console.log(objectProductselected);
			/* console.log(readySendDataJson); */
			 var data = {
				"data" : {
					"status" : "cancelled",
					"meta_data": [
						{
						"key": "ready-send-datajson",
						"value": JSON.stringify(readySendDataJson)
						}
					]
				},
				"orderId": ordernumber
			   
			};
				$http.post('https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/?request=update_order', JSON.stringify(data)).then(function (response) {
					console.log(response);
					// if (response.data)
					// $scope.msg = "Post Data Submitted Successfully!";
			
				}, function (response) {
				/* 	
					$scope.msg = "Service not Exists";
					$scope.statusval = response.status;
					$scope.statustext = response.statusText;
					$scope.headers = response.headers(); */
				
				});	


				$rootScope.showCancelProductModal = false;
				swal("Cancelled!", "You orderd has been cancelled!", "success");

	}

	$scope.closeCancelProductModal = ()=>{
		 $rootScope.showCancelProductModal = false;
		 // console.log($scope.request);  
	}
})

 app.controller("EditProductForm" , function($scope, $http , $document){
	// $scope.id = ;
		$scope.productid = -1;
		
	   $scope.updatePopulation = function() {
		   console.log($scope.productid);
		   
		 
		    $http({method : 'GET',
			url : 'https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/?request=get_order&id=' + $scope.productid ,
			dataType: 'json',
			headers: {
				"Content-Type": "application/json"
			}
			})
			.success(function(data, status) {
				
				$scope.info = data;
				$scope.products = data.data.line_items;
				console.log(data);
			})
			.error(function(data, status) {
				alert("Error");
			}); 
		   }
	   $scope.ClearForm = function() {
        
			console.log("Clear");
			$scope.productid = -1;
			$scope.info = [];
			$scope.products = [];
			
		};
	 /*  $scope.fetchData = function(){
	  $http.get('fetch_data.php').success(function(data){
		$scope.namesData = data;
	  });
	 };  */
	 
	 
	
	
}) 


app.controller("AddProductOrder" , function($scope, $http){
	 
	 $scope.newItemListID = -1;
	 $scope.newItemList = [];
	 $scope.quantityrepeat = [];
	 
	 $scope.addProductEvent = function() {
		 /* console.log("AddProductOrder");
		console.log($scope.info);
		console.log($scope.products); */
		  $scope.products.push($scope.newItemList); 
	 }
	 
	 
	
			
			
	 $scope.removeProductList = function(event) {
		 // 
		 var productid = event.currentTarget.getAttribute("data-productid");
		 console.log(productid);
		 $scope.newItemList = $scope.newItemList.filter(item => 
				
					item.product_id !== Number(productid)
				
				);  
		console.log( $scope.newItemList);
	 }
	 $scope.addProductInList = function() {
		 console.log("addProductInList");
		 
		    $http({method : 'GET',url : 'https://vetpost.co.nz/wp-content/plugins/vetpost-api/api/?request=get_products&id=' + $scope.newItemListID })
			.success(function(data, status) {
				
				/* $scope.info = data;
				$scope.products = data.data.line_items; */
				var data = data.data;
				// console.log( data);
				
			
				const isExist = $scope.newItemList.filter(item => 
				
					item.product_id == data.id
				
				);
				console.log($scope.newItemList);
				
				if(isExist.length === 0) {
					$scope.newItemList.push({
						name: data.name,
						image: {
							id: data.images[0].id,
							src: data.images[0].src
						},
						price : data.price ,
						product_id : data.id ,
						sku : data.sku ,
						subtotal : data.price  ,
						subtotal_tax : 0,
						tax_class : data.tax_class ,
						taxes :  [],
						total : data.price,
						total_tax : 0.,
						quantity : 1,
						variation_id : data.variations,
						
					}); 
				} else {
					
					$scope.newItemList = $scope.newItemList.map((list, index) => {
                        if(list.product_id === data.id){
                            return {...list , quantity :( $scope.newItemList[index].quantity+ 1)}
                        }
                        return list;
                    })
					
					
					/* $scope.newItemList
					
					$scope.newItemList.quantity = $scope.newItemList.quantity++;
					$scope.newItemList.price = $scope.newItemList.price * $scope.newItemList.quantity; */
				}
						
				
				/*  $scope.newItemList.push({
						name: data.name,
						image: {
							id: data.images[0].id,
							src: data.images[0].src
						},
						price : data.price ,
						product_id : data.id ,
						sku : data.sku ,
						subtotal : data.price  ,
						subtotal_tax : 0,
						tax_class : data.tax_class ,
						taxes :  [],
						total : data.price,
						total_tax : 0.,
						variation_id : data.variations,
						
					});  */
				// console.log( $scope.newItemList);
			})
			.error(function(data, status) {
				alert("Error");
			}); 
		 
		 // get_products
		/* console.log($scope.info);
		console.log($scope.products);
		  $scope.products.push({
            name: 'Equine 1 litre',
			image: {
				id: "572402",
				src: "https://vetpost.co.nz/wp-content/uploads/2022/08/oxbow-critical-care-herbivore-fine-grind.jpg"
			}
			
        });  */
	 }
	 
 })
