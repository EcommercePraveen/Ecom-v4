<?php
global $fabric_image_file_path;
$feildscategoryname = get_query_var("feildscategoryname");
$productslug = get_query_var("productname");
$fabricid = get_query_var("fabricid");
$fabricname = get_query_var("fabricname");
$colorid = get_query_var("colorid");
$pricegroup_id = get_query_var("pricegroupid");
$supplier_id = get_query_var("supplierid");

$productname =str_replace("-"," ",strtolower($productslug));

//get  product details
$get_v4_productlist = get_option('v4_productlistdata', true);
$product_list = json_decode(json_encode($get_v4_productlist->result->EcomProductlist), true);
$unit_type_data = json_decode(json_encode($get_v4_productlist->result->unit_type->original->result->unittype), true);
$id = array_search(strtolower($productname), array_map('strtolower', array_column($product_list, 'pei_ecomProductName')));

$product_id = $product_list[$id]['pei_productid'];

//get color details getfabriccolourdetails/{fabricid}/{colorid}
$mode ='getfabriccolourdetails/'.$fabricid.'/'.$colorid;
$color_result     = CallAPI_v4("POST",$mode);
$color_arr = json_decode(json_encode($color_result->result), true);
$min_width = $color_arr['Min Width'];
$max_width = $color_arr['Max Width'];
$min_drop = $color_arr['Min Drop'];
$max_drop = $color_arr['Max Width'];
$placeholder_text = '';
if($min_width){
	$placeholder_text = 'Min - '.$min_width;
}

if($max_width){
	$placeholder_text = '' != $min_width ? $placeholder_text.' & ' : $placeholder_text; 
	$placeholder_text = $placeholder_text. 'Max - '.$max_width;
}

$color_imag_url = $color_arr['Color Image'];
$unit_cost = $color_arr['Unit Cost'];
//get all form data
$mode ='getparameterdetails/'.$product_id.'/1';
$resultdata     = CallAPI_v4("POST",$mode);
$parameters_arr = json_decode(json_encode($resultdata->result), true);
$img_file_path_url = 'https://curtainmatrix.co.uk/ecommercesource/api/public/storage/';

$tax_list_response     = CallAPI_v4("POST",'gettaxlist/1');
$tax_list_response_arr = json_decode(json_encode($tax_list_response->result), true);
$chosen_tax_arr_key    = array_search(1, array_column($tax_list_response_arr, 'isDefault'));
$chosen_tax_arr        = isset($tax_list_response_arr[$chosen_tax_arr_key]) ? $tax_list_response_arr[$chosen_tax_arr_key]:array();

// get selected background image
$product_img_array =json_decode($product_list[$id]["pi_backgroundimage"],true);    
$product_deafultimage =json_decode($product_list[$id]["pi_deafultimage"],true);    

$background_color_image_url = $fabric_image_file_path.$color_imag_url;
$image_file_path ='https://curtainmatrix.co.uk/ecommercesource/api/public';
			foreach( $product_img_array as $product_img){
				if(isset($product_deafultimage['defaultimage']['backgrounddefault']) && $product_deafultimage['defaultimage']['backgrounddefault'] != ""){
					if (strpos($product_img, $product_deafultimage['defaultimage']['backgrounddefault']) !== false) {
					
						$mainframe = $image_file_path.$product_img;
                        
					}
				}
			}
?>

<form name="blindmatrix_v4_parameters_form" id="blindmatrix_v4_parameters_form" class="tooltip-container blindmatrix-v4-parameters-form cart" action="" method="post" enctype="multipart/form-data">

<input type='hidden' id="fabric_and_color_name" name="blindmatrix_v4_parameters_data[fabric_and_color_name]" value="<?php echo (ucwords(str_replace('-',' ',$fabricname))); ?>" />  
<input type='hidden' id="fabricname" name="blindmatrix_v4_parameters_data[fabricname]" value="<?php echo $color_arr['Fabric Name']; ?>" />  
<input type='hidden' id="colorname" name="blindmatrix_v4_parameters_data[colorname]" value="<?php echo $color_arr['Color Name']; ?>" />  
<input type='hidden' id="productname" name="blindmatrix_v4_parameters_data[productname]" value="<?php echo ucwords($productname); ?>" />  
<input type='hidden' id="feildscategoryname" name="blindmatrix_v4_parameters_data[feildscategoryname]" value="<?php echo ucwords(str_replace('-',' ',$feildscategoryname)); ?>" />  
<input type='hidden' id="product_id" name="blindmatrix_v4_parameters_data[product_id]" value="<?php echo($product_id); ?>" />  
<input type='hidden' id="fabricid" name="blindmatrix_v4_parameters_data[fabricid]" value="<?php echo($fabricid); ?>" />  
<input type='hidden' id="colorid" name="blindmatrix_v4_parameters_data[colorid]" value="<?php echo($colorid); ?>" /> 
<input type='hidden' id="rules_cost_price_comes_from" name="blindmatrix_v4_parameters_data[rules_cost_price_comes_from]" value="2" /> 
<input type='hidden' id="rules_net_price_comes_from" name="blindmatrix_v4_parameters_data[rules_net_price_comes_from]" value="2" /> 
<input type='hidden' id="vatvat_percentageprice" name="blindmatrix_v4_parameters_data[vat_percentage]" value="<?php echo esc_attr(!empty($chosen_tax_arr['taxValue']) ? $chosen_tax_arr['taxValue']:''); ?>" /> 
<input type='hidden' id="vatproduct_typeprice" name="blindmatrix_v4_parameters_data[product_type]" value="<?php echo($pricegroup_id); ?>" /> 
<input type='hidden' id="supplier_id" name="blindmatrix_v4_parameters_data[supplier_id]" value="<?php echo ($min_width); ?>" />

<input type='hidden' id="unittype" name="blindmatrix_v4_parameters_data[unittype]" value="" />  
<input type='hidden' id="min_width" name="blindmatrix_v4_parameters_data[min_width]" value="<?php echo ($min_width); ?>" />  
<input type='hidden' id="max_width" name="blindmatrix_v4_parameters_data[max_width]" value="<?php echo ($max_width); ?>" />  
<input type='hidden' id="min_drop" name="blindmatrix_v4_parameters_data[min_drop]" value="<?php echo ($min_drop); ?>" />  
<input type='hidden' id="max_drop" name="blindmatrix_v4_parameters_data[max_drop]" value="<?php echo ($max_drop); ?>" />  

<input type='hidden' id="finalcostprice" name="blindmatrix_v4_parameters_data[finalcostprice]" value="" />  
<input type='hidden' id="finalnetprice" name="blindmatrix_v4_parameters_data[finalnetprice]" value="" />  
<input type='hidden' id="costprice" name="blindmatrix_v4_parameters_data[costprice]" value="" />  
<input type='hidden' id="grossprice" name="blindmatrix_v4_parameters_data[grossprice]" value="" />  
<input type='hidden' id="netprice" name="blindmatrix_v4_parameters_data[netprice]" value="" />  
<input type='hidden' id="vatprice" name="blindmatrix_v4_parameters_data[vatprice]" value="" />  

    <div class="row align-center blinds -container" style="max-width: 1250px;" >    
        <div class="productaname" style="padding: 10px 0 10px;" >
            <a style="margin: 0;" href="#" target="_self" class="button secondary is-link is-smaller lowercase">
                <i class="icon-angle-left"></i>  <span>Back to roller blinds</span>
            </a>
            <h1 class="product-title product_title entry-title prodescprotitle"><span class="setcolorname"><?php echo ucwords(str_replace('-',' ',$fabricname)); ?></span> <?php echo ucwords($productname); ?></h1>
        </div>
        <div id="configurator-root" style="position:relative;" >
            <div class="configurator" >
                <div class="configurator-preview visible" style="position:relative; overflow:visible;box-shadow: 1px 0 5px #ccc; border-radius:10px" >
                    <div style="position:sticky; top:0;" >
                    <div class="product_preview">
                        <div id="main-img" class="configuratorpreviewimage" >
                            <img decoding="async" class="configurator-main-headertype" src="<?php echo($mainframe);?>" style="border-radius: 10px; background-image: url(<?php echo($background_color_image_url);?>);" alt="blinds image">
                            <p class="preview-desc blinds">  Diagram is for illustration only. </p>
                        </div>
                    </div>
                        <!-- Slider -->
                        <div class="col-md-12 slider-container">
                            <div class="blindmatrix-v4-slider responsive">
                                <?php
                                $frame_img_count = 0;
                                foreach( $product_img_array as $product_img):
                                    $selected_frame='';
                                    $frame_img_count+=1;
                                        $frame_file_path_url = $image_file_path.$product_img;
                                        if(isset($product_deafultimage['defaultimage']['backgrounddefault']) && $product_deafultimage['defaultimage']['backgrounddefault'] != ""){
                                            if (strpos($product_img, $product_deafultimage['defaultimage']['backgrounddefault']) !== false) {
                                            
                                                $selected_frame = 'selected_frame';
                                                
                                            }
                                        }
                                    ?>
                                    <div>    
                                        <a class="multiple-frame-list-button <?php echo( $selected_frame);?>">
                                            <img src="<?php echo($frame_file_path_url);?>" alt="" />
                                        </a>
                                    </div>    
                                <?php endforeach; ?>
                            </div>
                            <!-- control arrows -->
                           <?php if(3 < $frame_img_count): ?>
                            <div class="prev">
                                <span class="glyphicon icon-left" aria-hidden="true">&#11164;</span>
                            </div>
                            <div class="next">
                                <span class="glyphicon icon-right" aria-hidden="true">&#11166;</span>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
             <!--   product parameters start-->
                <div class="product-info">
                    <h3 style="text-align: center;">Please enter your measurements</h3>
                    <!-- unit select section -->
                    <div class="blinds-measurement" >
                        <div colspan="2" class="value" >
                            <span class="form-control-wrap ">
                                <span class="form-control radio" >
                                    <?php 
                                    foreach ($unit_type_data as $unit_type):
                                        $unit_name = $unit_type['name'];
                                        $unit_id = $unit_type['id'];
                                        $unit_hideshow = $unit_type['hideshow'];
                                        $unit_select='';
                                        if($unit_type['defaultid'] != 0){
                                            $unit_select='checked';
                                        }
                                        ?>
                                        <span class="list-item">
                                            <label>
                                                <input class="blindmatrix_v4_parameters_data[unit]" name="blindmatrix_v4_parameters_data[unit]" data-id="<?php echo($unit_id);?>" class="js-unit" value="<?php echo($unit_name);?>" <?php echo($unit_select);?> type="radio">
                                                <span class="list-item-label"><?php echo($unit_name);?></span>
                                            </label>
                                        </span>
                                        <?php endforeach; ?>
                                    </span>
                                </span>
                        </div>
                    </div>
                    <!-- parameters listing section -->
<?php     
            foreach($parameters_arr as $parameter){
                $field_type_id    = isset($parameter['fieldType'])? $parameter['fieldType'] :'';
                $field_name       = isset($parameter['fieldName'])? $parameter['fieldName'] :'';
                $field_id         = isset($parameter['fieldId'])? $parameter['fieldId'] :'';
                $mandatory        = isset($parameter['isMandatory'])? $parameter['isMandatory'] :'';
                $field_type_name  = isset($parameter['field_type_name'])? $parameter['field_type_name'] :'';
                if(!$field_type_id || !$field_name || !$field_id){
                    continue;
                }

                $extra_class = '';
                $options = array();
                $options_data = array();
                $custom_attributes = array();
                $custom_attributes_final = array();
				$placeholder_attr = '';
                if(11 == $field_type_id){
                    $extra_class ='blindmatrix-v4-width-val';
                    $custom_attributes = array(
                        'min' => $min_width,
                        'max' => $max_width,
                    );
					$placeholder_attr = $placeholder_text;
                }

                if(12 == $field_type_id){
                    $extra_class ='blindmatrix-v4-drop-val';
                    $custom_attributes = array(  
                        'min' => $min_drop,
                        'max' => $max_drop,
                    );
					$placeholder_attr = $placeholder_text;
                }

                $input_class = "blindmatrix-v4-input $extra_class";
                $hidden_items = array(
                    'class' => 'label',
                    'name'      => "blindmatrix_v4_parameters_data[$field_type_id][$field_id][label]",
                    'value'     => $field_name,
                );
                $input_name = "blindmatrix_v4_parameters_data[$field_type_id][$field_id][value]";
                if(3 == $field_type_id){
                    $result_data     = CallAPI_v4("GET",'get_list_level1_data/'.$product_id.'/'.$field_id.'/'.$field_type_id);
                    $result_data_arr = !empty($result_data) ? json_decode(json_encode($result_data),true):array();
                    if(empty($result_data_arr['result'] || !is_array($result_data_arr['result']))){
                        continue;
                    }
                    $hidden_items = array(
                        array(
                            'class' => 'label',
                            'name'  => "blindmatrix_v4_parameters_data[$field_type_id][$field_id][label]",
                            'value' => $field_name,
                        ),
                        array(
                            'class' => 'value',
                            'name'  => "blindmatrix_v4_parameters_data[$field_type_id][$field_id][value]",
                            'value' => '',
                        )
                    );
                    foreach ($result_data_arr['result'] as $list_value) {
                        $id = isset($list_value['id']) ? $list_value['id']:'';
                        $option_name = isset($list_value['Option Name']) ? $list_value['Option Name']:'';
                        $option_image_url = isset($list_value['Option Image']) ? $list_value['Option Image']:'';
                        $option_id = isset($list_value['fol_optionId']) ? $list_value['fol_optionId']:'';
                        $option_qty = isset($list_value['Option Qty']) ? $list_value['Option Qty']:'';
                        if(!$option_id || !$option_name){
                            continue;
                        }
                     
                        $options[$id] = $option_name;
                        $option_image_url = str_replace('/storage','',$option_image_url);
						$option_image_url = ltrim($option_image_url,'/');
                        $options_data[$id]['img_url'] = $img_file_path_url.$option_image_url;
						if(!@getimagesize($img_file_path_url.$option_image_url)){
							$options_data[$id]['img_url'] = '';
						}

                        $options_data[$id]['option_id'] = $option_id;
                    
                     }

                    $input_class = 'blindmatrix-v4-input blindmatrix-v4-select2';
                    $input_name = "blindmatrix_v4_parameters_data[$field_type_id][$field_id][chosen_options][]";
                }
                $default_custom_attributes =  array(
                    'mandatory'     => $mandatory,
                );

                $custom_attributes_final  = array_merge($default_custom_attributes,$custom_attributes);
                $field_args = array(
                    'input_class'       => $input_class,
                    'wrapper_class'     => 'blindmatrix-v4-parameter-wrapper',
                    'label'             => $field_name,
					'placeholder'       => $placeholder_attr,
                    'name'              => $input_name,
                    'custom_attributes' => $custom_attributes_final,
                    'options'           => $options,
                    'options_data'      => $options_data,
                    'default'           => '',
                    'description'       => '',
                    'value'             => '',
                    'multiple'          => false,
                    'data'              => array(
                        'field_type_id'   => $field_type_id,
                        'field_id'        => $field_id,
                    ),
                    'css'               => '',
                    'hidden_items'      => array($hidden_items),
                );
                echo get_blindmatrix_v4_parameters_HTML($field_type_id,$field_args);
            }
            ?>
                    <!-- product price section -->
                    <div class="price_wrapper" style="display:none;">
						<div class="price_text">Your Price</div>
						<div class="showprice_wrapper" > </div>
					</div>
                    <!-- quantity and add to cart select section -->
                    <div class="woocommerce-variation-add-to-cart variations_button ">
							<div class="quantity buttons_added">
								<input type="button" value="-" class="minus button is-form">
								<input type="number" id="qty" class="input-text qty text" step="1" min="1" max="" name="blindmatrix_v4_parameters_data[qty]" value="1" title="Qty" size="4" placeholder="" inputmode="numeric">
								<input type="button" value="+" class="plus button is-form">
							</div>
							<button type="submit" name="submit-btn" value="Submit" class=" blindmatrix-v4-add_to_cart_button button "><i class="icon-shopping-cart"></i> Add to cart</button>
						</div>
                </div>
              <!-- product parameters end-->
            </div>
        </div>
    </div>
</form>

<style>
    .configurator {
        grid-template-columns: 1fr 1.14fr;
        display: grid;
    }
    .product-info {
        padding: 10px 50px 0px;
        background: rgb(247, 246, 246);
        margin: 0px 20px;
        position: relative;
        border-radius: 10px;
        align-content: space-between;
        border-top: 4px solid rgb(0, 194, 255);
    }
    .price_wrapper {
        font-size: 1.5em;
        margin: .5em 0;
        font-weight: bolder;
        text-align: center;
    }
    .price_text {
        color: #00c2ff;
        margin-bottom: 0.3em;
    }
    .blindmatrix-v4-parameter-wrapper span.select2-selection {
        padding-left: 8px;
        border-radius: 20px !important;
    }
    .blindmatrix-v4-parameter-wrapper input.blindmatrix-v4-input, .blindmatrix-v4-parameter-wrapper span.select2-container,
    .blindmatrix-v4-parameter-wrapper .blindmatrix-v4-select2 {
        margin: 0px;
        width: 75% !important;
        border-radius: 20px;
    }
    .blindmatrix-v4-parameter-wrapper {
        display: flex;
        align-content: center;
        align-items: center;
        justify-content: space-between;
        padding: 5px 0px;
        margin: 5px 0px;
    }
    span.form-control.radio {
        display: flex;
        align-content: center;
        align-items: center;
        justify-content: center;
    }
    span.list-item {
        margin: 0px .5rem;
    }
    .blinds-measurement .value {
        display: inline-block;
        padding: 0px 10px;
        border-radius: 25px;
        box-shadow: 0 0 1px 0 rgba(24, 94, 224, 0.15), 0 6px 12px 0 rgba(24, 94, 224, 0.1);
        background: #fff;
        border-left: 4px solid #00c2ff;
        border-right: 4px solid #00c2ff;
    }
    button.blindmatrix-v4-add_to_cart_button{
        border-radius: 2em;
        background-color: #00c1fe;
    }
    .woocommerce-variation-add-to-cart.variations_button {
        text-align: center;
    }

    /*slider*/

/* Custom Arrow */
.prev {
	color: #999;
	position: absolute;
	top: 38%;
	left: 0em;
	font-size: 1.5em;
}
.next {
	color: #999;
	position: absolute;
	top: 38%;
	right: 0em;
	font-size: 1.5em;
}
img.configurator-main-headertype {
    object-fit: cover;
    object-position: center;
    position: absolute;
    width: 100%;
    height: 100%;
}
.col-md-12.slider-container {
        width: 100%;
        position: relative;
        margin: 1% 0px;
        border-top: 1px solid rgb(204, 204, 204);
        padding-top: 5px;
        display: block;
    }
    .slick-slide {
    max-width: 45%;
    padding: 0px 10px;
}
.selected_frame img {
    outline: 2px solid #00c2ff;
}
a.multiple-frame-list-button img {
    margin: 3px;
    position: initial;
    width: auto;
    z-index: 999;
    height: 70px;
    object-fit: unset;
    margin-left: 0px;
    margin-right: 0;
}
.product_preview {
    width: 100%;
    display: block;
    position: relative;
    padding-bottom: 100% !important;
}
</style>