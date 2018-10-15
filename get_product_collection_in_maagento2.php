<?php

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

$collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->load();

foreach ($collection as $product){
     echo 'Name  =  '.$product->getName().'<br>';
}  

?>
