<?php

declare(strict_types=1);

namespace Trellis\CheckoutValidation\Plugin;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Trellis\CheckoutValidation\Helper\Quote as Helper;

class ValidateConfigurableProductMaxQuantity
{
    /** @var Helper $helper */
    protected $helper;

    /**
     * ValidateConfigurableProductQuantity constructor.
     *
     * @param Helper $helper
     */
    public function __construct(
        Helper $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Validates if the requested quantity is greater than the accepted before adding it to the quote
     *
     * @param Quote   $quote
     * @param Product $product
     * @param null    $request
     * @param string  $processMode
     *
     * @return array
     * @throws LocalizedException
     */
    public function beforeAddProduct(
        Quote $quote,
        Product $product,
        $request = null,
        $processMode = \Magento\Catalog\Model\Product\Type\AbstractType::PROCESS_MODE_FULL
    ) {
        if ($this->helper->getConfig(Helper::PATH_CART_LIMIT_CART_ENABLED) && $product->getTypeId(
            ) == Configurable::TYPE_CODE) {
            $qty = 1;

            if (is_object($request)) {
                $qty = $request->getData('qty');
            }

            if (is_numeric($request)) {
                $qty = $request;
            }

            $this->helper->validateMaxQty($product, $qty, $quote);
            $this->helper->validateMinQty($product, $qty, $quote);
        }

        return [$product, $request, $processMode];
    }
}
