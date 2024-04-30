/**
	 * Block a node visually for processing.
	 *
	 * @param {JQuery Object} $node
	 */
var block = function ( $node ) {
    if ( ! is_blocked( $node ) ) {
        $node.addClass( 'processing' ).block( {
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6,
            },
        } );
    }
};

	/**
	 * Check if a node is blocked for processing.
	 *
	 * @param {JQuery Object} $node
	 * @return {bool} True if the DOM Element is UI Blocked, false if not.
	 */
	var is_blocked = function ( $node ) {
		return (
			$node.is( '.processing' ) || $node.parents( '.processing' ).length
		);
	};
	

/**
 * Unblock a node after processing is complete.
 *
 * @param {JQuery Object} $node
 */
var unblock = function ( $node ) {
    $node.removeClass( 'processing' ).unblock();
};



function freesample($this,color_id,fabric_id,pricing_grp_id,fabricname,fabric_img_url){
    block(jQuery($this));
    formData ={ action:'add_freesample',
    color_id:color_id,
    fabric_id:fabric_id,
    pricing_grp_id:pricing_grp_id,
    fabricname:fabricname,
    product_title: jQuery('#product_title').val(),
    fabric_img_url:fabric_img_url
 };
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: v4_ajax_object.ajax_url,
        data:formData,
        success: function(html){
            unblock(jQuery($this));
            jQuery($this).find('span').html('');
            jQuery($this).find('span').append(html['value']);
        }
    });
}

// fabric and color listing 

function fabriclist(pei_productid){
    block(jQuery('.listpage'));
    formData ={ action:'fabriclist',
    pei_productid:pei_productid,
    feildscategoryid: jQuery('#feildscategoryid').val(),
    productslug: jQuery('#productslug').val(),
    ecomFreeSample: jQuery('#ecomFreeSample').val(),
    page_no:1,
    feildscategoryname: jQuery('#feildscategoryname').val()
 };
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: v4_ajax_object.ajax_url,
        data:formData,
        success: function(html){
            unblock(jQuery('.listpage'));
            jQuery('#row-product-list').html('');
            jQuery('#row-product-list').append(html['html']);
            jQuery('.nav-pagination').html('');
            jQuery('.pagination_div').append(html['nav']);
            jQuery('.woocommerce-result-count').html('');
            jQuery('.woocommerce-result-count').append(html['total_fabric_color']);
        }
    });
}

//fabric and color listing page pagination

function pagination(page){
    block(jQuery('.listpage'));
    formData ={ action:'fabriclist',
        pei_productid:jQuery('#pei_productid').val(),
        feildscategoryid:jQuery('#feildscategoryid').val(),
        productslug:jQuery('#productslug').val(),
        ecomFreeSample:jQuery('#ecomFreeSample').val(),
        page:page,
        feildscategoryname:jQuery('#feildscategoryname').val()
    };
 jQuery.ajax({
    type: "post",
    dataType: "json",
    url: v4_ajax_object.ajax_url,
    data:formData,
    success: function(html){
        unblock(jQuery('.listpage'));
        jQuery('#row-product-list').html('');
        jQuery('#row-product-list').append(html['html']);
        jQuery('.nav-pagination').html('');
        jQuery('.pagination_div').append(html['nav']);
        jQuery('.woocommerce-result-count').html('');
        jQuery('.woocommerce-result-count').append(html['total_fabric_color']);
    }
});
}

jQuery(document).ready(function($){
	// Trigger Select2 JS. 
	jQuery('.blindmatrix-v4-select2').select2({
		templateResult: add_custom_img_select2,
		minimumResultsForSearch: -1
	});

    //select option price calc funtion
 
    jQuery(document).on('change', ".blindmatrix-v4-select2", function() {
        var $this = jQuery(this);
        var closestParent = $this.closest('.blindmatrix-v4-parameter-wrapper');
        var value = closestParent.find('.value');
        value.val('');
        var selectedOptionText = $this.find('option:selected').map(function() {
            return jQuery(this).text();
        }).get().join(', ');
        value.val(selectedOptionText);
        getcomponentSub($this);
        price_calculate();
    });
    
    //unit change trigger funtion
    jQuery(document).on('change', 'input[type="radio"][name="blindmatrix_v4_parameters_data[unit]"]', function () {
        jQuery('#unittype').val(jQuery(this).data('id'));
        jQuery('.blindmatrix-v4-width-val').val('');
        jQuery('.blindmatrix-v4-drop-val').val('');

        var unit_id = jQuery(this).data('id'),
         unit_name  = jQuery(this).val(), 
         $min_width = unitcalculate(unit_id,jQuery('#min_width').val()),
         $max_width = unitcalculate(unit_id,jQuery('#max_width').val()),
         $min_drop  = unitcalculate(unit_id,jQuery('#min_drop').val()),
         $max_drop  = unitcalculate(unit_id,jQuery('#max_drop').val()),$width_message = '',$drop_message = '';

         if($min_width){
            $width_message = 'Min '+$min_width;
         }

         if($max_width){
            $width_message = '' != $width_message ? $width_message +' '+ unit_name +' - Max '+$max_width+ ' '+unit_name : 'Max '+$max_width +' '+ unit_name;
         }

         if($min_drop){
            $drop_message = 'Min '+$min_drop;
         }

         if($max_drop){
            $drop_message = '' != $drop_message ? $drop_message +' '+ unit_name +' - Max '+$max_drop+ ' '+unit_name : 'Max '+$max_drop +' '+ unit_name ;
         }

         jQuery('.blindmatrix-v4-width-val').attr('placeholder',$width_message);
         jQuery('.blindmatrix-v4-drop-val').attr('placeholder',$drop_message);
    
    });

    jQuery('input[type="radio"][name="blindmatrix_v4_parameters_data[unit]"]:checked').change();

    //width drop enter price calculation
        jQuery(document).on('keyup', 'input.blindmatrix-v4-drop-val', function() {
            price_calculate();
        });
        jQuery(document).on('keydown', 'input.blindmatrix-v4-drop-val', function() {
            price_calculate();
        });

    // Product add to cart button trigger
        jQuery(document).on('click', '.blindmatrix-v4-add_to_cart_button', function (event) {
            event.preventDefault();
            addtocart();
        });
        jQuery(document).on('click', '.multiple-frame-list-button', function () {
            jQuery("body").find(".multiple-frame-list-button.selected_frame").removeClass('selected_frame');
            var new_banner = jQuery(this).children('img').attr('src');
            jQuery(this).addClass('selected_frame');
            jQuery(".configurator-main-headertype").attr("src",new_banner);
        });

        jQuery('.blindmatrix-v4-slider').slick({
          prevArrow: jQuery('.prev'),
          nextArrow: jQuery('.next'),
          infinite: false,
          speed: 300,
          slidesToShow: 3,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
        });    

    //trigger fabric color list on product list page
        var pei_productid = jQuery('#pei_productid').val();
        if(!pei_productid){
                return false;
        }
        fabriclist(pei_productid);

});

function add_custom_img_select2(opt){
	if (!opt.id) {
		return opt.text;
	} 
	var optimage = jQuery(opt.element).attr('data-img_url'); 
	if(!optimage || optimage == " " ){
		return opt.text;
	} else {                    
		var $opt = jQuery(
			'<span class="blindmatrix-v4-select2-img-single-product"><img src="' + optimage + '" width="60" /> ' + opt.text + '</span>'
		);
	return $opt;
	}
};

// unit colculation
function unitcalculate(unit_id,value){

    var value = parseFloat(value),result,inch; 
     
    if(unit_id == 1 ){
        result = (value / 10);
    }else if(unit_id == 4 ){
        inch = value / 25.4;
        result = round_up(inch,2);
    }else if (unit_id == 3 ){
        result = (value * 0.001);
    } else{
        result = value;
    }

    return result;
}
function round_up ( value, precision ) { 
    var pow = Math.pow(10,precision);
    return ( Math.ceil ( pow * value ) + Math.ceil ( pow * value - Math.ceil ( pow * value ) ) ) / pow; 
}
// display subcomponent list

function getcomponentSub($this){
    if($this.hasClass('blindmatrix-v4-subcomp-lvl2')){
        return false;
    }
    
    block(jQuery('.product-info'));
    var selected_value = $this.children("option:selected").val(),
        optionId = $this.find('option:selected').data('option_id'),
        field_id = $this.data('field_id'),
        product_id = jQuery('#product_id').val();
	
	$this.find('option').each(function(){
		if(selected_value != jQuery(this).val()){
			jQuery('.blindmatrix-v4-sub-component-section-'+jQuery(this).data('option_id')).remove();
		}
	});

    formData={ action:'subcomponent',
        product_id:product_id,
        selected_value:selected_value,
        optionId:optionId,
        field_id:field_id,
    };
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: v4_ajax_object.ajax_url,
        data:formData,
        success: function(response){
            unblock(jQuery('.product-info'));
            $this.closest('.blindmatrix-v4-parameter-wrapper').after(response.data.html);
            console.log('select2 success');
            jQuery('.blindmatrix-v4-select2').select2({
                templateResult: add_custom_img_select2,
                minimumResultsForSearch: -1
            });
        }
    });
 
};

//price calculation

function price_calculate(){
    jQuery('.price_wrapper').hide();
    formData={ action:'price_calculation',
        form_data:jQuery("#blindmatrix_v4_parameters_form").serialize(),
    };
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: v4_ajax_object.ajax_url,
        data:formData,
        success: function(response){
            if(response.fullpriceobject && response.fullpriceobject.costprice !== null && response.fullpriceobject.grossprice !== null &&  response.fullpriceobject.netprice !== null && response.fullpriceobject.vatprice !== null ){
                jQuery('#finalcostprice').val(response.finalcostprice);
                jQuery('#finalnetprice').val(response.finalnetprice);
                jQuery('#costprice').val(response.fullpriceobject.costprice);
                jQuery('#grossprice').val(response.fullpriceobject.grossprice);
                jQuery('#netprice').val(response.fullpriceobject.netprice);
                jQuery('#vatprice').val(response.fullpriceobject.vatprice);
                jQuery('.price_wrapper').show();
                jQuery('.showprice_wrapper').html(response.price_html);
            }
        }
    });

};


//product add to cart

function addtocart(){

    block(jQuery('.product-info'));
    formData={ action:'add_to_cart',
        form_data:jQuery("#blindmatrix_v4_parameters_form").serialize(),
    };
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: v4_ajax_object.ajax_url,
        data:formData,
        success: function(){
            unblock(jQuery('.product-info'));
            jQuery.confirm({
                title: 'Success!',
                columnClass: 'col-md-4 col-md-offset-4',
                content: 'The product is successfully added to cart',
                type: 'blue',
                typeAnimated: true,
                boxWidth: '30%',
                useBootstrap: false,
                buttons: {
                     'Continue shopping': {
                        btnClass: 'btn-blue',
                        text: 'Continue shopping', // With spaces and symbols
                        action: function () {
                                history.go(0);
                        }
                    },
                     'Proceed to cart': {
                        btnClass: 'btn-dark',             
                        text: 'Proceed to cart', // With spaces and symbols
                        action: function () {
                            window.location = v4_ajax_object.cart_url;
                        }
                    }
                }
            });
        }
    });
    return false;
}

