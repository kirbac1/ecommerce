import { describe, it, expect } from 'vitest';
import pricing from '../../public/assets/js/pricing.js';

const { toNumber, netPrice, grossPrice, percentOff, discountedPrice, lineTotal, format } = pricing;

// The API serialises decimal columns as strings, which is what tripped the
// storefront up originally -- so the fixtures use strings on purpose.
const bulgur = { name: 'Bulgur, coarse 1kg', priceEach: '2.9500', taxPercent: '14.00' };
const baklava = { name: 'Baklava tray 1kg', priceEach: '14.9000', taxPercent: '14.00' };

describe('toNumber', () => {
    it('parses the strings the API returns', () => {
        expect(toNumber('2.9500')).toBe(2.95);
        expect(toNumber('14.00')).toBe(14);
    });

    it('passes numbers through', () => {
        expect(toNumber(3.5)).toBe(3.5);
        expect(toNumber(0)).toBe(0);
    });

    it('falls back to 0 for anything unusable', () => {
        expect(toNumber(null)).toBe(0);
        expect(toNumber(undefined)).toBe(0);
        expect(toNumber('')).toBe(0);
        expect(toNumber('not a number')).toBe(0);
        expect(toNumber(NaN)).toBe(0);
        expect(toNumber(Infinity)).toBe(0);
    });
});

describe('netPrice', () => {
    it('returns the ex-tax price', () => {
        expect(netPrice(bulgur)).toBe(2.95);
    });

    it('is 0 for a missing product', () => {
        expect(netPrice(null)).toBe(0);
        expect(netPrice(undefined)).toBe(0);
    });
});

describe('grossPrice', () => {
    it('adds the product tax rate', () => {
        expect(grossPrice(bulgur)).toBeCloseTo(3.363, 3);
        expect(grossPrice(baklava)).toBeCloseTo(16.986, 3);
    });

    // Regression: `100 + "14.00"` concatenated to "10014.00", which divided by
    // 100 gave a multiplier of 100.14 and displayed 2.95 EUR as 295.41 EUR.
    it('does not concatenate the tax rate as a string', () => {
        expect(grossPrice(bulgur)).toBeLessThan(4);
        expect(grossPrice(bulgur)).not.toBeCloseTo(295.41, 2);
    });

    it('returns the net price when there is no tax', () => {
        expect(grossPrice({ priceEach: '10.00', taxPercent: '0.00' })).toBe(10);
    });

    it('treats a missing tax rate as zero rather than NaN', () => {
        expect(grossPrice({ priceEach: '10.00' })).toBe(10);
    });

    it('is 0 for a missing product', () => {
        expect(grossPrice(null)).toBe(0);
    });
});

describe('percentOff', () => {
    it('rounds the percentage for display', () => {
        expect(percentOff({ valuePercent: '20.00' })).toBe(20);
        expect(percentOff({ valuePercent: '12.40' })).toBe(12);
        expect(percentOff({ valuePercent: '12.60' })).toBe(13);
    });

    it('is 0 for a missing discount', () => {
        expect(percentOff(null)).toBe(0);
    });
});

describe('discountedPrice', () => {
    it('takes a percentage off the gross price', () => {
        const discount = { type: 'percent', valuePercent: '20.00', valueAmount: '0.00', product: baklava };

        // 14.90 * 1.14 = 16.986, less 20% = 13.5888
        expect(discountedPrice(discount)).toBeCloseTo(13.5888, 4);
        expect(format(discountedPrice(discount))).toBe('€13.59');
    });

    it('subtracts a fixed amount for amount-type discounts', () => {
        const discount = { type: 'amount', valuePercent: '0.00', valueAmount: '5.00', product: baklava };

        expect(discountedPrice(discount)).toBeCloseTo(11.986, 3);
    });

    it('never goes below zero', () => {
        const discount = { type: 'amount', valuePercent: '0.00', valueAmount: '999.00', product: bulgur };

        expect(discountedPrice(discount)).toBe(0);
    });

    it('handles a 100% discount', () => {
        const discount = { type: 'percent', valuePercent: '100.00', valueAmount: '0.00', product: bulgur };

        expect(discountedPrice(discount)).toBe(0);
    });

    it('is 0 when the discount has no product attached', () => {
        expect(discountedPrice({ type: 'percent', valuePercent: '20.00' })).toBe(0);
        expect(discountedPrice(null)).toBe(0);
    });
});

describe('lineTotal', () => {
    it('multiplies the gross price by the quantity', () => {
        expect(lineTotal(bulgur, 3)).toBeCloseTo(10.089, 3);
    });

    it('parses a string quantity', () => {
        expect(lineTotal(bulgur, '2')).toBeCloseTo(6.726, 3);
    });

    it('is 0 for a zero or missing quantity', () => {
        expect(lineTotal(bulgur, 0)).toBe(0);
        expect(lineTotal(bulgur, null)).toBe(0);
    });
});

describe('format', () => {
    it('renders two decimals with a euro sign', () => {
        expect(format(3.363)).toBe('€3.36');
        expect(format(10)).toBe('€10.00');
        expect(format('2.9500')).toBe('€2.95');
    });

    it('accepts a different symbol', () => {
        expect(format(3.363, '$')).toBe('$3.36');
    });

    it('renders unusable input as zero rather than NaN', () => {
        expect(format(null)).toBe('€0.00');
        expect(format('abc')).toBe('€0.00');
    });
});
