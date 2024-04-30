<?php

$get_v4_productlist = get_option('v4_productlistdata', true);
global $v4_product_page;
// echo'<pre>';
// var_dump($product_list);
// echo'</pre>';

$product_list = json_decode(json_encode($get_v4_productlist->result->EcomProductlist), true);

?>
    <div class="product-container-grid" >
		<h2 class="donotcross">Shop Our Blinds</h2>
        <div class="grid blinds_container" >
            <?php
            foreach ($product_list as $product):
            $product_img_array =json_decode($product["pi_productimage"],true);    
            $product_deafultimage =json_decode($product["pi_deafultimage"],true);    
            $product_id =$product["pei_productid"];    
            $product_category_id =$product["pi_category"];    
            $product_name = $product["pei_ecomProductName"];
            $product_price = $product["minimum_price"];
            $product_slug =str_replace(" ","-",strtolower($product_name));
            $feildscategoryid ='';
            $feildscategoryname ='';
            if( $product_category_id == 3){
                $feildscategoryname  =  'blinds-with-fabric';
            }
            else if($product_category_id == 4){
                $feildscategoryname  =  'blinds-with-slats';
            }
            $list_page_url= '/'.$v4_product_page.'/'.$feildscategoryname.'/'.$product_slug;
			$product_main_img ='';
			$image_file_path ='https://curtainmatrix.co.uk/ecommercesource/api/public';
			foreach( $product_img_array as $product_img){
				if(isset($product_deafultimage['defaultimage']['productdefault']) && $product_deafultimage['defaultimage']['productdefault'] != ""){
					if (strpos($product_img, $product_deafultimage['defaultimage']['productdefault']) !== false) {
					
						$product_main_img = $image_file_path.$product_img;
					}
                    else{
                        $product_main_img = plugin_dir_url( __FILE__ ).'images/no-image.jpg';
                    }
				}
			}
            ?>
                <article class="card-grid__item card-product step-up product type-product ">
                <a href="<?php echo($list_page_url);?>">   
                    <div class="card-product__top" >
                         <div class="blinds_container card-product__hero lazyload loaded" style="background-image: url('<?php echo($product_main_img);?>');" ></div>                        
                        <div class="blinds_container card-product__copy" >
                                <h5 class="blind_productname"><?php echo($product["pei_ecomProductName"]);?></h5>
                            </div>
                    </div>
                    <div class="card-product__meta" >
                    <div> <strong>Price from</strong>
							<span class="woocommerce-Price-amount amount">
								<span class="woocommerce-Price-currencySymbol">£</span>
								<span><?php echo($product_price);?></span>
							</span>
					</div>
                        <div class="shuttertext" >
                            <span style="text-align:center;"> <?php echo !empty($product['pi_productdescription']) ? substr_replace($product['pi_productdescription'], "...", 80):''; ?></span>
                        </div>
                    </div>
                </a>
                </article>	
            <?php endforeach; ?>
        </div>	
    </div>
