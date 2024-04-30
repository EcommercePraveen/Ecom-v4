<?php 
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
 
 echo('<pre>');
print_r($groupedjson);
echo('</pre>');
?>