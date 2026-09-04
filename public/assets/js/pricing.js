/**
 * Price arithmetic shared by the storefront pages.
 *
 * The API returns decimal columns as strings ("2.9500", "14.00"), so every value
 * has to be coerced before it is used in arithmetic. Getting this wrong is not
 * theoretical: `100 + product.taxPercent` concatenated into "10014.00" and the
 * storefront showed a 2.95 EUR bag of bulgur as 295.41 EUR.
 *
 * Loaded as a plain <script> in the browser (attaches to window.CartaPricing)
 * and required directly by the unit tests in Node.
 */
(function (root, factory) {
    var api = factory();

    if (typeof module === 'object' && module.exports) {
        module.exports = api;
    } else {
        root.CartaPricing = api;
    }
}(typeof self !== 'undefined' ? self : this, function () {

    /** Coerce an API value to a number, treating anything unusable as 0. */
    function toNumber(value) {
        var n = parseFloat(value);

        return isFinite(n) ? n : 0;
    }

    /** Price excluding tax. */
    function netPrice(product) {
        if (!product) return 0;

        return toNumber(product.priceEach);
    }

    /** Price including the product's own tax rate. */
    function grossPrice(product) {
        if (!product) return 0;

        return toNumber(product.priceEach) * (1 + toNumber(product.taxPercent) / 100);
    }

    /** Discount percentage, rounded for display. */
    function percentOff(discount) {
        if (!discount) return 0;

        return Math.round(toNumber(discount.valuePercent));
    }

    /**
     * Gross price of a discounted product. Percentage discounts come off the
     * tax-inclusive price; fixed-amount discounts subtract directly and never
     * take the price below zero.
     */
    function discountedPrice(discount) {
        if (!discount || !discount.product) return 0;

        var gross = grossPrice(discount.product);

        if (discount.type === 'amount') {
            return Math.max(gross - toNumber(discount.valueAmount), 0);
        }

        return Math.max(gross * (1 - toNumber(discount.valuePercent) / 100), 0);
    }

    /** Line total for a quantity of a product, tax included. */
    function lineTotal(product, quantity) {
        return grossPrice(product) * toNumber(quantity);
    }

    /** Format for display, e.g. 3.363 -> "€3.36". */
    function format(amount, symbol) {
        if (symbol === undefined) symbol = '€';

        return symbol + toNumber(amount).toFixed(2);
    }

    return {
        toNumber: toNumber,
        netPrice: netPrice,
        grossPrice: grossPrice,
        percentOff: percentOff,
        discountedPrice: discountedPrice,
        lineTotal: lineTotal,
        format: format
    };
}));
