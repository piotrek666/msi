<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Indexer\StockItem;

use Magento\Framework\App\ResourceConnection;
use Magento\Inventory\Model\ResourceModel\Source as SourceResourceModel;
use Magento\Inventory\Model\ResourceModel\SourceItem as SourceItemResourceModel;
use Magento\Inventory\Model\ResourceModel\StockSourceLink as StockSourceLinkResourceModel;
use Magento\Inventory\Model\Source;
use Magento\Inventory\Model\StockSourceLink;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Index data provider
 */
class IndexDataProvider
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int $stockId
     * @param array $sourceIds
     * @return \ArrayIterator
     */
    public function getData(int $stockId, array $sourceIds = []): \ArrayIterator
    {
        $connection = $this->resourceConnection->getConnection();
        $sourceTable = $connection->getTableName(SourceResourceModel::TABLE_NAME_SOURCE);
        $sourceItemTable = $connection->getTableName(SourceItemResourceModel::TABLE_NAME_SOURCE_ITEM);
        $sourceStockLinkTable = $connection->getTableName(StockSourceLinkResourceModel::TABLE_NAME_STOCK_SOURCE_LINK);

        // find all enabled sources
        $select = $connection->select()
            ->from($sourceTable, [SourceInterface::SOURCE_ID])
            ->where(SourceInterface::ENABLED . ' = ?', 1);
        if (count($sourceIds)) {
            $select->where(Source::SOURCE_ID . ' IN (?)', $sourceIds);
        }
        $sourceIds = $connection->fetchCol($select);

        if (0 === count($sourceIds)) {
            return new \ArrayIterator([]);
        }

        $select = $connection
            ->select()
            ->from(
                ['source_item' => $sourceItemTable],
                [
                    SourceItemInterface::SKU,
                    SourceItemInterface::QUANTITY => 'SUM(' . SourceItemInterface::QUANTITY . ')',
                ]
            )
            ->joinLeft(
                ['stock_source_link' => $sourceStockLinkTable],
                'source_item.' . SourceItemInterface::SOURCE_ID . ' = stock_source_link.' . StockSourceLink::SOURCE_ID,
                []
            )
            ->where('source_item.' . SourceItemInterface::STATUS . ' = ?', SourceItemInterface::STATUS_IN_STOCK)
            ->where('stock_source_link.' . StockSourceLink::STOCK_ID . ' = ?', $stockId)
            ->where('stock_source_link.' . StockSourceLink::SOURCE_ID . ' IN (?)', $sourceIds)
            ->group([SourceItemInterface::SKU]);

        return new \ArrayIterator($connection->fetchAll($select));
    }
}
