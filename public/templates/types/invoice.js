class Invoice {
    constructor(name) {
        this.name = name;
    }


    createInvoice(order) {

        order =  JSON.stringify(order);

        $.get("/api/v3/orders/"+orderId+"/convertToInvoice", function(data) {

            var invoiceID = data.id;
            window.open(
            '/api/v3/invoices/'+invoiceID,
            '_blank' // <- This is what makes it open in a new window.
        );


        }, "json");
    }
    
}
