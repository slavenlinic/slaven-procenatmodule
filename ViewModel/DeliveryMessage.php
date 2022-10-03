<?php

namespace Slaven\ProcenatModule\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;


class DeliveryMessage implements ArgumentInterface
{
    public function getMessage($product): string
    {
//        $myName = $product->getName();

        $maxpercent=0;

        if ($product->gettypeid()=='configurable')
        {
            $_children = $product->getTypeInstance()->getUsedProducts($product);
            foreach ($_children as $child){
                $finalPrice = $child->getFinalPrice();
                $regularPrice = $child->getPrice();
                if  ($regularPrice>0)
                      $maxpercent = max((100 - round(100 * $finalPrice / $regularPrice, 2)),$maxpercent);

            }
        }
        elseif ($product->gettypeid()=='simple')
        {
            $finalPrice = $product->getPriceInfo()->getPrice('final_price')->getValue();
            $regularPrice = $product->getPriceInfo()->getPrice('regular_price')->getValue();
            if ($regularPrice>0) $maxpercent = 100-round(100*$finalPrice/$regularPrice,2);
        }

        return $product->getTypeId().' : '.strval($maxpercent);
    }
}
