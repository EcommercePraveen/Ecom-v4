<?php
function validateImage($imageUrl) {
  $headers = @get_headers($imageUrl);
  if (empty($headers) || preg_match('/404 Not Found/', implode('', $headers))) {
    return false;
  }
  return true;
}
add_action( 'wp_ajax_flatsome_ajax_search_products', 'ajax_search_products_v4', 1 );
add_action( 'wp_ajax_nopriv_flatsome_ajax_search_products', 'ajax_search_products_v4', 1 );

function ajax_search_products_v4(){
$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';
	if($action == 'flatsome_ajax_search_products'){
		global $v4_product_visualizer_page;
		$get_productlist = get_option('productlist', true);
		
		$productlistdata = get_option('v4_productlistdata');
		$data = $productlistdata->result; 
		$groupedByCategory = [];
		
		foreach ($data->EcomProductlist as $product) {
		  $categoryId = $product->pi_category;
		  $productId = $product->pei_productid;
		  if (!isset($groupedByCategory[$categoryId])) {
			$groupedByCategory[$categoryId] = [];
		  }
		  $groupedByCategory[$categoryId][] = $productId;
		}
		
		$groupedjson= json_encode($groupedByCategory);
		
		$mode ="searchproduct";
		$data=array('searchtext'=>$_REQUEST['query'],'product_data'=>$groupedByCategory);
		$response =CallAPI_v4("POST",$mode,json_encode($data));
	
		$fabric_list = $response->result->Searchlist;
	
	
		$product = array();
		if(count($fabric_list) > 0){
				
			$images=[];
			foreach($fabric_list as $key=>$searchval){
				$productname = $searchval->productname;
				$productname_slug =str_replace(" ","-",strtolower($productname));
				if($searchval->category == 3){
					$categoryname = "blinds-with-fabric";
					$fabriccolorname = $searchval->fabricname." ".$searchval->colorname;
					$fabricname_slug =str_replace(" ","-",strtolower($fabriccolorname));
					$fabricid = $searchval->fd_id;
					$color_id = $searchval->cd_id;
					$groupid = $searchval->groupid;
					$supplierid = $searchval->supplierid;
					$mapid = $searchval->mapid;
					$productviewurl = get_bloginfo('url').'/'.$v4_product_visualizer_page.'/'.$categoryname.'/'.$productname_slug.'/'.$fabricname_slug.'/'.$fabricid.'/'.$color_id.'/'.$mapid.'/'.$groupid.'/'.$supplierid;
				}elseif($searchval->category == 4){
					$categoryname = "blinds-with-slats";
					$fabriccolorname = $searchval->colorname;
					$color_id = $searchval->cd_id;
					$groupid = $searchval->groupid;
					$supplierid = $searchval->supplierid;
					$mapid = $searchval->mapid;
					$fabricname_slug =str_replace(" ","-",strtolower($fabriccolorname));
					$productviewurl = get_bloginfo('url').'/'.$v4_product_visualizer_page.'/'.$categoryname.'/'.$productname_slug.'/'.$fabricname_slug.'/'.$color_id.'/'.$mapid.'/'.$groupid.'/'.$supplierid;
				
				}
				$images[] = $searchval->colorimage;
				if($searchval->colorimage != "" && strpos($searchval->colorimage, ".") !== false ){
					$product_image = "https://curtainmatrix.co.uk/ecommercesource/api/public/storage/attachments/ECOMMERCELATEST/material/colour/".$searchval->colorimage;
				}else{
					 $product_image =  plugin_dir_url( __FILE__ ).'/Shortcode-Source/images/no-image.jpg';
				}

			   
				$product['blind_'.$key]['name'] = $productname.' '.$fabriccolorname;
				$product['blind_'.$key]['url'] = $productviewurl;
				$product['blind_'.$key]['img'] = $product_image;
				$product['blind_'.$key]['price'] = $searchval->minprice;
			}
			
		}
		
			$searcharrfilter = array_values($product);
		
		if(count($searcharrfilter) > 0){
			$searchresult=array();
			foreach($searcharrfilter as $keyss=>$searchval){
					$searchresult['type'] = 'Product';
					$searchresult['id'] = $keyss;
					$searchresult['value'] = $searchval['name'];
					$searchresult['url'] = $searchval['url'];
					$searchresult['img'] = $searchval['img'];
					$searchresult['price'] = $searchval['price'];
				
				$searchresultlist[] = $searchresult;
			}
			$return['suggestions'] = $searchresultlist;
		}else{
			$return['suggestions'] = array(
			[
				'id'    => -1,
				'value' => 'No products found.',
				'url'   => ''
			]
			);
		}

		wp_send_json($return);
		wp_die( '0' );
	}
}
?>