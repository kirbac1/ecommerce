class Customer {

    constructor(customer,cart) {
        if (customer != undefined) {
            this.name = customer.name;
            this.id = customer.id;
            this.surname = customer.surname;
            this.taxid = customer.taxid;
            this.vatid = customer.vatid;
            this.group = customer.group;
            this.phone = customer.phone;
            this.email = customer.email;
            this.city = customer.city;
            this.type = customer.type;
            this.company = customer.company;
            this.zipcode = customer.zipcode;
            this.country = customer.country;
            this.street1 = customer.street1;
            this.current = customer.current;
            this.address = customer.street1 + " " + customer.city + " " + customer.zipcode + "" + customer.country;

            this.cart  = (cart === null || cart === undefined) ? new Cart() : new Cart(cart);

        } else {

            this.name = "default";
            this.cart = new Cart("default");
        }
    }

    getName() {
        return this.name;
    }

    setName(newName) {
        if (newName) {
            this.name = newName;
        }
    }
    getId() {
        return this.id;
    }

    setId(id) {
        if (id) {
            this.id = id;
        }
    }
    getCompanyId() {
        return this.companyId;
    }

    setCompanyId(companyId) {
        if (companyId) {
            this.companyId = companyId;
        }
    }
    getAddress() {
        return this.address;
    }

    setAddress(address) {
        if (address) {
            this.address = address;
        }
    }
    getPhone() {
        return this.phone;
    }

    setAddress(phone) {
        if (phone) {
            this.phone = phone;
        }
    }
    addToCart(product) {
        this.cart.add(product);
    }
    currentCart() {
        return this.cart;
    }

    registerMyself(obj) { 

        var customer = this.toJSON();
        var success = false;
        var that = this;
        // $.post("/api/v3/customers", customer, function(data) {

        $.post("/api/v3/customer/register", JSON.stringify(customer), function(data) {

                if (obj.current) {
                    $(".currentCustomer .name").html(customer.name);
                    $(".currentCustomer .companyId").html(customer.vatid);
                }

                obj.setId(data.id);
                $(".blink_me").removeClass("blink_me");
                $('#customerRegistrationForm').closeModal();

                that.add2Cache(customer);


            })
            .fail(function() {

                $('#customerRegistrationError').html("Asiakasrekisteri ei onnistinut. Soita adminille!");

            })

    }

    add2Cache(customer){

        customers = localStorage.getItem("customersCache");

        customers = JSON.parse(customers);

        customers.push(customer);
       
        localStorage.setItem("customersCache",JSON.stringify(customers));

     }

    toJSON() {
        var json = {};
        json.id = this.id;
        json.name = this.name;
        json.company = this.company;
        json.surname = this.surname;
        json.email1 = this.email;
        json.zipcode = this.zipcode;
        json.vatid = this.vatid;
        json.taxid = this.taxid;
        json.street1 = this.street1;
        json.city = this.city;
        json.phone = this.phone;
        json.country = this.country;
        json.order = this.cart.toJSON();
        json.type = this.type;
        json.customer_group_id = (this.group != undefined) ? "1" : this.group;
        return json;
    }

    getOrder() {

        var order = {};
        order.customer_id = this.id;
        order.name = this.name;
        order.company = this.company;
        order.surname = this.surname;
        order.email1 = this.email;
        order.zipcode = this.zipcode;
        order.vatid = this.vatid;
        order.taxid = this.taxid;
        order.street1 = this.street1;
        order.city = this.city;
        order.phone = this.phone;
        order.country = this.country;

        order.type = this.type;
        order.customer_group_id = (this.group != undefined) ? "1" : this.group;

        var allProducts = this.cart.getProducts();
        var products = [];


        for (var i = 0; i < allProducts.length; i++) {
            var product = {};
            product.quantity = allProducts[i].totalQty;
            product.id = allProducts[i].id;
            product.priceEach = allProducts[i].priceEach;
            products.push(product);
        }

        order.products = products;

        return order;

    }


}
