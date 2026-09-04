class Product {
    constructor(product) {
        this.name = product.name;
        this.id = product.id;
        this.basePrice = product.basePrice;
        this.qtyPerPack = product.qtyPerPack;
        this.taxPercent = product.taxPercent;
        this.barcode = product.barcode;
        this.taxedPrice = product.taxedPrice;
        this.taxAmount =  product.taxAmount;
        this.quantity = product.quantity;
    }


    getName(){
    return this.name;

    }
    getID(){
    	return this.id;
    }

    getBasePrice(){

    	return this.basePrice;
    }

    getQtyPerPack(){
    	return this.qtyPerPack;
    }

    getTaxPercent(){
    	return this.taxPercent;
    }

    getBarcode(){
    	return this.barcode;
    }
    getTaxedPrice(){
    	return this.taxedPrice;
    }

    getTaxAmount(){
    	return this.taxAmount;
    }


    toJSON(){
    	return JSON.stringify(this);
    }

}

