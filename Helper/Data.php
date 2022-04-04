<?php

namespace Trellis\CheckoutValidation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Trellis\CheckoutValidation\Helper
 */
class Data extends AbstractHelper
{
    const XPATH_GENERAL_ACTIVE = 'trellis_checkoutvalidation/general/status';

    const PATH_DISCOUNT_CODE = 'trellis_checkoutvalidation/general/apply_discount_code';

    const PATH_CART_LIMIT_CART_ENABLED = 'trellis_checkoutvalidation/cart/limit_qty_enabled';

    const PATH_CART_LIMIT_CART_MAX_ERROR = 'trellis_checkoutvalidation/cart/limit_qty_max_error';

    const PATH_CART_LIMIT_CART_MIN_ERROR = 'trellis_checkoutvalidation/cart/limit_qty_min_error';

    /**
     * @param string $xmlPath
     * @param mixed $storeCode
     * @return mixed
     */
    public function getConfig($xmlPath, $storeCode = null)
    {
        return $this->scopeConfig->getValue($xmlPath, ScopeInterface::SCOPE_STORE, $storeCode);
    }

    /**
     * @param string|null $storeCode
     * @return mixed
     */
    public function isModuleEnabled($storeCode = null)
    {
        return $this->scopeConfig->getValue(self::XPATH_GENERAL_ACTIVE, ScopeInterface::SCOPE_STORE, $storeCode) == '1';
    }

    /**
     * @param null $storeCode
     * @return bool
     */
    public function removeDiscountCode($storeCode = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::PATH_DISCOUNT_CODE,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    /**
     * get a magento formed URL
     *
     * @param       $url
     * @param array $params
     *
     * @return string
     */
    public function getUrl($url, $params = array())
    {
        return $this->_getUrl($url, $params);
    }
}
