<?php

namespace Fisheyehq\Pdfinvoicetext\Model\Plugin;

use Fisheyehq\Pdfinvoicetext\Helper\Data;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Psr\Log\LoggerInterface;


/**
 * Class PdfInvoice
 * @package Fisheyehq\Pdfinvoicetext\Model\Plugin
 */
class PdfInvoice
{

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var Invoice
     */
    protected $_logger;


    /**
     * PdfInvoice constructor.
     * @param Data $helper
     * @param LoggerInterface $logger
     */
    public function __construct(Data $helper, LoggerInterface $logger)
    {
        $this->_helper = $helper;
        $this->_logger = $logger;

    }


    /**
     * @param Invoice $subject
     * @param $result
     * @return mixed
     */
    public function afterGetPdf(\Magento\Sales\Model\Order\Pdf\Invoice $subject, \Zend_Pdf $result)
    {
        $page = end($result->pages);

        try {
            // draw pdf footer
            $this->_helper->drawFooter($subject, $page);
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }

        return $result;
    }
}