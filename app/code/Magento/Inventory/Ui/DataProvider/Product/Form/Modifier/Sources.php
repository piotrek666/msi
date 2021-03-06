<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Inventory\Model\ResourceModel\Source as SourceResourceModel;
use Magento\Inventory\Model\ResourceModel\SourceItem\Collection;
use Magento\Inventory\Model\ResourceModel\SourceItem\CollectionFactory;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Product form modifier. Add to form source data
 */
class Sources extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var CollectionFactory
     */
    private $sourceItemCollectionFactory;

    /**
     * Sources constructor
     *
     * @param LocatorInterface $locator
     * @param CollectionFactory $sourceItemCollectionFactory
     */
    public function __construct(
        LocatorInterface $locator,
        CollectionFactory $sourceItemCollectionFactory
    ) {
        $this->locator = $locator;
        $this->sourceItemCollectionFactory = $sourceItemCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();

        $data[$product->getId()]['sources']['assigned_sources'] = $this->getSourceItemsData();
        return $data;
    }

    /**
     * @return array
     */
    private function getSourceItemsData()
    {
        $product = $this->locator->getProduct();

        /** @var Collection $collection */
        $collection = $this->sourceItemCollectionFactory->create();
        $collection->addFilter(SourceItemInterface::SKU, $product->getSku());
        $collection->join(
            ['s' => SourceResourceModel::TABLE_NAME_SOURCE],
            sprintf('s.%s = main_table.%s', SourceInterface::SOURCE_ID, SourceItemInterface::SOURCE_ID),
            ['source_name' => SourceInterface::NAME]
        );

        $sourceItemsData = [];
        foreach ($collection->getData() as $row) {
            $sourceItemsData[] = [
                SourceItemInterface::SOURCE_ID => $row[SourceItemInterface::SOURCE_ID],
                SourceItemInterface::QUANTITY => $row[SourceItemInterface::QUANTITY],
                SourceItemInterface::STATUS  => $row[SourceItemInterface::STATUS],
                SourceInterface::NAME => $row['source_name'],
            ];
        }
        return $sourceItemsData;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
