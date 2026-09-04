var ORDER_CREATE = "/api/v3/orders";

class Order {
    constructor(name) {
        this.name = name;
    }

 
 	prepareOrder(customer, cart){

 		this.city = customer.getCity();
 		this.country = customer.getCountry();
 		this.customerId = customer.getId();
 		this.email1 = customer.getEmail();
 		this.entityType = customer.getEntityType();
 		this.name = customer.getName();
 		this.products = customer.getCart().getProducts();
 		this.surname = customer.getSurname();
 		this.vatid = customer.getVatId();
 		this.zipcode = customer.getZipcode();
 		this.phone = customer.getPhone();
 		this.street1 = customer.getStreet();

 	}

 	createOrder(){
        $.post("http://localhost/api/v3/orders", JSON.stringify(this), function(data) {

            
        }, "json");


 	}
}
