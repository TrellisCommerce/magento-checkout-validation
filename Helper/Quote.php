<?php

declare(strict_types=1);

namespace Trellis\CheckoutValidation\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote as QuoteModel;
use Trellis\CheckoutValidation\Helper\Data as Helper;

class Quote extends Data
{
    /** @var Session */
    protected $checkoutSession;

    protected ManagerInterface $manager;

    /**
     * Quote constructor.
     *
     * @param Session          $checkoutSession
     * @param Context          $context
     * @param ManagerInterface $manager
     */
    public function __construct(
        Session $checkoutSession,
        Context $context,
        ManagerInterface $manager
    ) {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
        $this->manager = $manager;
    }

    /**
     * @return QuoteModel
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCurrentQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * @param $product
     *
     * @return mixed
     */
    public function getStockItem($product)
    {
        return $product->getExtensionAttributes()->getStockItem();
    }

    /**
     * @param ProductInterface $product
     * @param int              $qty
     * @param QuoteModel       $quote
     *
     * @throws LocalizedException
     */
    public function validateMaxQty($product, $qty = 0, $quote = null)
    {
        $quote = $quote ?: $this->getCurrentQuote();
        $requestedQty = $this->getCurrentQtyForSameProduct($product, $quote) + $qty;
        $stock = $this->getStockItem($product);
        $maxSaleQty = (float)$stock->getData('max_sale_qty');

        if ($requestedQty > $maxSaleQty) {
            $errorMessage =     __(
                $this->getConfig(Helper::PATH_CART_LIMIT_CART_MAX_ERROR),
                [$product->getSku(), number_format($maxSaleQty)]
            );

            $this->manager->addErrorMessage($errorMessage);
            throw new LocalizedException($errorMessage);
        }
    }

    /**
     * @param ProductInterface $product
     * @param int              $qty
     * @param QuoteModel       $quote
     *
     * @throws LocalizedException
     */
    public function validateMinQty($product, $qty = 0, $quote = null)
    {
        $quote = $quote ?: $this->getCurrentQuote();
        $requestedQty = $this->getCurrentQtyForSameProduct($product, $quote) + $qty;
        $stock = $this->getStockItem($product);
        $minSaleQty = (float)$stock->getData('min_sale_qty');

        if ($requestedQty < $minSaleQty) {
            $errorMessage =      __(
                $this->getConfig(Helper::PATH_CART_LIMIT_CART_MIN_ERROR),
                [$product->getSku(), number_format($minSaleQty)]
            );
            $this->manager->addErrorMessage($errorMessage);
            throw new LocalizedException($errorMessage);
        }
    }

    /**
     * @param QuoteModel|null $quote
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function validateCart($quote = null)
    {
        $quote = $quote ?: $this->getCurrentQuote();
        $items = $quote->getAllItems();
        /** @var QuoteModel\Item $item */
        foreach ($items as $item) {
            $product = $item->getProduct();
            $this->validateMinQty($product);
            $this->validateMaxQty($product);
        }
    }

    /**
     * @param ProductInterface $product
     * @param QuoteModel       $quote
     *
     * @return mixed
     */
    public function getCurrentQtyForSameProduct($product, $quote)
    {
        $items = $quote->getAllItems();
        $currentQty = 0;
        /** @var QuoteModel\Item $item */
        foreach ($items as $item) {
            if ($product->getId() == $item->getData('product_id')) {
                $currentQty += $item->getQty();
            }
        }

        return $currentQty;
    }
}
