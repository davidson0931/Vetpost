// PLEASE PAY ATTENTION TO THE COMMENTS YOU SEE
// THE COMMENTS MAY HELP YOU TO FIND WHAT YOU'RE LOOKING FOR
// IF YOU SEE A FUNCTION BLOCK OF CODE WITH NO COMMENT PLEASE ADD A COMMENT OF WHAT THAT FUNCTION DO

var main_table;
var classColors = {
                    "pending"           : "_pending",
                    "processing"        : "_processing",
                    "on-hold"           : "_onHold",
                    "cancelled"         : "_cancelled",
                    "refunded"          : "_refunded",
                    "completed"         : "_completed",
                    "failed"            : "_failed",
                    "checkout-draft"    : "_checkoutDraft"
                  };

var monthNames = [ 
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" 
                 ];

var productData, productTableData = [], productTableDataName = [], addedProducts = [], options = [],
    tax_rate_value = [], tax_rate_is_active = false, tax_lines = [], product_lines = [],
    editOrderData, shipping_lines = [], shipping_lines_Data = [], fee_lines=[];




// export{productTableDataName}

(function( $ ) {



    
$(document).ready(function() 
{
    $('#loader').addClass("hide-loader");

    // LIST ALL TAX RATES
    /* $.ajax({
        type: "GET",
        url: `${vetpostapi.vetpostApiUrl}get_tax_rates`,
        success: function (response) {
            let tax_array = JSON.parse(response);
            tax_array.forEach(tax => {
                $("._taxRatesRows").append(`<tr class="p-2">
                       
                                        <td>
                                            <input type="radio" 
                                                   data-taxdata = ${JSON.stringify(tax)} 
                                                   id="tax-${tax.id}"
                                                   data-inputid = ${tax.id} 
                                                   class="_taxRadioButton" 
                                                   value="${Number(tax.rate).toFixed(2) / 100}">
                                            <button hidden data-taxremoveid = "${tax.id}" class="btn btn-sm _tax-id-${tax.id} _taxRemoveButton">
                                                    <i class="bi bi-x-circle"></i>
                                            </button>
                                        </td>
                                        <td>${tax.name}</td>
                                        <td class="text-start">${tax.class}</td>
                                        <td>${tax.country}-${tax.name}-${tax.priority}</td>
                                        <td class="text-end">${Number(tax.rate.toString())}%</td>
                                        </tr>
                                        `);
            });
        }
    }); */
    // END OF LIST ALL TAX RATES


    // LIST ALL PRODUCT FOR ADD PRODUCTS
    $.ajax({
        type: "GET",
        url: `${vetpostapi.vetpostApiUrl}all_products`,
        success: function (response) 
        {
            options.push(`<option disabled value="placeholder" selected>Select a product</option>`);
            let _productObj = JSON.parse(response);

            productData = _productObj;

            
            _productObj.data.forEach(product => {
                let option = `<option value="${product.id}"
                                     data-productprice ="${product.price}"
                                     data-productname ="${product.name}"
                                     data-productimg ='${product.images[0].src}'
                                     data-productsku = '${product.sku}'
                                     data-taxclass = '${product.tax_class}'
                                     data-variations = '${product.variations ? product.variations.join(', ') : ""}'>
                                      ${product.name}
                             </option>`;
                options.push(option);
            });

            // $('#_productDropDown').html(options).selectpicker('refresh');

          

         
        }
    });
    // END OF LIST ALL PRODUCT FOR ADD PRODUCTS



    // LIST ALL ORDERS
  /*   main_table = $('#_mainTable').DataTable( {
        ajax: `${vetpostapi.vetpostApiUrl}all_orders`,
        columns: [ 
                    {
                        render: function(data,type, row)
                        {
                            let toReturn = `<span class="_orderNumber">
                                            ${row.number}
                                            </span>`;

                            return toReturn;
                        }
                    },
                    {
                        
                        render: function(data, type, row)
                        {
                            let toReturn = `<div class=${classColors[row.status]}>
                                            ${row.status.replace("-", " ")}
                                            </div>`;

                            return toReturn;
                        }
                        
                    },
                    {
                        data: "date_created"
                    },
                    {
                        render: function(data, type, row)
                        {
                            let toReturn = `<div class="_totalCurrency">
                                                <span class="_total">${row.total}</span>
                                                <span class="_currency">${row.currency}</span>
                                            </div>`;

                            return toReturn;
                        }
                    },
                    {
                        sortable: false,
                        render: function(data, type, row)
                        {   
                            let rowData = JSON.stringify(row);
                            let toReturn = `<div class="_tableBtn">
                                            <button type="button" data-ordernum="${row.number}" id="data-${row.id}" class="btn btn-danger btn-sm _btnDelete"><span><i class="bi bi-file-earmark-x"></i></span></button>
                                            <button type="button"  data-rowdata = '${rowData}' id="data-${row.id}" class="btn btn-primary _btnEdit"><span><i class="bi bi-pencil-square"></i></span></button>
                                            </div>`;

                            return toReturn;
                        }
                    }           
                 ]

                
    } ); */

    // main_table.columns( 1 ).visible( false );
    // END OF LIST ALL ORDERS


    // SIDE NAV BEHAVIOURS 
    $("._side-button").click(function (e) { 
        e.preventDefault();
        $("._side-button").removeClass("_side-button-active");
        $(this).addClass("_side-button-active");

        let page_id = $(this).attr("id");

        if(page_id)
        {
            $(".pages").attr("hidden", true);
            $(`section[id="${page_id}"`).attr("hidden", false);
            $("._editForm").attr("hidden", true);
        }
    });
   


    // SIDE NAV COLLAPSABLE NAVIGATORS
   
    $("._order-link").click(function (e) { 
        e.preventDefault();

        // SET ACTIVE NAVIGATOR
        $("._order-link").removeClass("_order-link-active");
        $(this).addClass("_order-link-active");

        let page_id = $(this).attr("id");

    
        $("._tableContainer").attr("hidden", false);

        // HIDE EDIT FORM
        $("._editForm").attr("hidden", true);


        // FILTER ORDER'S TABLE BY ORDER STATUS
        if(page_id)
        {
          
                let filter = page_id.replace('-', " ");
                // main_table.column(1).search(page_id != "all_orders" ? filter : "");
                

                if(page_id != "all_orders")
                {
                    // main_table.column( 1 ).visible( true ).draw();
                }
                else
                {
                    // main_table.column( 1 ).visible( false ).draw();

                }
    
            
        }
    });
     // END OF SIDE NAV BEHAVIOURS 


    
});



// DELETE ORDER FUNCTION
$(document).on("click", "._btnDelete",   function ()
{
  $(this).attr("hidden", false);
  $('#spinnerModal').modal({backdrop: 'static', keyboard: false});
  $('#spinnerModal').modal('show');



  let order_number = $(this).data("ordernum");
  let tableRow = $(this);

  
   $.ajax({
        type: "POST",
        url: `${vetpostapi.vetpostApiUrl}delete_order`,
        contentType: "app/json: charset=UTF-8",
        data: JSON.stringify({ order_number: order_number }),
        success: function (response) 
        {
            let objResponse = JSON.parse(response);

            if(objResponse.code == 200)
            {

                tableRow.parent().closest("tr").hide();
                $('#spinnerModal').modal('hide');
                
            }

        }
    });
    


});
// END OF DELETE ORDER FUNCTION



// OPEN EDIT FORM
$(document).on("click", '._btnEdit', function(e)
{
    e.preventDefault();

    let rowData = $(this).data("rowdata");
    editOrderData = rowData;
    // ORDER ID
    $("._orderNumberDetails").text(rowData.id);
    $("#updateForm").attr("ng-init" , "id="+rowData.id+"");
    $("#productID").val(rowData.id);
	$("#productID").trigger("change");
    $("._tableContainer").attr("hidden", true);
    $("._editForm").attr("hidden", false);
});
// END OF OPEN EDIT FORM



// EXIT THE EDIT FORM AND SHOW THE ORDER'S TABLE
$(document).on("click", '._btnBack', function(e)
{

    e.preventDefault();
   
    $("._tableContainer").attr("hidden", false);
    $("._editForm").attr("hidden", true);
    $("._productCardFooter").attr("hidden", false);
    $("._productCardOption").attr("hidden", true);

});
// END OF EXIT THE EDIT FORM AND SHOW THE ORDER'S TABLE



// SHOW AND HIDE PRODUCT TABLE ADD ITEMS OPTIONS
$(document).on("click", "._showAddProductOption", function(e)
{
    e.preventDefault();

    $("._productCardOption").addClass("slideInUp").attr("hidden", false);
    $("._productCardFooter").attr("hidden", true);
    

});




$(document).on("click", "._hideAddProductOption", function(e)
{
    e.preventDefault();

    $("._productCardOption").attr("hidden", true);
    $("._productCardFooter").addClass("slideInDown").attr("hidden", false);
    


});

// END  OF SHOW  AND HODE PRODUCT TABLE ADD ITEMS OPTIONS


// PREVENT NUMBER FIELD TO TAKE NEGATIVE VALUES
$(document).on("change", "#_procductQuantity", function(e){

    if($(this).val() < 0)
    {
        $(this).val(0);
    }

});
// END OF PREVENT NUMBER FIELD TO TAKE NEGATIVE VALUES


/* 
$(document).on("change", "#_productDropDown", function(e)
{
    let product_name = $("#_productDropDown option:selected").data('productname');
    let product_image = $("#_productDropDown option:selected").data('productimg');
    let product_price = $("#_productDropDown option:selected").data('productprice');
    let product_sku = $("#_productDropDown option:selected").data('productsku');
    let product_tax = $("#_productDropDown option:selected").data('taxclass');
    let product_id =  $("#_productDropDown").val();
    let product_quantity = $('#_procductQuantityInput').val();

    if(productTableData.find(prod => prod.product_id == product_id) && productTableDataName.find(prod => prod.product_id == product_id))
    {

        let productDataIndex = productTableData.find(prod => prod.product_id == product_id);
        productDataIndex.quantity += Number(product_quantity);


        let productNameIndex = productTableDataName.find(prod => prod.product_id == product_id);
        productNameIndex.quantity += Number(product_quantity);


        

    }

    else
    {

    

        productTableData.push({ 
                        
                                product_id: product_id,
                                quantity: Number(product_quantity),
                                subtotal: product_price,
                              });

        productTableDataName.push({
                                product_image: product_image,
                                name :  product_name,
                                product_id: product_id,
                                quantity: Number(product_quantity),
                                price: product_price,
                                sku: product_sku,
                                taxclass: product_tax,
                              });

                                                   
    }

     $('#_procductQuantityInput').val(1);
     $("._selectedProducts").empty();
    

    productTableDataName.forEach( async productList => {
            $("._selectedProducts").append(`<tr>
                                            <td>
                                                <div class="_productColumnImg">
                                                <img 
                                                src="${productList.product_image}" 
                                                class="img-fluid _product_image "
                                                id="_image${productList.product_id}"
                                                alt=""
                                                >
                                                </div>
                                            </td>
                                            <td>
                                                <select 
                                                        class="form-control selectpicker _productUpdateImage _readOnlyInput name-${productList.product_id}"" 
                                                        data-live-search="true"
                                                        data-selectid = "${productList.product_id}" 
                                                        data-productprice ="${productList.price}"
                                                        id="_productDropDownEdit${productList.product_id}" 
                                                        data-size="5"
                                                        disabled>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="_readOnlyInput quant-${productList.product_id}" 
                                                       type="number" 
                                                       id="_procductQuantity"
                                                       value="${productList.quantity}"
                                                       readonly/> 
                                            </td>
                                            <td class="text-center">
                                                <button 
                                                        type="button" 
                                                        class="btn btn-small _saveButton${productList.product_id}" 
                                                        id="_saveEditProduct"
                                                        data-productid="${productList.product_id}"
                                                        hidden>
                                                        <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button 
                                                        type="button" 
                                                        class="btn btn-small _editButton${productList.product_id}" 
                                                        id="_editProduct"
                                                        data-productid="${productList.product_id}"
                                                        >
                                                        <i class="bi bi-pencil"></i>
                                                </button>
                                                <button 
                                                        type="button" 
                                                        class="btn btn-small _removeButton" 
                                                        id="_removeProduct"
                                                        data-productid="${productList.product_id}">
                                                        <i class="bi bi-cart-dash"></i>
                                                </button>
                                            </tr>`);

            $(`#_productDropDownEdit${productList.product_id}`).html(options);
            $(`#_productDropDownEdit${productList.product_id}`).selectpicker('val', productList.product_id);
    });
   
  

    $("#_productDropDown").val('default').selectpicker("refresh");
 
}); */

/* 
$(document).on("click", "._removeButton", function(e)
{

    let product_id = $(this).data('productid');
    let product_name_index = productTableDataName.findIndex(x => x.product_id == product_id);
    let product_data_index = productTableData.findIndex(x => x.product_id == product_id);

    productTableDataName.splice(product_name_index, 1);
    productTableData.splice(product_data_index, 1);

    $(this).closest('tr').remove();

}); */

$(document).on("click", "#_editProduct", function(e) { 
    e.preventDefault();

    let elementID = $(this).data('productid');

    $(this).attr('hidden', true);
    $(`._saveButton${elementID}`).attr('hidden', false);

    $(`.name-${elementID}`).attr('disabled', false).selectpicker('refresh');
    $(`.quant-${elementID}`).attr('readonly', false);


});

$(document).on("click", "#_saveEditProduct", function(e)
{

    e.preventDefault();

    let elementID = $(this).data('productid');
    let editNameVal =  $(`#_productDropDownEdit${elementID} option:selected`).text();
    let editProductIdVal = $(`#_productDropDownEdit${elementID}`).val();
    let editQuantVal = $(`.quant-${elementID}`).val();
    let editImageVal = $(`#_productDropDownEdit${elementID} option:selected`).data('productimg');



    if(productTableData.find(prod => prod.product_id == elementID) && productTableDataName.find(prod => prod.product_id == elementID))
    {

        let productDataIndex = productTableData.find(prod => prod.product_id == elementID);
        productDataIndex.product_id = editProductIdVal;
        productDataIndex.quantity = Number(editQuantVal);

        let productNameIndex = productTableDataName.find(prod => prod.product_id == elementID);

        productNameIndex.name = editNameVal;
        productNameIndex.product_id = editProductIdVal;
        productNameIndex.quantity = Number(editQuantVal);
        productNameIndex.product_image = editImageVal;
  

    }


    $(this).attr('hidden', true);
    $(`._editButton${elementID}`).attr('hidden', false);

    $(`.name-${elementID}`).attr('disabled', true).selectpicker('refresh');
    $(`.quant-${elementID}`).attr('readonly', true);



});

$(document).on('click', '#_saveEditProduct', function(e)
{

    let product_id = $(this).data('productid');
    let editNameVal =  $(`#_productDropDownEdit${product_id} option:selected`).text();
    let editProductIdVal = $(`#_productDropDownEdit${product_id}`).val();
    let editQuantVal = $(`.quant-${product_id}`).val();
    let editImageVal = $(`#_productDropDownEdit${product_id} option:selected`).data('productimg');

    let updated_row = `<tr>
                        <td>
                            <div class="_productColumnImg">
                            <img 
                            id="_image${editProductIdVal}"
                            src="${editImageVal}" 
                            class="img-fluid"
                            alt=""
                            >
                            </div>
                        </td>
                        <td>
                            <select 
                                    class="form-control selectpicker _readOnlyInput _productUpdateImage name-${editProductIdVal}"" 
                                    data-live-search="true"
                                    data-productprice ="${productList.price}"
                                    data-selectid = "${productList.product_id}"  
                                    id="_productDropDownEdit${editProductIdVal}" 
                                    data-size="5"
                                    disabled>
                            </select>
                        </td>
                        <td>
                            <input class="_readOnlyInput quant-${editProductIdVal}" 
                                type="number" 
                                id="_procductQuantity"
                                value="${editQuantVal}"
                                readonly/> 
                        </td>
                        <td class="text-center">
                            <button 
                                    type="button" 
                                    class="btn btn-small _saveButton${editProductIdVal}" 
                                    id="_saveEditProduct"
                                    data-productid="${editProductIdVal}"
                                    hidden>
                                    <i class="bi bi-check-lg"></i>
                            </button>
                            <button 
                                    type="button" 
                                    class="btn btn-small _editButton${editProductIdVal}" 
                                    id="_editProduct"
                                    data-productid="${editProductIdVal}"
                                    >
                                    <i class="bi bi-pencil"></i>
                            </button>
                            <button 
                                    type="button" 
                                    class="btn btn-small _removeButton" 
                                    id="_removeProduct"
                                    data-productid="${editProductIdVal}">
                                    <i class="bi bi-cart-dash"></i>
                            </button>
                        </tr>`

        $(this).closest('tr').replaceWith(updated_row);

        
        $(`#_productDropDownEdit${editProductIdVal}`).html(options);
        $(`#_productDropDownEdit${editProductIdVal}`).selectpicker('val', editProductIdVal);


});


$(document).on("change", '._productUpdateImage', function(e)
{
    e.preventDefault();

    let elementID = $(this).data("selectid");
    let imageSrc = $('._productUpdateImage option:selected').data('productimg')
    

    $(`#_image${elementID}`).attr("src", imageSrc);

}); 

$(document).on('click', "#_closeAddProduct", function(e)
{
    closeAddProducts();
});

var productTable = [];

$(document).on("click", "._addProducts", async function(e)
{
    e.preventDefault();


    if (productTableDataName.length == 0) {return}


  await productTableDataName.forEach(product => {

      if(addedProducts.length === 0)
      {
        addedProducts.push({
                                product_image: product.product_image,
                                name :   product.name,
                                product_id:  product.product_id,
                                quantity: Number(product.quantity),
                                subtotal:  product.price,
                                total: product.price,
                                sku:  product.sku,
                                taxclass:  product.taxclass,
                          });


            product_lines.push({
                            product_id:  product.product_id,
                            quantity: Number(product.quantity),
                            subtotal: Number(product.price)
                     });
      }
      else
      {

        let product_array_index =  addedProducts.find(prod => prod.product_id == product.product_id);
        let product_data_index = product_lines.find(prod => prod.product_id == product.product_id);

        if(product_array_index && product_data_index)
        {
            let change_value_product =  addedProducts.findIndex(prod => prod.product_id == product.product_id);
            let change_value_product_lines = product_lines.findIndex(prod => prod.product_id == product.product_id);

            addedProducts[change_value_product].quantity += Number(product.quantity);
            addedProducts[change_value_product].subtotal += Number(product.price);
            addedProducts[change_value_product].total = Number(addedProducts[change_value_product].subtotal) > Number(addedProducts[change_value_product].total) ? 
                                                        Number(addedProducts[change_value_product].total) * Number(addedProducts[change_value_product].quantity) :
                                                        Number(addedProducts[change_value_product].subtotaltotal);

            let product_lines_index = product_lines.find(prod => prod.product_id == product.product_id);
            product_lines_index.quantity += Number(product.quantity);
            product_lines_index.subtotal += Number(product.price);

        }
        else
        {
            addedProducts.push({
                product_image: product.product_image,
                name :   product.name,
                product_id:  product.product_id,
                quantity: Number(product.quantity),
                subtotal:  product.price,
                total: product.price,
                sku:  product.sku,
                taxclass:  product.taxclass,
          });


            product_lines.push({
                        product_id:  product.product_id,
                        quantity: Number(product.quantity),
                        subtotal: Number(product.price)
                });
                   
        }


      }

   console.log(addedProducts);

    });


    $('#_addedProductsData').empty();

    // addedProducts = addedProducts.concat(productTableDataName);

    // console.log(productTableDataName);
    
     
    console.log(product_lines );
    let voucher_value = 0;
    addedProducts.forEach( async product => 
        {   
                    let subtotal = Number(product.subtotal);
                    let total = Number(product.total);

                    $("#_addedProductsData").append(
                
                
                        `<tr class="m-2" id="tr-${product.product_id}" data-rowid="${product.product_id}">
                            <th scope="row" hidden>1</th>
                            <th scope="row" class="pt-4 pb-4">
                                <div class="_productColumn">
                                    <div class="_productColumnImg">
                                        <img 
                                            src="${product.product_image}" 
                                            class="img-fluid"
                                            alt=""
                                        >
                                    </div>
                    
                                    <div class="_productColumnText">
                                        <p>${product.name}</p>
                                        <p>SKU: <span>${product.sku}</span></p>
                                    </div>
                                </div>
                            </th>
                            <td>$<span class="_productPrice-${product.product_id}">${subtotal.toFixed(2)}</span></td>
                            <td class="text-center">
                                <div class="_cellContainer">
                                    <div class="_productTableQuantity">
                                        <span>x</span>
                                        <span id="_editProductQuantity-${product.product_id}">${product.quantity}</span>
                                        <br>
                                    </div>
                                    <small hidden class="_defaultValues _defaultQuantity-${product.product_id} _defaultValue-${product.product_id}">Old Quantity</small>
                                </div>
                            </td>
                            <td  class="text-center">
                                <div class="_cellContainer ">
                                    <div class="_productTableTotal _cellContainer-${product.product_id}">
                                        <span>$</span>
                                        <span id ="_editProductTotal-${product.product_id}">${Number(total).toFixed(2)}</span> 
                                    </div>
                                    <small hidden class="_defaultValues _defaultTotal-${product.product_id} _defaultValue-${product.product_id}"></small>
                                    <small hidden class="_discountTotal _discountTotal-${product.product_id}">
                                        <span>$<span class="_discountValue-${product.product_id}"></span> Discount</span>
                                    </small>
                                    </div>
                            </td>
                            
                            <div id="tax_cells_${product.product_id}">
                            ${await addTaxCell(product.subtotal, product.product_id) }
                            </div>                            
                            <td id="_productAction-${product.product_id}">
                                <div  class="_productActionButtons">
                                    <span class="_editProductRow _editProductRow-${product.product_id}"><i class="bi bi-pencil"></i></span>
                                    <span hidden class="_saveProductRow _saveProductRow-${product.product_id}"><i class="bi bi-check"></i></span>
                                    <span class="_deleteProductRow _deleteProductRow-${product.product_id}"><i class="bi bi-cart-dash"></i></span>
                                    <span hidden class="_cancelEditProductRow _cancelEditProductRow-${product.product_id}"> <i class="bi bi-x"></i> </span>
                                </div>
                            </td>
                        </tr>`
                
                    );


                    if( subtotal > total )
            
                    {
                        
                        let discount_value = Number(subtotal) -  Number(total);
                        $(`._cellContainer-${product.product_id}`).css('margin-top', '2.7rem');
                        $(`._discountValue-${product.product_id}`).text(discount_value.toFixed(2));
                        $(`._discountTotal-${product.product_id}`).attr('hidden', false);
                        voucher_value = Number(voucher_value) + Number(discount_value);
                        console.log(voucher_value, subtotal, total, discount_value);
                        
                        $('#_voucherValue').text(Number(voucher_value).toFixed(2));
                        $('#_voucherRow').attr('hidden', false);
            
                       
            
                    }
        });

        closeAddProducts();
    
    });

    

    var currTaxRate;
    $(document).on('click', '._taxRadioButton', async function(e)
    {
        let taxData = $(this).data("taxdata");
        let taxID = $(this).data('inputid');
        
        currTaxRate = $(this).val();
        $(this).attr("disabled", "true");
        tax_rate_value.push(taxData);

        tax_rate_is_active = true;


        $(`<th class="_taxHeaders" data-taxheaderid="${taxID}"><div>${taxData.name}<span><i class="bi bi-x-circle _taxRemoveButton" data-taxremoveid="${taxID}" style="font-size: 14px !important"></i></div></span></th>`).insertBefore("#_actionButtonHeader");
        
        $(`<tr class="_addedTaxSubtotal _taxtSubtotal" data-taxtotalid="${taxID}">
            <td>${taxData.name}:</td>
            <th><span id="_itemTax-${taxID}">$0.00</span></th>
           </tr>`).appendTo('._addedTaxesTotal');


        addedProducts.forEach(async product => {
            
            
            const addedCell = await addTaxCell(product.subtotal, product.product_id);
   

            // $(addedCell).appendTo(`#tr-${product.product_id}`);
            $(addedCell).insertBefore(`#_productAction-${product.product_id}`);
        });

        $(this).attr("hidden", true);
        $(`._tax-id-${taxID}`).attr("hidden", false);


        if(tax_lines.find(tax => tax.rate_id == taxData.id))
        {
            return;
        }

        tax_lines.push(
            {
                rate_id: taxData.id,
                rate_percent: Number(taxData.rate).toFixed(2) / 100 
            }
        )

        let taxPercentValue = Number(taxData.rate).toFixed(2) / 100 ;

        if(shipping_lines.length > 0 )
        {
            
            
            $("#_addedShippingData > tr").each(function (index, element) {
                const shippingTotal = $(this).find(`#_shippingTotal-${index}`).val();
                const shippingIndex = $(this).find(`#_shippingTotal-${index}`).data('inputindex');
                get_taxes_id(shippingIndex);
                $(`<td class="_shippingTaxTotal" data-shippingtaxid = "${taxID}">$<span class="_shippingSubtotalTax-${shippingIndex}-${taxID}">${shippingTotal * taxPercentValue}</span></td>`).insertBefore(`#_shippingAction-${shippingIndex}`);
              });
        }

  
        // console.log("After Adding: ", tax_lines, tax_rate_value, productTableDataName);


    });

    $(document).on("click", '._taxRemoveButton', function(e)
    {

        let tax_id = $(this).data("taxremoveid");
     

        $(`._tax-id-${tax_id}`).attr("hidden", true);
        $(`#tax-${tax_id}`).attr("hidden", false).prop("checked",false).attr("disabled", false);
        $(`th[data-taxheaderid =${tax_id}]`).remove();
        $(`._addedTax[data-taxcellid =${tax_id}]`).remove();
        $(`._addedTaxSubtotal[data-taxtotalid =${tax_id}]`).remove();
        $(`td[data-shippingtaxid = ${tax_id}]`).remove()
        



        const index = tax_lines.map(x => {
            return x.rate_id;
          }).indexOf(tax_id);
         
        const tax_index = tax_rate_value.map( x => {
            return x.rate_id;
          }).indexOf(tax_id) ;

          tax_lines.splice(index, 1);
          tax_rate_value.splice(tax_index, 1);

          $('._addedTax').remove();

        productTableData.forEach(async product => {
            
            
            const addedCell = await addTaxCell(product.subtotal, product.product_id);

            // $(addedCell).appendTo(`#tr-${product.product_id}`);
            $(addedCell).insertBefore(`#_productAction-${product.product_id}`)
            
        });

        //   console.log("After Removed: ", tax_lines, tax_rate_value, productTableDataName);

    });

    $(document).on("click", "._removeTaxes", function (e)
    {
        e.preventDefault();
        tax_rate_value = [];
        tax_lines = [];
        $('._taxHeaders').remove();
        $('._addedTax').remove();
        $('._addedTaxSubtotal').remove();
        $('._taxRadioButton').attr("hidden", false).prop("checked",false).attr("disabled", false)
        $('._taxRemoveButton').attr("hidden", true);
        $('._addedTaxTotal').remove();
        $('._shippingTaxTotal').remove();
        
    });



    $(document).on('click', "._addShippingTrigger", function (e)
    {   
        $('._shippingRow').attr('hidden', false);

        shipping_lines.push({
                                method_title    :   "",
                                method_id       :   "",
                                total           :   ""
                            });
                            
   

        
        
        const _shippingIndex = shipping_lines.indexOf(shipping_lines[shipping_lines.length -1]);

        // ADD TEMP OBJECT ID
        shipping_lines[_shippingIndex].shipping_id = _shippingIndex; 

        $(`<tr data-shippingindex="${_shippingIndex}">
            <th scope="row" class="w-75 pt-4 pb-4">
                <div class="_shippingColumn">
                    <div class="_shippingIcon">
                        <i class="bi bi-truck"></i>   
                    </div>

               
                        <div class="_shippingTitleType">
                            <input class="form-control _shippingTitleInput" data-titleindex = "${_shippingIndex}" id="_shippingTitle-${_shippingIndex}" value="Shipping"/>
                            <select class="form-control _shippingTypeInput" data-typeindex = "${_shippingIndex}" id="_shippingType-${_shippingIndex}">
                                <option value="n/a">N/A</option>
                                <option value="flat_rate">Flat Rate</option>
                                <option value="free_shipping">Free Shipping</option>
                                <option value="local_pickup">Local Pickup</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                  
                </div>
            </th>
            <td colspan="3" class="text-end"><input data-shippingtaxid="" min="1" data-inputindex=${_shippingIndex} class="form-control _shippingValue _numberInput" type="number" value="1" id="_shippingTotal-${_shippingIndex}"/></td>
            <td id="_shippingAction-${_shippingIndex}">
                <div  class="_shippingActionButtons">
                    <span></span>
                    <span class="_deleteShippingRow"><i class="bi bi-cart-dash"></i></span>
                </div>
            </td>
            </tr>`).appendTo('#_addedShippingData');

          if(tax_lines.length > 0 )
          {
               tax_lines.forEach(lines => {
                console.log(lines); 
                      $(`<td class="_shippingTaxTotal" data-shippingtaxid = "${lines.rate_id}">$<span class="_shippingSubtotalTax-${_shippingIndex}-${lines.rate_id}">${Number($(`#_shippingTotal-${_shippingIndex}`).val()) * lines.rate_percent}</span></td>`).insertBefore(`#_shippingAction-${_shippingIndex}`);
                  });
             
          }

          
        $(`<tr class="_addedShippingTotals _addedShippingSubtotal" data-shippingtotalid="${_shippingIndex}">
            <td id="_shippingTitleText-${_shippingIndex}">${$(`#_shippingTitle-${_shippingIndex}`).val()}:</td>
            <th>$<span id="_itemShipping-${_shippingIndex}">${Number($(`#_shippingTotal-${_shippingIndex}`).val()).toFixed(2)}</span></th>
            </tr>`).appendTo('._addedShippingTotal');

        get_taxes_id(_shippingIndex);
    });


    function get_taxes_id(shipping_index)
    {
        if(tax_lines.length == 0){return}
        const tax_array = []
        tax_lines.forEach(async tax => {
            await tax_array.push(tax.rate_id);
        });
  
        $(`#_shippingTotal-${shipping_index}`).attr('data-shippingtaxid', `[${tax_array}]`);

    }


    $(document).on('keypress', '._numberInput', function(e)
    {
        var charCode = e.which ? e.which : e.keyCode;
        // Only Numbers 0-9
        if (charCode < 48 || charCode > 57) {
            e.preventDefault();
            return false;
        } 
    });


    $(document).on('change', '._shippingTypeInput', function(e)
    {
        e.preventDefault();

        const shippingType = $(this).val();
        const shippingIndex = $(this).data('typeindex');
        shipping_lines[shippingIndex].method_id = shippingType;
        console.log(shipping_lines);


    });

    $(document).on('keyup', '._shippingTitleInput', function(e)
    {
        e.preventDefault();
        const input_value = $(this).val();
        const shipping_index = $(this).data('titleindex');
        // const shipping_old_value = $(`_itemShipping-${shipping_index}`).val();
        shipping_lines[shipping_index].method_title = input_value;

        $(`#_shippingTitleText-${shipping_index}`).text(`${input_value}:`);


    });


    $(document).on('keyup', '._shippingValue', function(e)
    {
        
        const input_index = $(this).data('inputindex');
        let input_value = $(this).val();
        
        if(tax_lines.length != 0)
        {
            const input_tax_array = $(this).data('shippingtaxid');
            input_value = input_value == 0 ? 1 : input_value;
            input_tax_array.forEach(tax => {
                const tax_rate = tax_lines.find(rate => rate.rate_id  === tax);
                const tax_rate_new_value = tax_rate.rate_percent * input_value;
                
                $(`._shippingSubtotalTax-${input_index}-${tax_rate.rate_id}`).text(tax_rate_new_value.toFixed(2));

            });

        }

        shipping_lines[input_index].total = input_value;

        $(`#_itemShipping-${input_index}`).text(Number(input_value).toFixed(2))



        

    });


   


  async function addTaxCell(price, product_id)
    {

        let taxcellIncluded = [];
       
        let totalWithTax;
        
     if(tax_rate_value.length == 0) { return }


        
     await tax_rate_value.forEach(tax_cell => {
        $('._addedTax').remove();
        let taxAddedCell = "";
            
            totalWithTax = price * (Number(tax_cell.rate/ 100));
            taxAddedCell = `<td class="_addedTax" data-productid = "${product_id}" data-taxcellid="${tax_cell.id}"> $${totalWithTax.toFixed(2)} </td>`;
          
            if(!taxcellIncluded.find(a =>a.includes(addTaxCell)))
            {
                taxcellIncluded.push(taxAddedCell);
            }

            if(productTableData.length != 0)
            {
    
            
                productTableData.forEach(product => {
                
                product[tax_cell.name] = Number(product.subtotal * currTaxRate).toFixed(2);
    
    
                });
    
                        
    
            }

           
            
           
        });

     return taxcellIncluded.join('');

    }


    function closeAddProducts()
    {
      
        $("#_addProductModal").modal("hide");
        productTableDataName = [];
        // $("._selectedProducts").empty();
    
    }

    
 $(document).on('click', "#_closeAddFee", function(e)
 {
         $("#_addFeeModal").modal("hide");
 });


})( jQuery );
