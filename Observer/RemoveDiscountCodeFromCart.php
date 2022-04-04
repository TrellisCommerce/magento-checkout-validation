<?php

namespace Trellis\CheckoutValidation\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Trellis\CheckoutValidation\Helper\Data as Helper;

class RemoveDiscountCodeFromCart implements ObserverInterface
{
    /**
     * @var Helper
     */
    protected $_helper;

    /**
     * RemoveDiscountCodeFromCart constructor.
     *
     * @param Helper $helper
     */
    public function __construct(
        Helper $helper
    ) {
        $this->_helper = $helper;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getLayout();

        /** @var \Magento\Framework\View\Element\Template $block */
        $block = $layout->getBlock('checkout.cart.coupon');
        if ($block && $this->_helper->removeDiscountCode()) {
            if ($block->getNameInLayout() == 'checkout.cart.coupon') {
                $block->setTemplate(false);
            }
        }
    }
}
