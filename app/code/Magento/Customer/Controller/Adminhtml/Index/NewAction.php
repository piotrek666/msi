<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Controller\Adminhtml\Index;

/**
 * Class \Magento\Customer\Controller\Adminhtml\Index\NewAction
 *
 */
class NewAction extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Create new customer action
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('edit');
        return $resultForward;
    }
}
