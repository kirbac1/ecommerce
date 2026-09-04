<div class="loadingModal">
    <!-- Place at bottom of page -->
</div>
<!-- CUSTOMER COMPONENT END -->
<!-- CART COMPONENT -->
<!-- PRODUCT COMPONENT END -->
<!-- HEADER COMPONENT END -->
<!-- ACTIONPAD COMPONENT START -->
<!-- ACTIONPAD COMPONENT END -->
<!-- CATEGORIES COMPONENT START -->
<!-- CATEGORIES COMPONENT END -->
<script type="text/javascript" src="/templates/types/cart.js">
</script>
<script type="text/javascript" src="/templates/types/customer.js">
</script>
<script type="text/javascript" src="/templates/types/product.js">
</script>
<script type="text/javascript" src="/templates/types/invoice.js">
</script>
<script type="text/javascript" src="/templates/types/order.js">
</script>
<script type="text/javascript" src="/templates/types/proforma.js">
</script>
<script src="https://www.promisejs.org/polyfills/promise-done-7.0.4.min.js"></script>
<!-- Import jQuery before materialize.js -->
<script type="text/javascript" src="/templates/assets/js/jquery-2.1.1.min.js">
</script>
<script type="text/javascript" src="/templates/assets/js/materialize.js">
</script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="/templates/assets/lib/jquery.jeditable.mini.js">
</script>
<script type="text/javascript" src="/templates/utility/RESOURCE.js"></script>
<script type="text/javascript" src="/templates/controller.js"></script>
<script type="text/javascript" src="/assets/js/vue-1.0.17.js"></script>
<script src="/assets/js/vue-resource-0.7.0.js"></script>
<script type="text/javascript">
var getXsrfToken = function() {
    var cookies = document.cookie.split(';');
    var token = '';

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split('=');
        if (cookie[0] == 'XSRF-TOKEN') {
            token = decodeURIComponent(cookie[1]);
        }
    }

    return token;
}

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").getAttribute('content');
Vue.http.headers.common['X-XSRF-TOKEN'] = getXsrfToken();


Vue.http.get('/api/v3/search/customers').then(function success(response) {


    localStorage.setItem("customersCache", JSON.stringify(response.data.result));

}.bind(this), function error(response) {

    console.log('FAILURE', response);

});
</script>
