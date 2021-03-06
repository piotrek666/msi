<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventoryApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\InventoryApi\Api\Data\SourceItemExtensionInterface;

/**
 * Represents amount of product on physical storage
 * Entity id getter is missed because entity identifies by compound identifier (sku and source_id)
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface SourceItemInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const SKU = 'sku';
    const SOURCE_ID = 'source_id';
    const QUANTITY = 'quantity';
    const STATUS = 'status';
    /**#@-*/

    /**#@+
     * Source items status values
     */
    const STATUS_OUT_OF_STOCK = 0;
    const STATUS_IN_STOCK = 1;
    /**#@-*/

    /**
     * Get source item sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set source item sku
     *
     * @param string $sku
     * @return void
     */
    public function setSku($sku);

    /**
     * Get source id
     *
     * @return int
     */
    public function getSourceId();

    /**
     * Set source id
     *
     * @param int $sourceId
     * @return void
     */
    public function setSourceId($sourceId);

    /**
     * Get source item quantity
     *
     * @return float
     */
    public function getQuantity();

    /**
     * Set source item quantity
     *
     * @param float $quantity
     * @return void
     */
    public function setQuantity($quantity);

    /**
     * Get source item status (One of self::STATUS_*)
     *
     * @return int
     */
    public function getStatus();

    /**
     * Set source item status (One of self::STATUS_*)
     *
     * @param int $status
     * @return int
     */
    public function setStatus($status);

    /**
     * Retrieve existing extension attributes object
     *
     * @return \Magento\InventoryApi\Api\Data\SourceItemExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Magento\InventoryApi\Api\Data\SourceItemExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(SourceItemExtensionInterface $extensionAttributes);
}
