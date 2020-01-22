<?php
define('DS', DIRECTORY_SEPARATOR); 
use \Magento\Framework\App\Bootstrap;

require __DIR__ .'/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$app_state = $objectManager->get('\Magento\Framework\App\State');
$app_state->setAreaCode('frontend');

$productsData = getProducts();
importSimpleProducts( $productsData , $objectManager );

function getProducts()
{
    $file = 'csv/allsimplesku.csv';
    $arrResult = array();
    $headers = false;
    $handle = fopen($file, "r");
    if (empty($handle) === false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (!$headers) {
                $headers[] = $data;
            } else {
                $arrResult[] = $data;
            }
        }
        fclose($handle);
    }
    return $arrResult;
}

/*
 * Import Simple Products
 */
function importSimpleProducts( $importProducts, $objectManager ) {

    // Path to media folder        
    $filesystem = $objectManager->create('Magento\Framework\Filesystem');
    $mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    $mediaPath = $mediaDirectory->getAbsolutePath();

    foreach( $importProducts as $importProduct ) {

        try {  

            $product = $objectManager->create('\Magento\Catalog\Api\ProductRepositoryInterface')->get($importProduct[0]);
            $product->setSku($importProduct[1]);
            //$product->setSku($importProduct[2]);
			$product->save();
            //echo "SKU has been changed :: " . $product->getId()."\n";
        }
    catch(Exception $e)
    {
        echo 'Something failed for product import ' . $importProduct[1] . PHP_EOL;
        //print_r($e);
    }
}   
}