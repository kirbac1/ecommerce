class Proforma {
    constructor(customer,products) {

        this.order = {
            "customer_id": customer.id,
            "city": customer.city,
            "country": customer.country,
            "email1": customer.email1,
            "email2": "",
            "entityType":customer.entityType,
            "mobile": customer.mobile,
            "name": customer.name,
            "phone": customer.phone,
            "state": customer.state,
            "street1": customer.street1,
            "street2": "",
            "surname": customer.surname,
            "taxit": customer.taxid,
            "vatid": customer.vatid,
            "website": customer.website,
            "zipcode": customer.zipcode,
            "products": products
        }
    }

    createProforma() {
        $.post("http://localhost/api/v3/proformas", this.order , function(data) {

            alert(data.status); // John
        }, "json");
    }
}
