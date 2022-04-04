<?php

namespace Trellis\CheckoutValidation\Plugin;


use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Trellis\CheckoutValidation\Helper\Quote as Helper;

class ProductViewQuantityValidator
{
    /** @var Helper $helper */
    protected $helper;

    /**
     * ValidateConfigurableProductQuantity constructor.
     * @param Helper $helper
     */
    public function __construct(
        Helper $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * If the custom quantity validator is enabled, remove the magento default validators
     * on the qty input only for configurable products
     *
     * @param \Magento\Catalog\Block\Product\View $block
     * @param array $validators
     * @return array
     */
    public function afterGetQuantityValidators(
        \Magento\Catalog\Block\Product\View $block,
        array $validators
    ) {
        $product = $block->getProduct();
        if ($this->helper->getConfig(Helper::PATH_CART_LIMIT_CART_ENABLED) && $product->getTypeId() == Configurable::TYPE_CODE) {
            if (isset($validators['validate-item-quantity'])) {
                unset($validators['validate-item-quantity']);
            }
        }
        return $validators;
    }

}
