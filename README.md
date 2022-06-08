Brought to you and maintained by [Trellis Commerce](https://trellis.co/) - A full-service eCommerce agency based in Boston, MA.

# Trellis Checkout Validation

This extension makes several small modifications to the native Magento checkout experience.

* Adds a set of form validation rules to the shipping and billing steps of checkout.
* Respect max/min quantity on configurable products.
* Removes discount code from cart and checkout.


## Installation Instructions
Follow the instructions to install this extension using Composer.
```
composer require trellis/module-checkout-validation
bin/magento module:enable --clear-static-content Trellis_CheckoutValidation
bin/magento setup:upgrade
bin/magento cache:flush
```


## Configuration

Configuration settings for this extension are found under Stores > Configuration > Trellis > Checkout Validation

* "Enabled?" - Yes/no configuration setting that determines if the additional shipping and billing address 
  validation rules will apply.  
  * See rules below for details.
* "Remove Apply Discount Code" - Yes/no configuration setting that determines if the discount code input form is 
  visible on the cart and during checkout.  
* Max/Min quantity settings on configurable products are now respected. (These are cumulative for all child products.)


### Shipping Address Validation Rules
- Street is required and each street can have max 40 characters each
- First Name is required and can have 20 characters max
- Last Name is required and can have 20 characters max
- Company can have 40 characters max
- City is required can have 40 characters max
- Phone is required

### Billing Address Validation Rules
- Street is required and each street can have max 40 characters each
- First Name is required and can have 20 characters max
- Last Name is required and can have 20 characters max
- Company can have 40 characters max
- City is required can have 40 characters max
- Phone is required
- Postal Code is required
 