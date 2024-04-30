<?php

/**
 *
 * all globel veriable list 
 *
 */ 
function global_blinds_variables_v4() {
    global $v4_product_page;
    global $v4_product_visualizer_page;
    global $fabric_image_file_path;
    $v4_product_page = 'products';
    $v4_product_visualizer_page = 'productview';
    $fabric_image_file_path = 'https://curtainmatrix.co.uk/ecommercesource/api/public/storage/attachments/ECOMMERCELATEST/material/colour/';

}
add_action( 'after_setup_theme', 'global_blinds_variables_v4' );
/**
 *
 * all custom value list 
 *
 */ 
function custom_rewrite_tag_v4() {

    add_rewrite_tag('%feildscategoryname%', '([^&]+)');
    add_rewrite_tag('%productname%', '([^&]+)');
    add_rewrite_tag('%fabricname%', '([^&]+)');
    add_rewrite_tag('%fabricid%', '([^&]+)');
    add_rewrite_tag('%colorid%', '([^&]+)');
    add_rewrite_tag('%pricegroupid%', '([^&]+)');
    add_rewrite_tag('%mapid%', '([^&]+)');
    add_rewrite_tag('%supplierid%', '([^&]+)');

  }
  add_action('init', 'custom_rewrite_tag_v4', 10, 0);

 /**
 *
 * all custom value page list 
 *
 */ 
function custom_rewrite_rule_v4() {
	global $v4_product_page;
    global $v4_product_visualizer_page;
	add_rewrite_rule('^'.$v4_product_page.'/([^/]*)/([^/]*)/?','index.php?page_id=8220&feildscategoryname=$matches[1]&productname=$matches[2]','top');
	add_rewrite_rule('^'.$v4_product_visualizer_page.'/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?','index.php?page_id=8236&feildscategoryname=$matches[1]&productname=$matches[2]&fabricname=$matches[3]&fabricid=$matches[4]&colorid=$matches[5]&mapid=$matches[6]&pricegroupid=$matches[7]&supplierid=$matches[8]','top');
}
add_action('init', 'custom_rewrite_rule_v4', 10, 0);

/**
 *
 * store get product details
 *
 */
add_action('init','get_product_list_data');
function get_product_list_data(){
    $get_product_list_data = CallAPI_v4("POST","getproductsdetails",array());
    update_option( 'v4_productlistdata', $get_product_list_data);
	
}
/**
 *
 * v4 api call function
 *
 */
function CallAPI_v4($method,$pass_data ,$data = false,$node = false)
{
	    try{
			$myobheader  = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'companyname: ECOMMERCELATEST',
			'platform: Ecommerce',
			'Ecommercekey:83218ac34c1834c26781fe4bde918ee4'
		);
        if($node == true){
            $url = "https://curtainmatrix.co.uk/ecommercesource/nodeapi/";
        }else{
            $url = 'https://curtainmatrix.co.uk/ecommercesource/api/public/api/';
        }
		$url = $url.$pass_data;
        $curl = curl_init();
    
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
    
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s&%s", $url, http_build_query($data));
        }
		curl_setopt($curl, CURLOPT_HTTPHEADER, $myobheader);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of the url
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    
        $result = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            custom_logs($error_msg);
        }
        
        curl_close($curl);
    
        return json_decode($result);
    
    }catch(Exception $e){
        $error_message = $e->getMessage();
        custom_logs($error_message);
    }
   
}

/**
 *
 * custom shortcode create
 *
 *
 */
function BlindMatrix_Hub_v4($attrs, $content = null) {
	$buffer='';
    if (isset($attrs['source'])) {
        $file = strip_tags($attrs['source']);
        if ($file[0] != '/')
            $file = ABSPATH .'wp-content/plugins/BlindMatrix-Api-v4/Shortcode-Source/'. $file .'.php';

        ob_start();
        include($file);
        $buffer = ob_get_clean();
        $buffer = do_shortcode($buffer);
    }
    return $buffer;
}

// Here because the funciton MUST be define before the "add_shortcode" since 
// "add_shortcode" check the function name with "is_callable".
add_shortcode('BlindMatrixv4', 'BlindMatrix_Hub_v4');


/**
 *
 * custom style and script file enque
 *
 *
 */
add_action('wp_enqueue_scripts', 'v4_frontend_styles');

function v4_frontend_styles() {
    ;
	wp_enqueue_script( 'blindmatrix-v4-select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery'));
	wp_enqueue_script( 'blindmatrix-v4-slider-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.5/slick.min.js', array( 'jquery'));
	wp_enqueue_script( 'blindmatrix-v4-conform-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js', array( 'jquery'));
	wp_enqueue_style( 'blindmatrix-v4-select2-css','https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array() );
	wp_enqueue_style( 'blindmatrix-v4-slider-css','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.5/slick.min.css', array() );
	wp_enqueue_style( 'blindmatrix-v4-conform-css','https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css', array() );
    wp_register_style( 'blindmatrix_apiv4', plugins_url('BlindMatrix-Api-v4/assets/css/frontend.css'));
    wp_enqueue_style( 'blindmatrix_apiv4' );
    wp_register_script('frontend_custom_js', plugins_url('BlindMatrix-Api-v4/assets/js/frontend.js'),array('jquery','jquery-blockui', 'blindmatrix-v4-select2-js','blindmatrix-v4-slider-js','blindmatrix-v4-conform-js'));
    wp_enqueue_script('frontend_custom_js');
    wp_localize_script( 'frontend_custom_js', 'v4_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'cart_url'=>get_permalink( wc_get_page_id( 'cart' )) ) );
}

/**
 *
 * free sample add to cart
 *
 *
 */
add_action( 'wp_ajax_nopriv_add_freesample', 'add_freesample' );
add_action( 'wp_ajax_add_freesample', 'add_freesample' );


function add_freesample(){
	global $woocommerce;
	$cart_item_data['color_id'] = $_POST['color_id'];
	$cart_item_data['fabric_id'] = $_POST['fabric_id'];
	$cart_item_data['pricing_grp_id'] = $_POST['pricing_grp_id'];
	$cart_item_data['current_post_title'] = $_POST['fabricname'];
	$cart_item_data['new_product_image_path'] = $_POST['fabric_img_url'];
	$product_id = 4803;
	$quantity = 1;
	$custom_price= 0;
    $html =[];
	$cart = $woocommerce->cart->add_to_cart( $product_id, $quantity,$auto_id,$variation,array('my_new_price'=>$custom_price,'current_post_title'=>$cart_item_data['current_post_title'],'product_my_blind_attr'=>$cart_item_data,'new_product_image_path'=>$cart_item_data['new_product_image_path'],'new_product_url'=>'','vaterate'=>$vaterate));
    if(isset($cart) && $cart !='' ){
        $html['success'] ='true';
    }
    $html['value'] ='sample added';
    echo wp_json_encode($html);
    exit;
}

 /**
 *
 * product fabric and color list page call
 *
 *
 */
add_action( 'wp_ajax_nopriv_fabriclist', 'fabriclist' );
add_action( 'wp_ajax_fabriclist', 'fabriclist' );


function fabriclist(){
    
    global $v4_product_visualizer_page;
    $feildscategoryid = $_POST['feildscategoryid'];
    $pei_productid =  $_POST['pei_productid'];
    $productslug =  $_POST['productslug'];
    $feildscategoryname =  $_POST['feildscategoryname'];
    $ecomFreeSample =  $_POST['ecomFreeSample'];
    $data =array("page"=> $_POST['page']);
    $mode ='fabriclistview/'.$feildscategoryid.'/'.$pei_productid;

    $resultcontact = CallAPI_v4("POST",$mode,json_encode($data));
    $total_fabric_color = $resultcontact->result->total.' Items';
    $total_pages = $resultcontact->result->total_pages;
    $current_page = $resultcontact->result->current_page;
    $per_page = $resultcontact->result->per_page;
    

    $products_list_data = json_decode(json_encode($resultcontact->result->Ecomfabiclist), true);
    
    $html = [];
    ob_start();
    foreach($products_list_data as $product_data):
        
        $fabric_img_url = $fabric_image_file_path.$product_data['colorimage'];
        $mapid = $product_data['mapid'];
        
        $pricing_grp_id = $product_data['groupid'];
        $minprice = $product_data['minprice'];
        $supplier_id = $product_data['supplierid'];
        if($feildscategoryname == 'blinds-with-fabric'){
            $fabric_id = $product_data['fd_id'];
            $color_id = $product_data['cd_id'];
            $fabricname = $product_data['fabricname']." ".$product_data['colorname'];
            $fabricname_slug =str_replace(" ","-",strtolower($fabricname));
            $visulizer_page_link = '/'.$v4_product_visualizer_page.'/'.$feildscategoryname.'/'.$productslug.'/'.$fabricname_slug.'/'.$fabric_id.'/'.$color_id.'/'.$mapid.'/'.$pricing_grp_id.'/'.$supplier_id;
  
        }else{
            $color_id = $product_data['cd_id'];
            $fabricname = $product_data['colorname'];
            $fabricname_slug =str_replace(" ","-",strtolower($fabricname));
            $visulizer_page_link = '/'.$v4_product_visualizer_page.'/'.$feildscategoryname.'/'.$productslug.'/'.$fabricname_slug.'/'.$color_id.'/'.$mapid.'/'.$pricing_grp_id.'/'.$supplier_id;

        }
        
       
          ?>    
        <div class="col product row-box-shadow-2" >
            <div class="col-inner" >
                <div class="product-small box " >
                        <div class="box-image" >
                            <div class="image-fade_in_back" >
                                <a href="<?php echo($visulizer_page_link);?>">
                                    <img src="https://blindmatrix.biz/modules/PriceBooks/fabric_color/YOURBLINDSSHOP/Roller%20Blinds/frame/Roller%20Blinds~~45970.webp" class="product-frame frame_backgound" style="background-image:url(<?php echo($fabric_img_url);?>);background-repeat: no-repeat;width: 100%;height: 100%;">
                                </a>
                            </div>
                        </div>
                        <div class="product-info-container" >
                            <div class="product details product-item-details" >
                                <h2 class="product name product-item-name"><a class="product-item-link" href=""><?php echo($fabricname);?></a></h2>
                                <?php if(isset($minprice) && $minprice !== ''):?>
                                <a href="<?php echo($visulizer_page_link);?>" title="<?php echo($fabricname);?>" class="action more">
                                    <span class="price-container">
                                        <span id="product-price" class="price-wrapper ">
                                            <span class="price">£<?php echo($minprice);?></span>
                                        </span>
                                    </span>
                                 </a>
                                 <?php endif?>
                            </div>
                            <div class="small-product-img" >
                                <a href="<?php echo($visulizer_page_link);?>" title="<?php echo($fabricname);?>" class="action more">
                                    <div class="product-image-container" style="position: relative;" >
                                        <img alt="<?php echo($fabricname);?>" src="<?php echo($fabric_img_url);?>" width="100" height="100" style="" class="product-image-photo swatch-img">
                                    </div>									   
                                </a>
                            </div>
                        </div>
                            <a href="#" style="border-color: #002746;color:#fff;padding: 0px 0.3em;font-size: 11px; margin: 0 !important;background-color: #002746;" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
                                <i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
                            </a>
                        <?php if($ecomFreeSample == 1): ?>    
                            <a class="sample_addtocart_container" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="freesample(this,'<?php echo($color_id);?>','<?php echo($fabric_id);?>','<?php echo($pricing_grp_id);?>','<?php echo($fabricname);?>','<?php echo($fabric_img_url);?>')">
                            <span style="vertical-align: middle;padding: 0px !important;margin:5px 0 !important">Free Sample</span>
                            </a>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach;  
$result = ob_get_contents();
ob_end_clean();

$html['html'] =$result;
ob_start();
?>
    <ul class="page-numbers nav-pagination links text-center">  
        <?php for ($x = 1; $x <= $total_pages; $x++): 
            if($current_page == $x){
                $current ='current';
            }else{
                $current='';
            }
            
            ?>
            <li>
                <a onclick="pagination(<?php echo($x);?>);" class="page-number <?php echo($current);?>"><?php echo($x);?></a>
            </li>  
        <?php endfor; ?>
    </ul>
<?php
$nav_html = ob_get_contents();
ob_end_clean();
$html['nav'] =$nav_html;
$html['total_fabric_color'] = $total_fabric_color;
$html['success'] ='true';
echo wp_json_encode($html);
exit;
}

/**
 *
 * Get blindmatrix v4 parameters in HTML
 * 
 * @return HTML
 */
function get_blindmatrix_v4_parameters_HTML($chosen_field_type_id,$custom_html_args = array(),$echo = true){
    $default_html_args = array(
        'input_class'       => '',
        'wrapper_class'     => '',
        'label'             => '',
		'placeholder'       => '',
        'name'              => '',
        'custom_attributes' => array(),
        'options'           => array(),
		'options_data'      => array(),
        'default'           => '',
        'description'       => '',
        'value'             => '',
        'multiple'          => false,
        'data'              => array(),
        'css'               => '',
        'hidden_items'      =>array(),
    );
    
    $html_args  = array_merge($default_html_args,$custom_html_args);
    
    $custom_attributes = array();
    if ( ! empty( $html_args['custom_attributes'] ) && is_array( $html_args['custom_attributes'] ) ){
		foreach ( $html_args['custom_attributes'] as $attribute => $attribute_value ) {
			if(!$attribute_value){
		       continue;
		    }
			$custom_attributes[] = $attribute . '=' . $attribute_value ;
		}
    }
		
	$data = array();
	if ( ! empty( $html_args['data'] ) && is_array( $html_args['data'] ) ) {
		foreach ( $html_args['data'] as $key => $value ) {
			$data[] = "data-".$key.'='.$value;
		}
	}
	
    $options_data = array();
	if ( ! empty( $html_args['options_data'] ) && is_array( $html_args['options_data'] ) ) {
		foreach ( $html_args['options_data'] as $key => $value ) {
		    foreach ($value as $option_key => $option_val ) {
		        if(!$option_val){
		            continue;
		        }
	            $options_data[$key][$option_key] = 'data-'. $option_key . '=' . $option_val ;
            }
		}
	}

    $html_args['custom_attributes'] = $custom_attributes;
    $html_args['data']              = $data;
    $html_args['options_data']      = $options_data;
 
    $field_types = array(
        '3' => 'list',
        '5' => 'fabric_and_color',
        '6' => 'number',
        '11' => 'width',
        '12' => 'drop',
        '18' => 'text',
    );

    if(!$chosen_field_type_id){
        return;
    }

    $field_type_name= isset($field_types[$chosen_field_type_id]) ? $field_types[$chosen_field_type_id]:'';
    if(!$field_type_name){
        return;
    }
    
    switch($chosen_field_type_id){
        case '11':
        case '12':
        case '6':
            $field_type_name = 'number';
            break;
    }
    
    $function = 'blindmatrix_render_'.$field_type_name.'_field';
    if($echo){
        $function($html_args);
    }else{
        ob_start();
        $function($html_args);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

/**
 *
 * Render number field HTML
 * 
 * @return HTML
 */
function blindmatrix_render_number_field($html_args){
    ?>
    <div class="<?php echo esc_attr($html_args['wrapper_class']); ?>">
        <label><?php echo esc_html($html_args['label']); ?></label>
        <input type="number" 
        name="<?php echo esc_attr($html_args['name']); ?>"
        class="<?php echo esc_html($html_args['input_class']); ?>" 
        <?php echo !empty($html_args['custom_attributes']) && is_array($html_args['custom_attributes']) ? implode(' ',$html_args['custom_attributes']):''; ?>
		placeholder="<?php echo esc_attr($html_args['placeholder']); ?>"	   
        value=""
        <?php echo !empty($html_args['data']) && is_array($html_args['data']) ? implode(' ',$html_args['data']):''; ?>>
        <?php 
        if(!empty($html_args['hidden_items']) && is_array($html_args['hidden_items'])):
            foreach($html_args['hidden_items'] as $hidden_item):
                ?>
                <input type="hidden" class="<?php echo esc_html($hidden_item['class']); ?>" name="<?php echo esc_attr($hidden_item['name']); ?>" value="<?php echo esc_html($hidden_item['value']); ?>"/>
                <?php
            endforeach;
        endif;
        ?>
    </div>
    <?php
}

function blindmatrix_render_fabric_and_color_field($html_args){

}

/**
 *
 * Render list field - dropdown/component HTML
 * 
 * @return HTML
 */
function blindmatrix_render_list_field($html_args){
    ?>
    <div class="<?php echo esc_attr($html_args['wrapper_class']); ?>">
        <label><?php echo esc_html($html_args['label']); ?></label>
        <select class="<?php echo esc_html($html_args['input_class']); ?>" 
				<?php echo !empty($html_args['custom_attributes']) && is_array($html_args['custom_attributes']) ? implode(' ',$html_args['custom_attributes']):''; ?>
				name="<?php echo esc_attr($html_args['name']); ?>" 
				<?php echo esc_attr(!empty($html_args['data']) && is_array($html_args['data']) ? implode(' ',$html_args['data']):''); ?>>
                <option value="" >Choose an option</option>
            <?php 
            if(!empty($html_args['options']) && is_array($html_args['options'])):
                foreach($html_args['options'] as $key => $option_name):
                    ?>
                    <option value="<?php echo esc_html($key);?>" 
                    <?php echo esc_attr(!empty($html_args['options_data'][$key]) && is_array($html_args['options_data'][$key]) ? implode(' ',$html_args['options_data'][$key]):''); ?>><?php echo esc_html($option_name);?></option>
                <?php 
                endforeach;
            endif;    
            ?>
        </select>
        <?php 
        if(!empty($html_args['hidden_items']) && is_array($html_args['hidden_items'])):
            foreach($html_args['hidden_items'] as $hidden_item_arr):
                foreach($hidden_item_arr as $hidden_item):
                    ?>
                    <input type="hidden" class="<?php echo esc_html($hidden_item['class']); ?>" name="<?php echo esc_attr($hidden_item['name']); ?>" value="<?php echo esc_html($hidden_item['value']); ?>"/>
                    <?php
                endforeach;
            endforeach;
        endif;
        ?>
    </div>
    <?php
}

/**
 *
 * Render text field HTML
 * 
 * @return HTML
 */
function blindmatrix_render_text_field($html_args){
    ?>
    <div class="<?php echo esc_attr($html_args['wrapper_class']); ?>">
        <label><?php echo esc_html($html_args['label']); ?></label>
        <input type="text" 
		placeholder="<?php echo esc_attr($html_args['placeholder']); ?>"	   
        name="<?php echo esc_attr($html_args['name']); ?>"
        class="<?php echo esc_html($html_args['input_class']); ?>" 
        <?php echo !empty($html_args['custom_attributes']) && is_array($html_args['custom_attributes']) ? implode(' ',$html_args['custom_attributes']):''; ?>
        value=""
        <?php echo !empty($html_args['data']) && is_array($html_args['data']) ? implode(' ',$html_args['data']):''; ?>>
        <?php 
        if(!empty($html_args['hidden_items']) && is_array($html_args['hidden_items'])):
            foreach($html_args['hidden_items'] as $hidden_item):
                ?>
                <input type="hidden" class="<?php echo esc_html($hidden_item['class']); ?>" name="<?php echo esc_attr($hidden_item['name']); ?>" value="<?php echo esc_html($hidden_item['value']); ?>"/>
                <?php
            endforeach;
        endif;
        ?>
    </div>
    <?php
}

/**
 *
 * Get blindmatrix v4 sub component in HTML
 * 
 * 
 */
add_action( 'wp_ajax_nopriv_subcomponent', 'subcomponent' );
add_action( 'wp_ajax_subcomponent', 'subcomponent' );
function subcomponent(){
    $product_id = $_POST['product_id'];
    $selected_value = $_POST['selected_value'];
    $optionId = $_POST['optionId'];
    $field_id = $_POST['field_id'];
    $level_id = 2 ;
    $mode ='products/fields/list/'.$product_id.'/'.$level_id.'/'.$optionId.'/'.$field_id.'/'.$selected_value;

    $resultcontact = CallAPI_v4("POST",$mode);
    $parameters_arr = json_decode(json_encode($resultcontact->result), true);
    $result = array();
    ob_start();
    foreach($parameters_arr as $parameter){
        $field_type_id    = isset($parameter['fieldType'])? $parameter['fieldType'] :'';
        $field_name       = isset($parameter['fieldName'])? $parameter['fieldName'] :'';
        $field_id         = isset($parameter['fieldId'])? $parameter['fieldId'] :'';
        $mandatory        = isset($parameter['isMandatory'])? $parameter['isMandatory'] :'';
        $field_type_name  = isset($parameter['field_type_name'])? $parameter['field_type_name'] :'';
        if(!$field_type_id || !$field_name || !$field_id){
            continue;
        }

        $input_class = 'blindmatrix-v4-input';
        $options = array();
        $options_data = array();
         $hidden_items = array(
            'class' => 'label',
            'name'      => "blindmatrix_v4_parameters_data[$field_type_id][$field_id][label]",
            'value'     => $field_name,
         );
         $input_name =  "blindmatrix_v4_parameters_data[$field_type_id][$field_id][value]";
        if(3 == $field_type_id){
            $input_class            = 'blindmatrix-v4-input blindmatrix-v4-select2 blindmatrix-v4-subcomp-lvl2';
			$mode                   ='products/options/listforfieldspage/byfield/'.$product_id.'/'.$field_id.'/3';
			$sub_component_response = CallAPI_v4("GET",$mode);
			$sub_comp_arr           = json_decode(json_encode($sub_component_response->result), true);
			if(!empty($sub_comp_arr) && is_array($sub_comp_arr)){
				foreach($sub_comp_arr as $sub_comp_value){
						$id = isset($sub_comp_value['id']) ? $sub_comp_value['id']:'';
                        $option_name = isset($sub_comp_value['Option Name']) ? $sub_comp_value['Option Name']:'';
                        $option_image_url = isset($sub_comp_value['Option Image']) ? $sub_comp_value['Option Image']:'';
                        $option_id = isset($sub_comp_value['fol_optionId']) ? $sub_comp_value['fol_optionId']:'';
                        $option_qty = isset($sub_comp_value['Option Qty']) ? $sub_comp_value['Option Qty']:'';
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
            
            $input_name = "blindmatrix_v4_parameters_data[$field_type_id][$field_id][chosen_options][]";
        }
        
        
        $field_args = array(
            'input_class'       => $input_class,
            'wrapper_class'     => "blindmatrix-v4-parameter-wrapper blindmatrix-v4-sub-component-section-$optionId",
            'label'             => $field_name,
            'name'              => $input_name,
            'custom_attributes' => array(
                'mandatory'     => $mandatory
            ),
            'options'           => $options,
            'options_data'      => $options_data,
            'default'           => '',
            'description'       => '',
            'value'             => '',
            'multiple'          => false,
            'data'              => array(
                'field_type_id'   => $field_type_id,
                'field_id'        => $field_id,
                'field_type_name' => $field_type_name 
            ),
            'css'               => '',
            'hidden_items'      => array($hidden_items),
        );
        get_blindmatrix_v4_parameters_HTML($field_type_id,$field_args);
    }

    $content = ob_get_contents();
    ob_end_clean();

    $result['html'] = $content;
    $result['success'] ='true';
    wp_send_json_success($result);
}


/**
 *
 * Get price details
 * 
 * 
 */
add_action( 'wp_ajax_nopriv_price_calculation', 'price_calculation' );
add_action( 'wp_ajax_price_calculation', 'price_calculation' );
function price_calculation(){
    $form_data = isset($_POST['form_data']) ? $_POST['form_data']:array();
    $form_data = wp_parse_args($form_data);
    $blindmatrix_v4_parameters_data = isset($form_data['blindmatrix_v4_parameters_data']) ? $form_data['blindmatrix_v4_parameters_data']:array();
    $list_data = isset($blindmatrix_v4_parameters_data[3]) ? $blindmatrix_v4_parameters_data[3]:array();
    $option_ids = !empty($list_data) && is_array($list_data) ? array_values($list_data) : array();
    $option_data =[];
    if(is_array($option_ids) || !empty($option_ids)){
        $chosen_option_data = array_filter(array_map(function($option_id){
            return isset($option_id['chosen_options']) ? $option_id['chosen_options']:''; 
        },$option_ids));
        
        if(is_array($chosen_option_data) || !empty($chosen_option_data)){
            foreach($chosen_option_data as $chosen_option_ids){
                if(empty(array_filter($chosen_option_ids))){
                        continue;
                }
                
                foreach($chosen_option_ids as $chosen_option_id){
                    $option_data[] =[
                        "optionvalue" => $chosen_option_id,
                        "fieldtypeid" => 3,
                        "optionqty"=> 1
                    ];
                }
            }
        }
    }

    $product_id = isset($blindmatrix_v4_parameters_data['product_id']) ? $blindmatrix_v4_parameters_data['product_id']:'';
    $supplier_id = isset($blindmatrix_v4_parameters_data['supplier_id']) ? $blindmatrix_v4_parameters_data['supplier_id']:'';

    $width_arr = isset($blindmatrix_v4_parameters_data[11]) ? $blindmatrix_v4_parameters_data[11]:'';
    $width_arr = !empty($width_arr) && is_array($width_arr) ? array_values($width_arr):array();
    $width     = isset($width_arr[0]['value']) ? $width_arr[0]['value']:'';
    
    $drop_arr = isset($blindmatrix_v4_parameters_data[12]) ? $blindmatrix_v4_parameters_data[12]:'';
    $drop_arr = !empty($drop_arr) && is_array($drop_arr) ? array_values($drop_arr):array();
    $drop     = isset($drop_arr[0]['value']) ? $drop_arr[0]['value']:'';
    
    $vat_percentage = isset($blindmatrix_v4_parameters_data['vat_percentage']) ? $blindmatrix_v4_parameters_data['vat_percentage']:'';
    $unittype = isset($blindmatrix_v4_parameters_data['unittype']) ? $blindmatrix_v4_parameters_data['unittype']:'';
    $fabricid = isset($blindmatrix_v4_parameters_data['fabricid']) ? $blindmatrix_v4_parameters_data['fabricid']:'';
    $colorid = isset($blindmatrix_v4_parameters_data['colorid']) ? $blindmatrix_v4_parameters_data['colorid']:'';
    $product_type = isset($blindmatrix_v4_parameters_data['product_type']) ? $blindmatrix_v4_parameters_data['product_type']:'';
    $rules_cost_price_comes_from = isset($blindmatrix_v4_parameters_data['rules_cost_price_comes_from']) ? $blindmatrix_v4_parameters_data['rules_cost_price_comes_from']:'';
    $rules_net_price_comes_from = isset($blindmatrix_v4_parameters_data['rules_net_price_comes_from']) ? $blindmatrix_v4_parameters_data['rules_net_price_comes_from']:'';
    $option_data  = json_encode($option_data);
    $data = '{
        "blindopeningwidth": [],
        "productid": '.$product_id.',
        "supplierid": '.$supplier_id.',
        "mode": "",
        "width": '.$width.',
        "drop": '.$drop.',
        "pricegroup": ["'.$product_type.'"],
        "customertype": 1,
        "optiondata": '.$option_data.',
        "unittype": '.$unittype.',
        "orderitemqty": 1,
        "jobid": null,
        "overridetype": 4,
        "overrideprice": "",
        "overridevalue": null,
        "vatpercentage": '.$vat_percentage.',
        "costpriceoverride": 0,
        "costpriceoverrideprice": 0,
        "orderitemcostprice": 274,
        "productionmaterialcostprice": 2.5,
        "productionmaterialnetprice": 5,
        "productionmaterialnetpricewithdiscount": 5,
        "overridepricevalue": 5,
        "getpricegroupprice": 0,
        "rulescostpricecomesfrom": '.$rules_cost_price_comes_from.',
        "rulesnetpricecomesfrom": '.$rules_net_price_comes_from.',
        "fabricfieldtype": 5,
        "widthfieldtypeid": 11,
        "dropfieldtypeid": 12,
        "colorid": '.$colorid.',
        "priceapicount": 0,
        "reportpriceresults": [
            {
                "optionid": 2566,
                "unitcost": "2.50",
                "reportprice": "2.50"
            }
        ],
        "fabricid": '.$fabricid.',
        "orderid": "",
        "customerid": "",
        "fabriciddual": "",
        "coloriddual": "",
        "subfabricid": "",
        "subcolorid": "",
        "pricegroupdual": ""
    }';

    $price_response = CallAPI_v4("POST",'orderitems/calculate/option/price/',$data,true);

     

    $price_arr = [];
    $price_arr['finalcostprice']= $price_response->finalcostprice;
    $price_arr['finalnetprice']= $price_response->finalnetprice;
    $price_arr['fullpriceobject']= $price_response->fullpriceobject;
    $price_arr['price_html']= wc_price($price_response->fullpriceobject->grossprice);
    $price_arr['success'] ='true';

    echo wp_json_encode($price_arr);
    exit;
}


/**
 *
 * single product add to cart funtion
 * 
 * 
 */
add_action( 'wp_ajax_nopriv_add_to_cart', 'add_to_cart' );
add_action( 'wp_ajax_add_to_cart', 'add_to_cart' );
function add_to_cart(){
    $form_data = isset($_POST['form_data']) ? $_POST['form_data']:array();
    $form_data = wp_parse_args($form_data);
    $blindmatrix_v4_parameters_data = isset($form_data['blindmatrix_v4_parameters_data']) ? $form_data['blindmatrix_v4_parameters_data']:array();

    if(!is_array($blindmatrix_v4_parameters_data) || empty($blindmatrix_v4_parameters_data)){
        return;
    }
    
	$cart_item_data['new_product_image_path'] = '';
	$product_id  = 4803;
	$quantity    = isset($blindmatrix_v4_parameters_data['qty']) ? $blindmatrix_v4_parameters_data['qty']:'';;
    
	$cart = WC()->cart->add_to_cart( $product_id, $quantity,0,array(),array('blindmatrix_v4_parameters_data'=> $blindmatrix_v4_parameters_data));
	$html = [];  
    if($cart !='' ){
        $html['success'] ='true';
        $html['cartitem'] ='product added';
    }
    echo wp_json_encode($html);
    exit;
}


/**
 *
 * update single product details on cart
 * 
 * 
 */
add_action('woocommerce_before_calculate_totals', 'update_cart_data');

function update_cart_data($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Loop through cart items
    foreach( $cart->get_cart() as $cart_value ) {		
        if(!isset($cart_value['blindmatrix_v4_parameters_data'])){
            continue;
        }
        
        $blindmatrix_v4_parameters_data = $cart_value['blindmatrix_v4_parameters_data'];
        if( isset( $blindmatrix_v4_parameters_data['grossprice'] ) ) {				
            $price = $blindmatrix_v4_parameters_data['grossprice'];
            $cart_value['data']->set_price($price);
        }
        if( isset( $blindmatrix_v4_parameters_data['fabric_and_color_name'] ) ) {
            $name = $blindmatrix_v4_parameters_data['fabric_and_color_name'].' '.$blindmatrix_v4_parameters_data['productname'];
            $cart_value['data']->set_name($name);				
        }
        $cart_value['data']->set_weight( 100 );
    }
}

add_filter('woocommerce_get_item_data', function($item_data, $cart_item){
    $blindmatrix_v4_parameters_data = isset($cart_item['blindmatrix_v4_parameters_data']) ? $cart_item['blindmatrix_v4_parameters_data']:array();
    if(empty($blindmatrix_v4_parameters_data) || !is_array($blindmatrix_v4_parameters_data)){
        return $item_data;
    }
    
    $parameters = array();
    // Unit Type
    $unit_type   = isset($blindmatrix_v4_parameters_data['unit'])? $blindmatrix_v4_parameters_data['unit']:'';
    
    // Width
    $width_data  = isset($blindmatrix_v4_parameters_data[11])? array_values($blindmatrix_v4_parameters_data[11]):'-';
    $parameters[] = array(
        'key'   => isset($width_data[0]['label']) ? $width_data[0]['label']:'-',     
        'value' => isset($width_data[0]['value']) ? $width_data[0]['value'].' '.$unit_type:'-'
    );
    
    // Drop
    $drop_data  = isset($blindmatrix_v4_parameters_data[12])? array_values($blindmatrix_v4_parameters_data[12]):'-';
    $parameters[] = array(
        'key'   => isset($drop_data[0]['label']) ? $drop_data[0]['label']:'-',     
        'value' => isset($drop_data[0]['value']) ? $drop_data[0]['value'].' '.$unit_type:'-'
    );
    // Text
    $text_values  = isset($blindmatrix_v4_parameters_data[18])? array_values($blindmatrix_v4_parameters_data[18]):'';
    if(!empty($text_values) && is_array($text_values)){
        foreach($text_values as $key => $value){
            $parameters[] = array(
                'key'   => isset($value['label']) ? $value['label']:'-',     
                'value' => isset($value['value']) ? $value['value']:'-'
             );
         }
    }
    // Component & Drop down
    $comp_values  = isset($blindmatrix_v4_parameters_data[3])? array_values($blindmatrix_v4_parameters_data[3]):'';
    if(!empty($comp_values) && is_array($comp_values)){
        foreach($comp_values as $key => $value){
            $parameters[] = array(
                'key'   => isset($value['label']) ? $value['label']:'-',     
                'value' => isset($value['value']) ? $value['value']:'-'
             );
         }
    }
    
    foreach($parameters as $parameter){
        $item_data[] = array(
		    'key'   => $parameter['key'],
		    'value' => $parameter['value']
		);
    } 
    
    return $item_data;
}, 999, 2 );	

