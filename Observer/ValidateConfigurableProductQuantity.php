<?php

namespace Trellis\CheckoutValidation\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Trellis\CheckoutValidation\Helper\Quote as Helper;

class ValidateConfigurableProductQuantity implements ObserverInterface
{
    /** @var Helper $helper */
    protected $helper;

    /** @var ManagerInterface */
    protected $messageManager;

    /**
     * ValidateConfigurableProductMnQuantity constructor.
     *
     * @param Helper           $helper
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Helper $helper,
        ManagerInterface $messageManager
    ) {
        $this->helper = $helper;
        $this->messageManager = $messageManager;
    }

    /**
     * Validates all product quantities before going to checkout page
     *
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->getConfig(Helper::PATH_CART_LIMIT_CART_ENABLED)) {
            try {
                $this->helper->validateCart();
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $controller = $observer->getEvent()->getData('controller_action');
                $url = $this->helper->getUrl('checkout/cart');
                $response = $controller->getResponse();
                $response->setRedirect($url);
            }
        }
    }
}
