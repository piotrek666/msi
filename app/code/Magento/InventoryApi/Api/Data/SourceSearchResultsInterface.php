<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventoryApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Search results of Repository::getList method
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface SourceSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get sources list
     *
     * @return \Magento\InventoryApi\Api\Data\SourceInterface[]
     */
    public function getItems();

    /**
     * Set sources list
     *
     * @param \Magento\InventoryApi\Api\Data\SourceInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
