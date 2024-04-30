<?php
    $get_v4_productlists = get_option('v4_productlistdata', true)->result->EcomProductlist;
	global $v4_product_page;
	
	?>
	<!-- <pre><?php // print_r($get_v4_productlists); ?> </pre> -->
	<?php

    $blindswithfabric = [];
    $blindswithslates = [];
	$image_file_path ='https://curtainmatrix.co.uk/ecommercesource/api/public';
    foreach($get_v4_productlists as $productss){
        if( 3 == $productss->pi_category){
            $blindswithfabric['productname'][$productss->pei_productid] = $productss->pei_ecomProductName;
            $blindswithfabric['productdescription'][$productss->pei_productid] = $productss->pi_productdescription;
            $pi_deafultimages = json_decode($productss->pi_productimage, true);
			$pi_deafultimage = isset($pi_deafultimages[0]) ? $pi_deafultimages[0]:'';
			if(!empty($pi_deafultimage)){
				$pi_deafultimage = $image_file_path.$pi_deafultimage;
			}
            $blindswithfabric['menuimage'][$productss->pei_productid] = $pi_deafultimage;
        }

        if( 4 ==  $productss->pi_category){
            $blindswithslates['productname'][$productss->pei_productid] = $productss->pei_ecomProductName;
            $blindswithslates['productdescription'][$productss->pei_productid] = $productss->pi_productdescription;
            $pi_deafultimages = json_decode($productss->pi_productimage, true);
			$pi_deafultimage = isset($pi_deafultimages[0]) ? $pi_deafultimages[0]:'';
			if(!empty($pi_deafultimage)){
				$pi_deafultimage = $image_file_path.$pi_deafultimage;
			}
            $blindswithslates['menuimage'][$productss->pei_productid] = $pi_deafultimage;
        }
    }

?>
<li id="menu-item-111" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-103 <?php if(is_front_page()): ?>current_page_item active<?php endif; ?>">
	<a href="<?php bloginfo('url'); ?>" class="nav-top-link">
		<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/house.png" class="menu-image menu-image-title-after" alt="">
		<span class="menu-image-title-after menu-image-title">Home</span>
	</a>
</li>
	
	<li id="menu-item-111" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children menu-item-1111 has-dropdown">
		<a href="javascript:;" class="nav-top-link">
			<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/shopping-bag.png" class="menu-image menu-image-title-after " alt="">
			<span class="menu-image-title-after menu-image-title">Blinds with fabrics</span>
			<i class="icon-angle-down"></i>
		</a>
		<ul class="sub-menu nav-dropdown nav-dropdown-simple blinds nav-dropdown-full" >
			<li  style="flex: 0 1 25%; min-width: 25%;overflow-y: auto;height: 310px;" class="menuscrollbarbz menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col">
				<ul class="sub-menu nav-column nav-dropdown-simple productsubmenu">
					<div class="productsubclass">
                    <?php foreach( $blindswithfabric['productname'] as $key => $productname){ 
						 $feildscategoryname  =  'blinds-with-fabric';
						 $product_slug =str_replace(" ","-",strtolower($productname));
						$list_page_url= '/'.$v4_product_page.'/'.$feildscategoryname.'/'.$product_slug;
						?>
                    <li href="#"  style="display:flex; padding: 5px 15px;" id="id-<?php echo $key?>" data-productID="<?php echo $key; ?> " data-des="<?php echo !empty($blindswithfabric['productdescription'][$key])?$blindswithfabric['productdescription'][$key]:'';?>" data-productname="<?php echo $productname; ?>" data-img="<?php echo $blindswithfabric['menuimage'][$key];?>"> <img style="menu-image menu-image-title-after icon-test"  src="https://ecommerce-v4.blindssoftware.com/wp-content/uploads/2022/05/Measure-1-1.png"><a href="<?php echo($list_page_url);?>"> <span class="menu-image-title-after menu-image-title" > <?php echo $productname; ?> </span> </a></li>
                <?php } ?>
						
					</div>
				</ul>
			</li>
			<li style="flex: 0 1 75%; min-width: 75%;" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col">
				<ul class="sub-menu nav-column nav-dropdown-simple" style="display: flex;">
				<div style="padding-left: 5px; padding-right: 5px;width:60%;" class="blind-col blind-col-12">
               <?php
                $firstKey = key( $blindswithfabric['productdescription']); 
                $firstdec = $blindswithfabric['productdescription'][$firstKey]; 
                $firstnameKey = key( $blindswithfabric['productname']); 
                $firstname = $blindswithfabric['productname'][$firstnameKey];

                ?>
					 <h3 class="blind-typography title " font-size="normal"><?php echo($firstname); ?></h3>
					 <div class="blind-typography paragraph normal" font-size="normal"><?php echo($firstdec); ?></div>


				  </div>
				  <div style="padding-left: 5px; padding-right: 5px;width:40%;" class="blind-col blind-col-12">
					 <div class="image-desc-container">
                     <?php 
              $firstKey = key($blindswithfabric['menuimage']); 
              $firstimg = $blindswithfabric['menuimage'][$firstKey]; 
            ?>
						<div class="background-image " style="background-image: url(&quot;<?php echo($firstimg)?>&quot); background-position: right top; background-size: contain ;background-position: center center; background-repeat: no-repeat; background-color: inherit;"><img class="product_img_menu" style="max-width: 300px;visibility: hidden;" src="<?php echo($firstimg)?>"></div>
					 </div>
				  </div>
				</ul>
			</li>
		</ul>
	</li>

    <li id="menu-item-111" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children menu-item-1111 has-dropdown">
		<a href="javascript:;" class="nav-top-link">
			<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/shopping-bag.png" class="menu-image menu-image-title-after " alt="">
			<span class="menu-image-title-after menu-image-title">Blinds with slates</span>
			<i class="icon-angle-down"></i>
		</a>
		<ul class="sub-menu nav-dropdown nav-dropdown-simple blinds nav-dropdown-full" >
			<li  style="flex: 0 1 25%; min-width: 25%;overflow-y: auto;height: 310px;" class="menuscrollbarbz menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col">
				<ul class="sub-menu nav-column nav-dropdown-simple productsubmenu">
					<div class="productsubclass">
                    <?php foreach( $blindswithslates['productname'] as $key => $productname){ 
						$feildscategoryname  =  'blinds-with-slats';
						$product_slug =str_replace(" ","-",strtolower($productname));
					   $list_page_url= '/'.$v4_product_page.'/'.$feildscategoryname.'/'.$product_slug;
					   ?>
						
                    <li href="#"  style="display:flex; padding: 5px 15px;" id="id-<?php echo $key?>" data-productID="<?php echo $key; ?> " data-des="<?php echo !empty($blindswithslates['productdescription'][$key])?$blindswithslates['productdescription'][$key]:'';?>" data-productname="<?php echo $productname; ?>" data-img="<?php echo $blindswithslates['menuimage'][$key];?>"> <img  style="menu-image menu-image-title-after icon-test" src="https://ecommerce-v4.blindssoftware.com/wp-content/uploads/2022/05/Measure-1-1.png"><a  href="<?php echo($list_page_url);?>"><span class="menu-image-title-after menu-image-title" ><?php echo $productname; ?></span></a> </li>
                <?php } ?>
						
					</div>
				</ul>
			</li>
			<li style="flex: 0 1 75%; min-width: 75%;" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col">
				<ul class="sub-menu nav-column nav-dropdown-simple" style="display: flex;">
				<div style="padding-left: 5px; padding-right: 5px;width:60%;" class="blind-col blind-col-12">
               <?php
                $firstKey = key( $blindswithslates['productdescription']); 
                $firstdec = $blindswithslates['productdescription'][$firstKey]; 
                $firstnameKey = key( $blindswithslates['productname']); 
                $firstname = $blindswithslates['productname'][$firstnameKey];

                ?>
					 <h3 class="blind-typography title " font-size="normal"><?php echo($firstname); ?></h3>
					 <div class="blind-typography paragraph normal" font-size="normal"><?php echo($firstdec); ?></div>


				  </div>
				  <div style="padding-left: 5px; padding-right: 5px;width:40%;" class="blind-col blind-col-12">
					 <div class="image-desc-container">
                     <?php 
              $firstKey = key($blindswithslates['menuimage']); 
              $firstimg = $blindswithslates['menuimage'][$firstKey]; 
            ?>
						<div class="background-image " style="background-image: url(&quot;<?php echo($firstimg)?>&quot); background-position: right top; background-size: contain ;background-position: center center; background-repeat: no-repeat; background-color: inherit;"><img class="product_img_menu" style="max-width: 300px;visibility: hidden;" src="<?php echo($firstimg)?>"></div>
					 </div>
				  </div>
				</ul>
			</li>
		</ul>
	</li>
    

<?php 

?>
<script>
jQuery(".blinds.nav-dropdown-full .productsubmenu .productsubclass li").mouseover(function() {
	
	var des = jQuery(this).attr("data-des");
	var productname =jQuery(this).attr("data-productname");
	var img =  jQuery(this).attr("data-img");
	var url = "\"" + img + "\"";
	jQuery(this).parents('.blinds').find(".blind-typography.title" ).text(productname);
	jQuery(this).parents('.blinds').find(".blind-typography.paragraph" ).text(des);
	jQuery(this).parents('.blinds').find(".background-image" ).css("background-image", 'url(' + url + ')');
	jQuery(this).parents('.blinds').find(".product_img_menu" ).attr("src",img );
});

</script>
<style>
.productsubclass li:hover {
  background-color:#00c2ff40 !important; 

}
.productsubclass li a {
    background-color:transparent !important;
    width:100% ;
    padding: 10px 1px;
}

.background-image{
    height:300px !important;
}

</style>
