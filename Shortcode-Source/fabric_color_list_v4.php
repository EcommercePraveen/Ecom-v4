<?php
global $v4_product_visualizer_page;
$feildscategoryname = get_query_var("feildscategoryname");
$productslug = get_query_var("productname");
$productname =str_replace("-"," ",strtolower($productslug));
if($feildscategoryname == 'blinds-with-fabric'){
	$feildscategoryid =5;
}else{
	$feildscategoryid =20;
}
$get_v4_productlist = get_option('v4_productlistdata', true);
$product_list = json_decode(json_encode($get_v4_productlist->result->EcomProductlist), true);
$id = array_search(strtolower($productname), array_map('strtolower', array_column($product_list, 'pei_ecomProductName')));

$pei_productid = $product_list[$id]['pei_productid'];

// $mode ='fabriclistview/'.$feildscategoryid.'/'.$pei_productid;

// $resultcontact = CallAPI_v4("POST",$mode,json_encode($data));


//  echo'<pre>';
//  print_r($resultcontact);
//  echo'</pre>'; 

$product_title = $product_list[$id]['pei_ecomProductName'];
$product_description = $product_list[$id]['pi_productdescription'];
$ecomFreeSample = $product_list[$id]['pei_ecomFreeSample'];

?>
<input id="product_title" name="product_title" value="<?php echo($product_title); ?>" hidden/>
<input id="ecomFreeSample" name="ecomFreeSample" value="<?php echo($ecomFreeSample); ?>" hidden/>
<input id="feildscategoryid" name="feildscategoryid" value="<?php echo($feildscategoryid); ?>" hidden/>
<input id="pei_productid" name="pei_productid" value="<?php echo($pei_productid); ?>" hidden/>
<input id="productslug" name="productslug" value="<?php echo($productslug); ?>" hidden/>
<input id="feildscategoryname" name="feildscategoryname" value="<?php echo($feildscategoryname); ?>" hidden/>
<div class="row row-small align-center commonfont listpage" >
	<div class="col medium-12 large-12" >
	    <div class="products row row-small" style="margin: auto;" >
		    <div class="box has-hover has-hover box-text-bottom" >
			    <!-- box-image -->
			    <div class="box-text text-center" >
                        <div class="box-text-inner" >
                            <h1 class="uppercase" style="text-transform: capitalize;margin-bottom: 15px;"><?php echo($product_title);?><span class="searchtext"></span></h1>
                            <p class="blindslistdescription"><?php echo($product_description);?></p>
                        </div><!-- box-text-inner -->
                </div><!-- box-text -->
            </div>
        </div>
		<div class="shop-page-title category-page-title page-title category-page-title-container" >
			<div class="page-title-inner flex-row  medium-flex-wrap nws" style="padding-top: 0px;" >
				<div style="width: 17%" class="first-col" data-filter="open" data-click="" >
					<span class="">Filter By<i class="fa fab fa-minus"></i></span>
				</div>
				
				<div class="sce-col" >
					
                            <span class="swatch_thumbnails_container">
                                <label class="switch_label">Fabric View</label>
                                <label class="switch">
                                <input type="checkbox" id="Swatch_Thumbnails">
                                <span class="bm_slider round"></span>
                                </label>
                            </span>
                        <span class="fabriclist_listby_container">
                            <div class="btn-container nws" >
                                <label class="btn-color-mode-switch">
                                    <input type="checkbox" onchange="fabriclist_listby(this);" id="listbypflist" value="1">
                                    <label for="listbypflist" data-on="List by fabric" data-off="View all products" class="btn-color-mode-switch-inner"></label>
                                </label>
                            </div>
                        </span>
                        <div class="woocommerce-ordering hidemobile" >
                            <select name="orderby" class="orderby" onchange="fabriclist_sort(this.value);">
                                <option value="">Default sorting</option>
                                <option value="ASC">Price - Low to High</option>
                                <option value="DESC">Price - High to Low</option>
                                <option value="BESTSELLING">Best Selling</option>
                                <option value="ATOZ">Alphabetical (A to Z)</option>
                            </select>
                        </div>
                        <div class="filtertab-last custabchild nws" >
                            <span class="woocommerce-result-count"></span>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
        <div class="cointainer_product_list" >
			<div class="col-inner mt-half clearfix block_load" style="transition: all 0.3s ease 0s;" >
				<div class="products row large-columns-4 medium-columns-3 small-columns-2" id="row-product-list" style="margin:auto;" >         
               
                </div>
                <nav class="woocommerce-pagination pagination_div">
                    
                  </nav>
			</div>
		</div>    
    </div>
</div>

