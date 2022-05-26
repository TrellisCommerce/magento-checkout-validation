<?php

declare(strict_types=1);

namespace Trellis\CheckoutValidation\Block;

use Trellis\CheckoutValidation\Helper\Data as HelperData;

class LayoutProcessor
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @param HelperData $helperData
     */
    public function __construct(
        HelperData $helperData
    ) {
        $this->_helperData = $helperData;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array                                            $jsLayout
     *
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        if ($this->_helperData->isModuleEnabled()) {
            //shipping address is required and each street can have max 40 characters each
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['street'] = [
                'component' => 'Magento_Ui/js/form/components/group',
                'label' => __('Street Address'),
                'required' => true,
                'dataScope' => 'shippingAddress.street',
                'provider' => 'checkoutProvider',
                'sortOrder' => 60,
                'type' => 'group',
                'children' => [
                    [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'config' => [
                            'customScope' => 'shippingAddress',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'dataScope' => '0',
                        'provider' => 'checkoutProvider',
                        'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 40],
                    ],
                    [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'config' => [
                            'customScope' => 'shippingAddress',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'dataScope' => '1',
                        'provider' => 'checkoutProvider',
                        'validation' => ['required-entry' => false, "min_text_len‌​gth" => 1, "max_text_length" => 40],
                    ]
                ]
            ];

            //First Name associated with shipping address is required and can have 20 characters max
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['firstname'] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'label' => __('First Name'),
                'required' => true,
                'dataScope' => 'shippingAddress.firstname',
                'provider' => 'checkoutProvider',
                'sortOrder' => 20,
                'type' => 'abstract',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input'
                ],
                'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 20]
            ];

            //Last Name associated with shipping address is required and can have 20 characters max
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['lastname'] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'label' => __('Last Name'),
                'required' => true,
                'dataScope' => 'shippingAddress.lastname',
                'provider' => 'checkoutProvider',
                'sortOrder' => 40,
                'type' => 'abstract',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input'
                ],
                'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 20]
            ];

            //Company associated with shipping address can have 40 characters max
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['company'] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'label' => __('Company'),
                'required' => true,
                'dataScope' => 'shippingAddress.company',
                'provider' => 'checkoutProvider',
                'sortOrder' => 45,
                'type' => 'abstract',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input'
                ],
                'validation' => ["max_text_length" => 40]
            ];

            //Company associated with shipping address can have 40 characters max
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'label' => __('City'),
                'required' => true,
                'dataScope' => 'shippingAddress.city',
                'provider' => 'checkoutProvider',
                'sortOrder' => 80,
                'type' => 'abstract',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input'
                ],
                'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 40]
            ];

            //validate phone number format, shipping info
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone'] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'label' => __('Phone Number'),
                'required' => true,
                'dataScope' => 'shippingAddress.telephone',
                'provider' => 'checkoutProvider',
                'type' => 'abstract',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input'
                ],
                'validation' => ['required-entry' => true]
            ];

            $paymentsList = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['payments-list']['children'];

            foreach ($paymentsList as $paymentMethod => $payment) {
                if (isset(
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']
                )) {
                    $prefix = '';
                    if (isset($payment['dataScopePrefix'])) {
                        $prefix = $payment['dataScopePrefix'];
                    }
                    //max 40 chars allowed for each line in street address, billing info
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['street'] = [
                        'component' => 'Magento_Ui/js/form/components/group',
                        'label' => __('Street Address'),
                        'required' => true,
                        'dataScope' => $prefix . '.street',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 60,
                        'type' => 'group',
                        'children' => [
                            [
                                'component' => 'Magento_Ui/js/form/element/abstract',
                                'config' => [
                                    'customScope' => $prefix,
                                    'template' => 'ui/form/field',
                                    'elementTmpl' => 'ui/form/element/input'
                                ],
                                'dataScope' => '0',
                                'provider' => 'checkoutProvider',
                                'validation' => [
                                    'required-entry' => true,
                                    "min_text_len‌​gth" => 1,
                                    "max_text_length" => 40
                                ],
                            ],
                            [
                                'component' => 'Magento_Ui/js/form/element/abstract',
                                'config' => [
                                    'customScope' => $prefix,
                                    'template' => 'ui/form/field',
                                    'elementTmpl' => 'ui/form/element/input'
                                ],
                                'dataScope' => '1',
                                'provider' => 'checkoutProvider',
                                'validation' => [
                                    'required-entry' => false,
                                    "min_text_len‌​gth" => 1,
                                    "max_text_length" => 40
                                ],
                            ]
                        ]
                    ];

                    //max 20 chars allowed for firstname, billing info
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['firstname'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'label' => __('First Name'),
                        'required' => true,
                        'dataScope' => $prefix . '.firstname',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 20,
                        'type' => 'abstract',
                        'config' => [
                            'customScope' => $prefix,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 20]
                    ];

                    //max 20 chars allowed for lastname, billing info
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['lastname'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'label' => __('Last Name'),
                        'required' => true,
                        'dataScope' => $prefix . '.lastname',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 40,
                        'type' => 'abstract',
                        'config' => [
                            'customScope' => $prefix,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 20]
                    ];

                    //max 40 chars allowed for company name, billing info
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['company'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'label' => __('Company'),
                        'required' => true,
                        'dataScope' => $prefix . '.company',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 45,
                        'type' => 'abstract',
                        'config' => [
                            'customScope' => $prefix,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'validation' => ["max_text_length" => 40]
                    ];

                    //max 40 characters allowed for city name, billing info
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['city'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'label' => __('City'),
                        'required' => true,
                        'dataScope' => $prefix . '.city',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 80,
                        'type' => 'abstract',
                        'config' => [
                            'customScope' => $prefix,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 40]
                    ];

                    //validate billing info: phone number format
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['telephone'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'label' => __('Phone Number'),
                        'required' => true,
                        'dataScope' => $prefix . '.telephone',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 120,
                        'type' => 'abstract',
                        'config' => [
                            'customScope' => $prefix,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'validation' => ['required-entry' => true]
                    ];

                    //validate billing address zip code format
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$paymentMethod]['children']['form-fields']['children']['postcode'] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'label' => __('Zip/Postal Code'),
                        'required' => true,
                        'dataScope' => $prefix . '.postcode',
                        'provider' => 'checkoutProvider',
                        'sortOrder' => 110,
                        'type' => 'abstract',
                        'config' => [
                            'customScope' => $prefix,
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input'
                        ],
                        'validation' => ['required-entry' => true, 'validate-zip-us' => true]
                    ];
                }
            }

            if ($this->_helperData->removeDiscountCode() && isset(
                    $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]
                    ["payment"]["children"]["afterMethods"]["children"]["discount"]
                )) {
                unset(
                    $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]
                    ["payment"]["children"]["afterMethods"]["children"]["discount"]
                );
            }
        }

        return $jsLayout;
    }

}
