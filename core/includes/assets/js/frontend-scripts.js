

// IMPORTANT NOTES:
// - Before update an order first you must remove the "shipping_id" property from all of the object of "shipping_lines" array


function IsNumeric(value){
    switch(value){
    case 38: //Up Arrow
        return false;
    case 40: //Down Arrow
        return false;
    case 109: //NumpadSubtract
        return false;
    case 110: //Decimal
        return false;
    case 189: //Subtract
        return false;
    }
    
    }

(function( $ ) {

    $(document).on('click', '._deleteProductRow', function(e)
    {
       const product_row_id = $(this).closest('tr').data('rowid');
       let product_array_index = addedProducts.findIndex(x => x.product_id == product_row_id);
       let product_data_index = product_lines.findIndex(x=>{x.product_id == product_row_id});


       addedProducts.splice(product_array_index, 1);
       product_lines.splice(product_array_index, 1);
       


     


      $(this).closest('tr').remove();
  
    });


    $(document).on('click', '._deleteShippingRow', function(e)
    {
       const shipping_row_id = $(this).closest('tr').data('shippingindex');
       let shipping_index = shipping_lines.findIndex(x => x.shipping_id == shipping_row_id);
 
       shipping_lines.splice(shipping_index, 1);


       $(`._addedShippingSubtotal[data-shippingtotalid = "${shipping_row_id}"]`).remove();

      $(this).closest('tr').remove();
  
    });

    

    $(document).on('click', '._editProductRow', function(e)
    {


       const product_row_id = $(this).closest('tr').data('rowid');
       const current_quantity = $(`#_editProductQuantity-${product_row_id}`).text();
       const default_quantity = current_quantity;
       const current_total = $(`#_editProductTotal-${product_row_id}`).text();
       const default_total = current_total;


       
        $(`._defaultQuantity-${product_row_id}`).text(`${default_quantity}`);
        $(`._defaultTotal-${product_row_id}`).text(`${default_total}`);


        $(`._editProductRow-${product_row_id}`).attr("hidden", true);
        $(`._deleteProductRow-${product_row_id}`).attr('hidden', true);
        $(`._saveProductRow-${product_row_id}`).attr("hidden", false);
        $(`._cancelEditProductRow-${product_row_id}`).attr("hidden", false);
        $(`._defaultValue-${product_row_id}`).attr('hidden', false);


       $(`#_editProductQuantity-${product_row_id}`).replaceWith(`<input type="number" min = "1" id="_editProductQuantity-${product_row_id}" class="text-end _productQuantity _numberInput" value="${current_quantity}"/>`);
       $(`#_editProductTotal-${product_row_id}`).replaceWith(`<input style="width: 6rem;" step="0.01" type="number" min = "1" id="_editProductTotal-${product_row_id}" class="text-end _productQuantity _numberInput" value="${current_total}"/>`);
     

    });


    $(document).on('keypess', '._productQuantity', function(e)
    {
        let charCode = e.which ? e.which : e.keyCode;
        // Only Numbers 0-9
        if (charCode < 49 || charCode > 57) {
            e.preventDefault();
            return false;
        } 
    });


    $(document).on('keyup', '._productQuantity', function(e)
    {

    
       const product_row_id = $(this).closest('tr').data('rowid');
       const product_row_price = $(`._productPrice-${product_row_id}`).text();
       let   input_value = Number($(this).val());
    
       input_value = input_value == 0 || input_value == '' ?  1 : input_value;
    
    
          const new_value = Number(product_row_price) * input_value;
         
       $(`#_editProductTotal-${product_row_id}`).text(`${new_value.toFixed(2)}`);

    //    $(this).val(input_value);

    });


    $(document).on('click', '._saveProductRow', function(e)
    {
        const product_row_id = $(this).closest('tr').data('rowid');
        
        
    

        let current_value = Number($(`#_editProductQuantity-${product_row_id}`).val());
        let default_total = $(`._defaultTotal-${product_row_id}`).text();
        let current_total = $(`#_editProductTotal-${product_row_id}`).val();

        current_value = Number(current_value) != 0 ? Number(current_value) : 1;

        let product_array_index =addedProducts.find(prod => prod.product_id == product_row_id);
        let product_data_index = product_lines.find(prod => prod.product_id == product_row_id);

        product_array_index.quantity = current_value;
        product_data_index.quantity = current_value;


        $(`._defaultValue-${product_row_id}`).attr('hidden', true);
        $(`._editProductRow-${product_row_id}`).attr("hidden", false);
        $(`._saveProductRow-${product_row_id}`).attr("hidden", true);
        $(`._deleteProductRow-${product_row_id}`).attr('hidden', false);
        $(`._cancelEditProductRow-${product_row_id}`).attr("hidden", true);

        $(`#_editProductQuantity-${product_row_id}`).replaceWith(`<span id="_editProductQuantity-${product_row_id}">${current_value}</span>`);
        $(`#_editProductTotal-${product_row_id}`).replaceWith(`<span id="_editProductTotal-${product_row_id}">${(current_value * current_total).toFixed(2)}</span>`);
        // $(`#_editProductTotal`).text((current_value * current_total).toFixed(2));
        
        if(current_total < default_total)
        {
            let voucher_value = Number($('#_voucherValue').text());
            let discount_value = Number(default_total - current_total).toFixed(2);
            $(`._cellContainer-${product_row_id}`).css('margin-top', '2.7rem');
            $(`._discountValue-${product_row_id}`).text(discount_value);
            $(`._discountTotal-${product_row_id}`).attr('hidden', false);

            voucher_value = Number(voucher_value) + Number(discount_value);
            console.log(Number(voucher_value), discount_value, 'hello');
            $('#_voucherValue').text(Number(voucher_value).toFixed(2));
            $('#_voucherRow').attr('hidden', false);

            product_array_index.total = current_total;
            product_data_index.total = current_total;
            
        }

        

    });

       $(document).on('click', '._cancelEditProductRow', function(e)
    {

        e.preventDefault();

        const product_row_id = $(this).closest('tr').data('rowid');
        let current_value = Number($(`#_editProductQuantity-${product_row_id}`).val());
        let default_total = Number($(`._defaultTotal-${product_row_id}`).text());
        let default_quantity = Number($(`._defaultQuantity-${product_row_id}`).text());

        $(`._editProductRow-${product_row_id}`).attr("hidden", false);
        $(`._saveProductRow-${product_row_id}`).attr("hidden", true);
        $(`._deleteProductRow-${product_row_id}`).attr('hidden', false);
        $(`._cancelEditProductRow-${product_row_id}`).attr("hidden", true);
        $(`._defaultValue-${product_row_id}`).attr('hidden', true);

        $(`#_editProductQuantity-${product_row_id}`).replaceWith(`<span id="_editProductQuantity-${product_row_id}">${default_quantity}</span>`);
        $(`#_editProductTotal-${product_row_id}`).replaceWith(`<span id="_editProductTotal-${product_row_id}">${default_total.toFixed(2)}</span>`);



    });


    $(document).on('click', '._addFeeTrigger', function(e){

		$('._addFeeRow' && '._addedFee').attr('hidden', false);

        let addFeeAmount = $('#_addFeeAmountInput').val();

        // $('#_itemFee').text(`${Number(addFeeAmount).toFixed(2)}`);
        let addedFeeAmount = Number($('#_itemFee').text()).toFixed(2);

        let feeTitle = `$${Number(addFeeAmount).toFixed(2)} Fee`
        
        fee_lines.push({
            name    :   feeTitle,
            total       :   ""
        });
        


        const _feeIndex = fee_lines.indexOf(fee_lines[fee_lines.length -1]);

        fee_lines[_feeIndex].fee_id = _feeIndex; 
        $("#_addFeeModal").modal("hide");
        $(`<tr data-feeindex="${_feeIndex}">
        <th scope="row" class="w-75 pt-4 pb-4">
            <div class="_shippingColumn">
                <div class="_shippingIcon">
                    <i class="bi bi-coin"></i>   
                </div>
                <div class="_shippingTitleType">
                    <input class="form-control _readOnlyInput _feeTitleInput" data-titleindex = "" id="_fee-${_feeIndex}" value="${feeTitle}" readonly>
                </div>
            </div>
        </th>
        <td colspan="3" class="text-end">
            <input class="_readOnlyInput" type="number" id="_feeTotal-${_feeIndex}" value="${addFeeAmount}" readonly/> 
            <small hidden class="_discountTotal _discountTotal-${_feeIndex}">
                <span><span class="_FeeDefaultValue-${_feeIndex}"></span> </span>
            </small>
        </td>
        <td id="_feeAction-${_feeIndex}">
            <div  class="_productActionButtons">
                <span class=" _saveButton _saveButton${_feeIndex}" id="_saveFeeProduct" data-feeid="${_feeIndex}" hidden>
                    <i class="bi bi-check-lg"></i>
                </span>
                <span class=" _editButton _editButton${_feeIndex}" id="_editFee" data-feeid="${_feeIndex}">
                    <i class="bi bi-pencil"></i>
                </span>
                <span class="_deleteFeeRow _deleteFeeRow-${_feeIndex}">
                    <i class="bi bi-cart-dash"></i>
                </span>
                <span hidden class="_cancelEditFeeRow _cancelEditFeeRow-${_feeIndex}" data-feeid="${_feeIndex}" id="_cancelEditFeeRow"> 
                    <i class="bi bi-x"></i> 
                </span>
            </div>
        </td>
        </tr>`).appendTo('#_addedFeeData');
        addedFeeAmount = Number(addedFeeAmount) + Number(addFeeAmount);
        $('#_itemFee').text(Number(addedFeeAmount).toFixed(2));
        $('#_addFeeAmountInput').val("");
    }); 

    $(document).on('click', '._deleteFeeRow', function(e)
    {
       const fee_row_id = $(this).closest('tr').data('feeindex');
       let fee_index = fee_lines.findIndex(x => x.fee_id == fee_row_id);
       let feeValue = Number($(`#_feeTotal-${fee_index}`).val());
       let feeTotal = Number($(`#_itemFee`).text());
       fee_lines.splice(fee_index, 1);
      feeTotal = feeTotal - feeValue;
      $(this).closest('tr').remove();
      $(`#_itemFee`).text(Number(feeTotal).toFixed(2));
      
    });

    //Edit Fee(s)
    $(document).on("click", "#_editFee", function(e) { 
        e.preventDefault();
    
        let elementID = $(this).data('feeid');
        
        let currentFeeValue = $(`#_feeTotal-${elementID}`).val();

        $(this).attr('hidden', true);

        $(`._saveButton${elementID}`).attr('hidden', false);
    
        $(`#_feeTotal-${elementID}`).attr('readonly', false);
        
        $(`._deleteFeeRow-${elementID}`).attr('hidden', true);
        
        $(`._cancelEditFeeRow-${elementID}`).attr('hidden', false);

        $(`._FeeDefaultValue-${elementID}`).text(currentFeeValue);
        

    
    });
    //
    $(document).on("click", "#_saveFeeProduct", async function(e)
{

    e.preventDefault();

    let elementID = $(this).data('feeid');
    /* let editNameVal =  $(`#_productDropDownEdit${elementID} option:selected`).text(); */
    let editQuantVal = $(`#_feeTotal-${elementID}`).val();
 


    if(fee_lines.find(fee => fee.fee_id == elementID))
    {

        let feeDataIndex = fee_lines.find(fee => fee.fee_id == elementID);
        feeDataIndex.total = Number(editQuantVal);
  
    }


    $(this).attr('hidden', true);

    $(`._editButton${elementID}`).attr('hidden', false);

    $(`.name-${elementID}`).attr('disabled', true).selectpicker('refresh');

    $(`#_feeTotal-${elementID}`).attr('readonly', true);

    $(`#_fee-${elementID}`).val(`$ ${Number(editQuantVal).toFixed(2)} Fee`);

    $(`._deleteFeeRow-${elementID}`).attr('hidden', false);

    $(`._cancelEditFeeRow-${elementID}`).attr('hidden', true);
    
    
    let feeSum = 0;

    await fee_lines.forEach(fee => {
        feeSum += fee.total;
    });

    $(`#_itemFee`).text(feeSum.toFixed(2));

});

    $(document).on("click", "#_cancelEditFeeRow", function(e) { 

    

    let elementID = $(this).data('feeid');
    
    let currentFeeValue = $(`._FeeDefaultValue-${elementID}`).text();


    $(`#_feeTotal-${elementID}`).val(currentFeeValue);

    $(`._editButton${elementID}`).attr('hidden', false);

    $(`.name-${elementID}`).attr('disabled', true).selectpicker('refresh');

    $(`#_feeTotal-${elementID}`).attr('readonly', true);

    $(`._deleteFeeRow-${elementID}`).attr('hidden', false);

    $(`._cancelEditFeeRow-${elementID}`).attr('hidden', true);

    $(`._saveButton${elementID}`).attr('hidden', true);
    

    });
	
	
	
	$(document).on("click", "#_svsmenu-all", function(e) { 
		$('#_svsmenutext').text("This status includes all orders that include only SVS products and which are in a Processing state in the Vet Post Website.");
    });
	
	$(document).on("click", "#_svsmenu-onhold", function(e) { 
		$('#_svsmenutext').text("");
    });
	
	$(document).on("click", "#_svsmenu-readytosend", function(e) { 
		$('#_svsmenutext').text("These are orders to be sent to SVS. They are cleared every 10 minutes.");
    });
	
	$(document).on("click", "#_svsmenu-partial", function(e) { 
		$('#_svsmenutext').text("");
    });
	
	$(document).on("click", "#_svsmenu-pending", function(e) { 
		$('#_svsmenutext').text("");
    });
	
	$(document).on("click", "#_svsmenu-processing", function(e) { 
		$('#_svsmenutext').text("All orders which have a status of processing. Only orders which are eligible for sending have the action icon next to it.");
    });
	
	$(document).on("click", "#_svsmenu-completed", function(e) { 
		$('#_svsmenutext').text("All SVS orders that are completed will appear back in the completed status.");
    });
	
	$(document).on("click", "#_svsmenu-refunded", function(e) { 
		$('#_svsmenutext').text("");
    });
	
	$(document).on("click", "#_svsmenu-svscandidates", function(e) { 
		$('#_svsmenutext').text("");
    });
	
	$(document).on("click", "#_svsmenu-svsprocessing", function(e) { 
		$('#_svsmenutext').text("These are orders are sent to SVS, we are now waiting for tracking details, we check for new tracking details every 60 minutes.");
    });
	
	$(document).on("click", "#_svsmenu-removed", function(e) { 
		$('#_svsmenutext').text("");
    });
	
	$(document).on("click", "#_svsmenu-errors", function(e) { 
		$('#_svsmenutext').text("This status means an internal error (e.g. WC or SVS API).");
    });
	

})( jQuery );
