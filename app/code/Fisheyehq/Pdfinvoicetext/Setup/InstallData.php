<?php

namespace Fisheyehq\Pdfinvoicetext\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


/**
 * Class InstallData
 * @package Fisheyehq\Pdfinvoicetext\Setup
 */
class InstallData implements InstallDataInterface
{

    /**
     * @var \Magento\Cms\Model\Block
     */
    protected $_blockModel;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Block\CollectionFactory
     */
    protected $_blockFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;


    /**
     * InstallData constructor.
     * @param \Magento\Cms\Model\Block $blockModel
     * @param \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $blockFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Cms\Model\Block $blockModel,
        \Magento\Cms\Model\ResourceModel\Block\CollectionFactory $blockFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_blockModel = $blockModel;
        $this->_blockFactory = $blockFactory;
        $this->_logger = $logger;
    }


    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $content = 'Thank you for buying from us';
        $identifier = 'pdf_invoice_text';
        $title = 'Pdf Invoice text';

        try {

            // get existed static block with identifier
            $cmsBlock = $this->_blockFactory->create()
                ->addFieldToFilter('identifier', array('eq' => $identifier))->getFirstItem();

            // get existed static block or create new if not exist
            if ($cmsBlock->getId()) {
                $model = $this->_blockModel->load($cmsBlock->getId());
            } else {
                $model = $this->_blockModel;
                $model->setIdentifier($identifier);
            }

            // set static block content
            $model->setStores([\Magento\Store\Model\Store::DEFAULT_STORE_ID]);
            $model->setTitle($title);
            $model->setContent($content);
            $model->setIsActive(1);
            $model->save();
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }

    }
}