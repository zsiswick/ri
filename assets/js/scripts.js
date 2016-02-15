$(document).foundation();
$(document).ready(function() {

    var baseurl;
    var environ = window.location.host;
    
    if(environ.indexOf('localhost') > -1) {
      baseurl = window.location.protocol + "//" + window.location.host + "/" + "rubyinvoice/";
    } else {
      baseurl = window.location.protocol + "//" + window.location.host + "/";
    }
    var id = window.location.pathname.split('/').pop();
    var request;

    // PREVENT INVALID CHARACTERS ENTERED INTO TXT FIELDS
        $( "#invoiceCreate, #paymentModal" ).on( "keypress", "input.qty, input.sum, input.amt", function(e) {
          var chr = String.fromCharCode(e.which);
          if ("1234567890.".indexOf(chr) < 0) {
          	return false;
          }
        });

        $('#nav-container-mobile').click(function() {
        	$('#main-menu-mobile').toggle(function() {
        		$('#main-menu-mobile-toggle').toggleClass('open');
        	});
        });

        // DO THE HARLEM SHAKE
    		$('form').on('invalid.fndtn.abide', function() {
          var jThis=$(this);

          	jThis.addClass('shake');
          	jThis.bind('webkitAnimationEnd mozAnimationEnd oAnimationEnd msAnimationEnd animationEnd',function(e){
          		jThis.removeClass('shake');
          	});
          	return false;

        });

        function init_autonumeric() {
        	if ( $("#invoiceCreate").length == 1 ) {
        		$('.sum, .totalSum, #invoiceSubtotal, #taxOne, #taxTwo, #discount').autoNumeric('init', {aDec:'.', aSep:'', aForm: false});
        	}
        }

        function calculateTotal() {
        	var tax1 = $("#taxOne").autoNumeric('get');
        	var tax2 = $("#taxTwo").autoNumeric('get');
        	var subtotal = $("#invoiceSubtotal").autoNumeric('get');
        	var discount = $("#discount").autoNumeric('get');
        	var totalSum;
        	totalAmnt = (+tax1 + +tax2) + +subtotal;

        	if (discount > totalAmnt)
        	{
        		totalSum = 0;
        	}
        		else
        	{
        		totalSum = (+totalAmnt - +discount);
        	}

        	$("#invoiceTotal").autoNumeric('set', totalSum);
        }

        function calculateTaxTotal() {
          init_autonumeric();
        	var	tax1 = 0;
        	var tax2 = 0;
        	var amnt1 = 0;
        	var amnt2 = 0;
        	var percent1 = 0;
        	var percent2 = 0;
        	var sum1 = 0;
        	var sum2 = 0;

          if ( $(".tax").length ) {

            $(".tax option:selected").each(function()
            {
              if ( $(this).val() == $("#invoice_tax_1").val() )
              {
                tax1 = ($(this).val() / 100);
                qty = $(this).closest("div.tabbed").find('input.qty').val();
                price = $(this).closest("div.price").find('input.unitCost').val();
                amnt1 = (qty * price);
                percent1 = (amnt1 * tax1);
                sum1 += percent1;
              }
                else if ( $(this).val() == $("#invoice_tax_2").val() )
              {
                tax2 = ($(this).val() / 100);
                qty = $(this).closest("div.tabbed").find('input.qty').val();
                price = $(this).closest("div.price").find('input.unitCost').val();
                amnt2 = (qty * price);
                percent2 = (amnt2 * tax2);
                sum2 += percent2;
              }
              $("#taxOne").autoNumeric('set', sum1);
              $("#taxTwo").autoNumeric('set', sum2);
            });

          } else {
            $("#taxOne").autoNumeric('set', 0);
            $("#taxTwo").autoNumeric('set', 0);
          }
          calculateTotal();
        }

        function calculateSubTotal() {
            var sum = 0;
            var amnt = 0;
            //iterate through each textboxes and add the values
            $(".totalSum").each(function()
            {
              amt = parseFloat( $(this).autoNumeric('get') );
              sum += amt;
            });

            //.toFixed() method will roundoff the final sum to 2 decimal places
            $("#invoiceSubtotal").autoNumeric('set', sum ).effect("highlight", {}, 1250);
            calculateTaxTotal();
        }

        // CALCULATE THE ITEM ROWS ON EDIT INVOICE PAGE
       	$(document).on('focusout', ".sum", function(){

        	init_autonumeric();
        	var $this = $(this);
        	var qty = $this.parent().parent().parent().parent().find('input.qty').val();

        	var num = $this.parent().parent().parent().parent().find('.unitCost').autoNumeric('get');
        	var sumtotal = parseFloat((num * qty));

        	$this.parent().parent().parent().parent().find('.totalSum').autoNumeric('set', sumtotal );
        	calculateSubTotal();

        });

        $(document).on('change', ".tax, #invoice_tax_1, #invoice_tax_2, #invoiceDiscount", function(){
        	calculateTaxTotal();
        });

        $(document).on('click', ".delete-row", function() { // RECALCULATE WHEN ITEMS ARE REMOVED
        	$(this).parent().parent().parent().parent().parent().remove();
        	$val = $(this).parent().parent().parent().prev().children().children('input'[name="item_id[]"]).val();
        	// CREATE HIDDEN INPUT LIST OF RECORDS TO BE DELETED
        	$('.edit-list-container div.tabbed.list').append('<input type="hidden" name="delete_ids[]" value="'+$val+'"></input>');

        	calculateSubTotal();
        });

        if ( $("#invoiceCreate").length ) {
        	calculateTaxTotal();
        	calculateSubTotal();
        }

        function add_client_callback(cl_data) {

        	setTimeout(function() {
        	      location.reload();
        	}, 1000);

        }

        // AJAX FORMS
        function ajaxRequest($this, url, callback) {
        	// abort any pending request
          if (request) request.abort();
          // setup some local variables
          var $form = $this;
          // let's select and cache all the fields
          var $inputs = $form.find("input, select, button, textarea");
          // serialize the data in the form
          var serializedData = $form.serialize();

          // let's disable the inputs for the duration of the ajax request
          // Note: we disable elements AFTER the form data has been serialized.
          // Disabled form elements will not be serialized.
          $inputs.prop("disabled", true);

          $('#loadingImg').show();

          // fire off the request
          request = $.ajax({
              url: baseurl+url,
              type: "POST",
              data: serializedData,
              dataType: 'json',
              cache:false,
              success: function(respond) {

                if (respond.result == 'false') {

                	var keys = Object.keys(respond);

                	$('#form-errors').html(respond.errors).addClass("").show();

                } else {

                 $('#form-errors').html(respond.errors).addClass("").show();

                 if(typeof respond.redirect != "undefined") window.location.href = respond.redirect;

                 if(typeof respond.records != "undefined") callback(respond.records);

                }
              }
          });
          // callback handler that will be called regardless
          // if the request failed or succeeded
          request.always(function () {
              // reenable the inputs
              $inputs.prop("disabled", false);
              $('#loadingImg').hide();
          });
          // prevent default posting of form
          event.preventDefault();
        }

        $( "#form-wrap" ).on( "submit", "#addClient", function(event) {
          var id = window.location.pathname.split('/').pop();
          $this = $(this);
          ajaxRequest($this, 'index.php/clients/create', add_client_callback);
        });

        // GET VARIOUS MODAL WINDOW CONTENT

        // GENERIC CONTENT LOADER FUNCTION
        function load_content(url) {
        	$("#form-wrap").hide();
        	$('#loadingImg').show();
        	var id = window.location.pathname.split('/').pop();

        	$.get( url, function( data ) {
        	  $("#form-wrap").html( data );
        	  $("#form-errors").hide();
        	}).always(function() {
            $('#loadingImg').hide();
            $('#form-wrap').show();
        	});
        }

        $("#addPaymentBtn").on("click", function() {
        	load_content(baseurl+"index.php/invoices/view_payments/"+id);
        });

        $('[name="client"]').on("change", function() {
        	if ( $(this).val() == "add_new_client") {
        		$('#revealModal').foundation('reveal', 'open');
        		load_content(baseurl+"index.php/clients/create_ajax");
        	}
        });

        $("#sendInvoiceBtn").on("click", function() {
        	load_content(baseurl+"index.php/invoices/view_invoice_email/"+id+"/0");
        });

        $("#sendInvoiceRemindBtn").on("click", function() {
        	load_content(baseurl+"index.php/invoices/view_invoice_email/"+id+"/1");
        });

        $("#sendInvoiceThanks").on("click", function() {
        	load_content(baseurl+"index.php/invoices/view_invoice_email/"+id+"/2");
        });

        // END

        $("#auto_reminder").on("click", function() {
        	var id = window.location.pathname.split('/').pop();
        	var checked = 0;
        	$this = $(this);

        	$("#form-wrap").hide();
        	$('#loadingImg').show();

        	if ($('#auto_reminder').is(':checked')) {
        		checked = 1;
        	}

        	$.ajax({
        	    type: 'POST',
        	    url: baseurl+"index.php/invoices/set_auto_reminder/"+id+"/"+checked,
        	    success: function(msg) {

        	    	$('#loadingImg').hide();
        	    	$('#form-wrap').show();
        	    	$('#paymentModal').foundation('reveal', 'open');
        	    		if (checked == 1) {
        	    			$("#form-wrap").html( '<div class="row"><div class="small-12 columns text-center"><h2>Auto Reminder</h2><p>Auto-Reminder has been set to remind every '+$("#inv_due").val()+' days</p></div></div><a class="close-reveal-modal">&#215;</a>' );
        	    		} else {
        	    			$("#form-wrap").html( '<div class="row"><div class="small-12 columns text-center"><h2>Auto Reminder</h2><p>Auto-Reminder is off</p></div></div><a class="close-reveal-modal">&#215;</a>' );
        	    		}

        	    }
        	  });
        });

        // ADD FAVORITE INVOICE ITEM

        $( ".edit-list-container" ).on( "click", ".add-favorite", function(event) {
          var postData = {
            "qty" : $(this).closest('div.qty').find('input.qty').val(),
            "unit" : $(this).closest('div.qty').find('select.unit').val(),
            "description" : $(this).closest('div.tabbed').find('textarea').val(),
            "unit_cost" : $(this).closest('div.tabbed').find('input.unitCost').val()
          };

          $.ajax({
              type: "POST",
              url: baseurl+"index.php/invoices/add_favorite_invoice_item",
              data: postData,
              success: function(){
                  $("#form-wrap").html('<div class="row"><div class="small-12 columns text-center"><h2 class="light-bg">Item</h2><p>'+postData.description+'</p><hr/><p><i class="fi-check large"></i> Added to saved items</p></div></div><a class="close-reveal-modal">&#215;</a>' );
                  $('#revealModal').foundation('reveal', 'open');
              }
          });
        });

        $("#cancelDeleteBtn").on("click", function() {
        	$('#editModal').foundation('reveal', 'close');
        });

        $("#enable_payments").on("click", function() {

        		if ( $('#enable_payments:checked').length >0 ) {
        			$("#payment_settings").slideDown("fast");
        		} else {
        			$("#payment_settings").slideUp("fast");
        		}
        });

        if ( $('#enable_payments:checked').length >0 ) {
        	$("#payment_settings").show();
        } else {
        	$("#payment_settings").hide();
        }

});
