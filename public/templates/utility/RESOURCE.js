var CUSTOMER_SEARCH_ENDPOINT = "/api/v3/search/customers/";
var PRODUCT_SEARCH_ENDPOINT = "/api/v3/search/products/";
var CUSTOMERS_ENDPOINT = "/api/v3/customers";




class RESOURCE {

    prepareCache(){

         $.get(CUSTOMERS_ENDPOINT, function(data) {

        
            localStorage.setItem("customersCache",JSON.stringify(data));
            

            })

            .fail(function() {
                reject(data.result);
            })

    }

    searchCustomers(query) {

        // Promises require two functions: one for success, one for failure
        return new Promise(function(resolve, reject) {
            $.get(CUSTOMER_SEARCH_ENDPOINT + query , function(data) {

                resolve(data.result);

            })

            .fail(function() {
                reject(data.result);
            })

        })
    }

    searchProducts(query,customer) {

        var group = "";
        if (customer.getName() != "default" && customer != undefined)
        {
            var group = "?customer_id="+customer.getId() 
        }

    	        // Promises require two functions: one for success, one for failure
        return new Promise(function(resolve, reject) {
            $.get(PRODUCT_SEARCH_ENDPOINT + query+ group, function(data) {

                resolve(data.result);

            })

            .fail(function() {
                reject(data.result);
            })

        })

    }

    searchOrders(query) {

    	        // Promises require two functions: one for success, one for failure
        return new Promise(function(resolve, reject) {
            $.get(ORDER_SEARCH_ENDPOINT + query, function(data) {

                resolve(data);

            })

            .fail(function() {
                reject(data);
            })

        })

    }


}
