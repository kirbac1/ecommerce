@extends('layouts.cashier')

@section('content')
<div id="app">
    <section id="header">
        <div class="header row">
            <div class="brand col s6">
                <div style=" margin-bottom: 0px;" class="header row">
                    <div class="brand col s6">
                        <h4 class="">
                       <a href="/cashier">{{ Setting::get('store_name', config('app.name')) }}</a>
                         </h4>
                    </div>
                    <div class="currentCustomer brand col s6">
                        <div style=" margin-bottom: 0px;" class="row">
                            <div class="col s10">
                                <h5 class="name  customerInfo"></h5>
                                <p style="margin-bottom: 0px;" class="companyId"></p>
                            </div>
                            <div class="addCustomer col s2" style="float:right"><i class="material-icons left">add_box</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: none" class="order-selector col s6">
                <ul class="tabs">
                    <li id="order-1" class="order-tab tab col s3">
                        <a class="active">
                                1
                            </a>
                    </li>
                    <li id="order-2" class="order-tab tab col s3">
                        <a class="">
                                2
                            </a>
                    </li>
                    <div id="order-add" class="order-button">
                        <i @click="add" id="add_tab" class="material-icons">
                                add
                            </i>
                    </div>
                    <div class="order-button">
                        <i @click="remove" id="remove_tab" class="material-icons">
                                indeterminate_check_box
                            </i>
                    </div>
                </ul>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="left col s2">
            <section id="products">
                <div id="searchBoxes" style="width: 100%">
                    <div class="sw">
                        <input id="search" type="search" name="barcode" class="search" placeholder="Etsi tuote..." />
                    </div>
                </div>
                <div class="products row nomargin">
                </div>
            </section>
            {{-- The whole left action pad (Customers, Maksa and the number
                 keypad) was removed: none of it was wired up, and it took a
                 fixed 260px column out of the register. Customer selection and
                 payment are available from the action buttons above. --}}
        </div>
        <div class="right col s10">
            <section id="options">
                <div class="options col col-small-padding">
                    <a style="background-color: #CE9869" target="_blank" href="../admin" class="waves-effect waves-light btn-large">
                        <i class="material-icons left">whatshot</i>Asetukset
                    </a>
                </div>
                <div class="options col col-small-padding">
                    <a style="background-color: #69CE8D" class="proforma  waves-effect waves-light btn-large" class="card-title item-list-title grey-text text-darken-4">
                        <i class="material-icons left">cloud</i> Tilausvahvistus
                    </a>
                </div>
                <div class="options col col-small-padding">
                    <a style="background-color: #69A9CE" class="waves-effect waves-light btn-large" href="/cashier/return">
                        <i class="material-icons left">cached</i> Palautus
                    </a>
                </div>
                <div class="options col col-small-padding">
                    <a style="background-color: #7469CE" class="invoice waves-effect waves-light btn-large" href="/cashier/invoice" target="_blank">
                        <i class="material-icons left">euro_symbol</i> Lasku
                    </a>
                </div>
                <div class="options col col-small-padding">
                    <a style="background-color: #A6264F" class="pay waves-effect waves-light btn-large"><i class="material-icons left">keyboard_return</i>Maksa Heti</a>
                </div>
                <div class="options col col-small-padding">
                    <a class="priceCheck waves-effect waves-light btn-large"><i class="material-icons left">zoom_in</i>Tarkista Hinta</a>
                </div>
                <div class="options col col-small-padding">
                    <a class="blink_me waves-effect waves-light btn-large"><i class="material-icons left">supervisor_account</i>Valitse Asiakas</a>
                </div>

                <div class="options col col-small-padding">
                    <a class="shipment waves-effect waves-light btn-large"><i class="material-icons left">directions_bus</i>Lähetys</a>
                </div>

                <!--                     <div class="options col col-small-padding">
                        <a class="newSale waves-effect waves-light btn-large"><i class="material-icons left">card_travel</i>Uusi Myynti</a>
                    </div> -->
            </section>
            <section id="cart">
                <div class="order-empty">
                    <i class="material-icons Large">
                    shopping_cart
                         </i>
                    <h1>
                    Ostoskori on tyhjä
                        </h1>
                </div>
                <div class="cart-full row" style="margin-bottom:0px">
                    <div class="orders-header row" style="position:relative">
                        <div class="col s1">
                            Code
                        </div>
                        <div class="col s1">
                            Nimi
                        </div>
                        <div class="col s1">
                            A-hinta
                            <br>veroton
                        </div>
                        <div class="col s1">
                            A-hinta
                            <br>verollinen
                        </div>
                        <div class="col s1">
                            Ltk Määrä
                        </div>
                        <div class="col s1">
                            KPL
                        </div>
                        <div class="col s1">
                            KPL Yhteensä
                        </div>
                        <div class="col s1">
                            Alv%
                        </div>
                        <div class="col s1">
                            Veroton
                            <br>yhteensä
                        </div>
                        <div class="col s1">
                            ALV
                            <br>yhteensä
                        </div>
                        <div class="col s1">
                            Verollinen
                            <br> yhteensä
                        </div>
                        <div style=" padding-left: 30px;" class="col s1">
                            Sil
                        </div>
                    </div>
                    <div id="orders" class="orders row">
                        <ul id="productList">
                        </ul>
                    </div>
                    <div class="total-price row">
                        <div class="total-price col s12 product-name">
                            <span class="totalNonTaxed"> </span>
                            <span>VEROTON YHTEENSÄ:  </span>
                        </div>
                        <div class="col s12">
                            <span class="totalTax"></span>
                            <span style="float:right;">VERO YHTEENSÄ:  </span>
                        </div>
                        <div class="col s12">
                            <span class="totalTaxed"></span>
                            <span>VEROLLINEN YHTEENSÄ:  </span>
                        </div>
                    </div>
                </div>
                <!-- CART COMPONENT END -->
            </section>
        </div>
    </div>
</div>
<!-- Modal Structure -->
<div id="confirmRemove" class="modal">
    <div class="modal-content">
        <h4>Oletko varma?</h4>
        <p>Tuote.</p>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" removeConfirmed modal-action modal-close waves-effect waves-green btn-flat">OK</a>
    </div>
</div>
<!-- Modal Structure -->
<div id="customers" class="modal" style="width: 80%">
    <div class="modal-content">
        <div class="container">
            <div id="top" class="row">
                <div class="col s11 right-align">
                    <input id="searchCustomer" placeholder="Etsi asiakas...">
                </div>
                <div class="addCustomer col s1 right-align" style="cursor:pointer;height: 50px; background-color: gray;">
                    <i style="font-size:35px" class="material-icons">add</i>
                </div>
            </div>
            <!-- customer details end-->
            <!-- Customer list section -->
            <section>
                <div class="row">
                    <table id="customerTable" class="striped bordered responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id">Name</th>
                                <th data-field="id">Company</th>
                                <th data-field="name">Address</th>
                                <th data-field="price">Phone</th>
                                <th data-field="price">Ytunnus</th>
                            </tr>
                        </thead>
                        <tbody id="customerList">
                        </tbody>
                    </table>
                </div>
            </section>
            <!-- Customer list section end-->
        </div>
        <!-- Container end -->
    </div>
    <div class="modal-footer">
    </div>
</div>
<div id="priceCheck" class="modal" style="width: 80%">
    <div class="modal-content">
        <div class="container">
            <div id="top" class="row">
                <div class="col s11 right-align">
                    <input id="checkPrice" placeholder="Etsi tuote...">
                </div>
            </div>
            <!-- customer details end-->
            <!-- Customer list section -->
            <section>
                <div id="productDetails" class="row">
                </div>
            </section>
            <!-- Customer list section end-->
        </div>
        <!-- Container end -->
    </div>
    <div class="modal-footer">
    </div>
</div>
<!-- Modal Trigger -->
<div id="customerRegistrationForm" class="modal" style="width:60%;max-height:80%">
    <div class="modal-content">
        <div class="container">
            <div class="row">
                <form id="registerForm" class="col s12">
                    <div class="row" style="text-align: center;">
                        Rekisteröi uusi asiakas
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="icon_prefix" name="name" type="text" class="validate" required>
                            <label for="icon_prefix">Nimi</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">assignment_ind
</i>
                            <input required="true" id="icon_surname" name="surname" type="text" class="validate" required>
                            <label data-error="Virhe!" data-success="Hyvä!" for="icon_surname">Sukunimi</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">credit_card</i>
                            <input id="icon_vat" name="vatid" type="text" class="validate">
                            <label for="icon_vat">Y-tunnus</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">assignment_ind
</i>
                            <input id="icon_company" name="company" type="text" class="validate">
                            <label data-error="Virhe!" data-success="Hyvä!" for="icon_company">Yritys</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">credit_card</i>
                            <input id="icon_street1" name="street1" type="text" class="validate">
                            <label for="icon_street1">Kadunnimi</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">home</i>
                            <input id="icon_kaupunki" name="city" type="tel" class="validate">
                            <label for="icon_kaupunki">Kaupunki</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">credit_card</i>
                            <input id="icon_zipcode" name="zipcode" type="text" class="validate">
                            <label for="icon_zipcode">Postinumero</label>
                        </div>
                        <div style="padding-left: 30px" class="input-field col s6">
                            <input name="group1" type="radio" id="select_person" />
                            <label for="select_person">Henkilö</label>
                            <input checked name="group1" type="radio" id="select_company" />
                            <label for="select_company">Yritys</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">credit_card</i>
                            <input id="icon_email" name="email" type="email" class="validate">
                            <label data-error="Virhe!" data-success="Hyvä!" for="icon_email">Sahköposti</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">home</i>
                            <input id="icon_telephone" name="phone" type="tel" class="validate">
                            <label data-error="Virhe!" data-success="Hyvä!" for="icon_telephone">Puhelin</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">supervisor_account</i>
                            <input id="icon_group" name="group" type="text" class="validate">
                            <label for="icon_group">Ryhmä</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">location_on</i>
                            <input id="icon_country" name="country" type="text" class="validate">
                            <label for="icon_country">Mää</label>
                        </div>
                    </div>
                    <div class="row" style="    padding-left: 14px;">
                        <p>
                            <input type="checkbox" id="setCurrentCustomer" />
                            <label for="setCurrentCustomer">Valitse asikkaaksi</label>
                        </p>
                    </div>
                    <div class="row">
                        <button style=" width: 70%; margin-right: 100px; margin-left: 100px;" class="saveCustomer waves-effect btn-large waves-light" type="submit" name="action">Talenna
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                    <div class="row" style="    padding-left: 14px;">
                        <p style="color:red" id="customerRegistrationError">
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <!-- Container end -->
    </div>
    <div class="modal-footer">
    </div>
</div>
<!-- Modal Trigger -->
<!-- Modal Structure -->
<div id="confirmNewCustomer" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>Varoitus!!</h4>
        <p>Jos vahidat asikkaan, menetat tiedot edellisesta asiakaasta! Hyväksytkö?</p>
    </div>
    <div class="modal-footer">
        <a class="confirmNewCustomer modal-action modal-close waves-effect waves-green btn-flat">OK</a>
        <a class="modal-action modal-close waves-effect waves-green btn-flat">Perutta</a>
    </div>
</div>
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

function init(argument) {
    

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").getAttribute('content');
Vue.http.headers.common['X-XSRF-TOKEN'] = getXsrfToken();


Vue.http.get('/api/v3/search/customers').then(function success(response) {


    localStorage.setItem("customersCache", JSON.stringify(response.data.result));

}.bind(this), function error(response) {

    console.log('FAILURE', response);

});
}

window.onload =init();

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

</script>
@stop
