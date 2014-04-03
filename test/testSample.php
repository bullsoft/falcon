<?php
/* testSample.php --- 
 * 
 * Filename: testSample.php
 * Description: 
 * Author: Gu Weigang
 * Maintainer: 
 * Created: Wed Jul 24 21:37:13 2013 (+0800)
 * Version: master
 * Last-Updated: Thu Feb 13 14:29:55 2014 (+0800)
 *           By: Gu Weigang
 *     Update #: 76
 * 
 */

/* Change Log:
 * 
 * 
 */

/* This program is part of "Baidu Darwin PHP Software"; you can redistribute it and/or
 * modify it under the terms of the Baidu General Private License as
 * published by Baidu Campus.
 * 
 * You should have received a copy of the Baidu General Private License
 * along with this program; see the file COPYING. If not, write to
 * the Baidu Campus NO.10 Shangdi 10th Street Haidian District, Beijing The Peaple's
 * Republic of China, 100085.
 */

/* Code: */

require_once __DIR__ . "/bootstrap/cli.php";
registerTask('sample');

use BullSoft\Cart;

    
//create toothbrush
$itemA = new Cart\Item();
$itemA->setId(1)
      ->setName('ToothBrush')
      ->setSku('toothbrush-1')
      ->setPrice('1.99')
      ->setQty(3)
      ->setIsTaxable(true)
      ->setIsDiscountable(true)
      ;

//create toothpaste
$itemB = new Cart\Item();
$itemB->setId(2)
      ->setName('ToothPaste')
      ->setSku('toothpaste-2')
      ->setPrice('2.99')
      ->setQty(1)
      ->setIsTaxable(true)
      ->setIsDiscountable(true)
      ;
$itemC = new Cart\Item();
$itemC->setId(3)
      ->setName('Anniversary Present')
      ->setSku('present-1')
      ->setPrice('99.99')
      ->setQty(1)
      ->setIsTaxable(true)
      ->setIsDiscountable(true)
      ;

//create a shipment
$shipmentA = new Cart\Shipment();
$shipmentA->setId(1)
          ->setVendor('ups')
          ->setMethod('ground')
          ->setPrice('6.95')
          ->setIsDiscountable(true)
          ->setIsTaxable(true)
          ;

$cart = new Cart\Cart();
$cart->setItem($itemA)
     ->setItem($itemB)
     ->setItem($itemC)
     ->setShipment($shipmentA)
     ->setIncludeTax(true)
     ->setTaxRate('0.07025')
     ->setId(3)
     ;

//set up a single discount condition, for the shipping method
$condition1 = new Cart\DiscountCondition();
$condition1->setName('Shipping: code = ups_ground')
           ->setCompareType(Cart\DiscountCondition::$compareEquals)
           ->setCompareValue('ups_ground')
           ->setSourceEntityType('shipments')
           ->setSourceEntityField('code')
           ;

//discount conditions are wrapped by a condition compare object
//compare objects are intended for creating trees of conditionals
$compare1 = new Cart\DiscountConditionCompare();
$compare1->setOp('or') // doing a linear 'or' (not left-right) since we only have 1 condition
         ->setSourceEntityType('shipments')
         ->addCondition($condition1)
         ;

//set up a single discount condition, for the item sku
$condition2 = new Cart\DiscountCondition();
$condition2->setName('Item: sku = toothpaste-2')
           ->setSourceEntityType('items')
           ->setSourceEntityField('sku')
           ->setCompareType(Cart\DiscountCondition::$compareEquals)
           ->setCompareValue('toothpaste-2')
           ;

$condition3 = new Cart\DiscountCondition();
$condition3->setName('Item: qty >= 2')
           ->setSourceEntityType('items')
           ->setSourceEntityField('qty')
           ->setCompareType(Cart\DiscountCondition::$compareGreaterThanEquals)
           ->setCompareValue('2')
           ;

//set up a single discount condition, for the item sku
$condition4 = new Cart\DiscountCondition();
$condition4->setName('Item: sku = toothbrush-1')
           ->setSourceEntityType('items')
           ->setSourceEntityField('sku')
           ->setCompareType(Cart\DiscountCondition::$compareEquals)
           ->setCompareValue('toothbrush-1')
           ;

//create 'container' for conditions
$compare2 = new Cart\DiscountConditionCompare();
$compare2->setOp('or') // doing a linear 'or' (not left-right) since we only have 1 condition
         ->setSourceEntityType('items')
         ->addCondition($condition2)
         ;

//create 'container' for conditions
$compare3 = new Cart\DiscountConditionCompare();
$compare3->setOp('and') // doing a linear 'and'
         ->setSourceEntityType('items')
         ->addCondition($condition3)
         ->addCondition($condition4)
         ;

//create the discount, but don't add the discount unless the conditions are met
//in this example, there is only a target criteria; no pre-requisite criteria
$discountA = new Cart\Discount();
$discountA->setId(1)
          ->setName('Free UPS Ground')
          ->setValue('1.00')
          ->setAs(Cart\Discount::$asPercent)
          ->setIsPreTax(true)
          ->setTo(Cart\Discount::$toSpecified) //not _all_ shipments
          ->setTargetConditionCompare($compare1) //only target conditions, no pre-conditions
          ;

// Buy 2 ToothBrush, Get 1 ToothPaste free
$discountB = new Cart\Discount();
$discountB->setId(2)
          ->setName('Buy 2 ToothBrush, Get 1 ToothPaste free')
          ->setTo(Cart\Discount::$toSpecified)
          ->setValue('1.00')
          ->setMaxQty(1)
          ->setIsMaxPerItem(false)
          ->setAs(Cart\Discount::$asPercent)
          ->setIsPreTax(true)
          ->setPreConditionCompare($compare3)
          ->setTargetConditionCompare($compare2)
          ;
          
//apply the automatic discount, if pre-conditions validate
if ($compare1->isValid($shipmentA)) {
    $discountA->setShipment($shipmentA);
    $cart->setDiscount($discountA);
}

//check pre-conditions and target conditions
if ($compare3->isValid($itemA) && $compare2->isValid($itemB)) {
    $discountB->setItem($itemB);
    $cart->setDiscount($discountB);
}


echo print_r($cart->getTotals(), 1);
echo "==============" . PHP_EOL;
echo print_r($cart->getDiscountedTotals(), 1);
echo print_r($cart->getDiscountGrid());

$cart2 = new Cart\Cart();
$cart2->importJson($cart->toJson());

echo "\n{$cart2}\n";
echo print_r($cart2->getTotals(), 1);
echo print_r($cart2->getDiscountedTotals(), 1);

/**
 * @backupGlobals disabled
 */
class testSample extends PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $expected = true;
        $actual   = false;
        $this->assertEquals($expected, $actual);        
    }
}


/* testSample.php ends here */
