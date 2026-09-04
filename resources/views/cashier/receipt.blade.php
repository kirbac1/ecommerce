<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>KÄTEISKUITTI</title>
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>

<body>
<button class="print" style="cursor: pointer;">Lataa</button>
<button class="receipt" style="cursor: pointer;">LUO</button>
<!-- <button class="email" style="cursor: pointer;">EMAIL</button>
<button class="cancel" style="cursor: pointer;">CANCEL</button>
<button class="createInvoice" style="cursor: pointer;">INVOICE</button> -->
    <header>
        <h1 class="headline">{{ Setting::get('store_name') }}</h1>
        <h5 class="subHeadline">{{ Setting::get('store_motto') }}</h5>
        <address class="seller">
            <p>{{ Setting::get('store_name') }}</p>
            <p>{{ Setting::get('store_address_1') }}
                <br> {{ Setting::get('store_address_2') }}
                <br> {{ Setting::get('store_postal_code') }} {{ Setting::get('store_state') }}
                <br> Puh. 09-753 1022
                <br> Ytunnus: {{ Setting::get('store_taxid') }}
            </p>
        </address>
        <h1 style="float: left;  letter-spacing: 0em; ">KÄTEISKUITTI</h1>
    </header>
    <article>

        <div>
            <h1 >Saaja</h1>
            <address>
                <p ><span class="company"> </span> 
                    <br><span class="street"> Tullikamarinaukio 12 </span>
                    <br><span class="zipcode"> 33100</span>
                    <br><span class="phone">04023232343</span> 
                    <br>Ytunnus:<span class="vatid">FI193832-1</span> 
                </p>
            </address>

            <table class="info receiver" style="border-style:solid; border-color: black; border-width: 1px; float: right; width: 40%">
                <tbody>
                    <tr>
                        <td>Kuitti numero: <span class="receiptNumber"></span></td>
                        <td >Maksettu: <span class="total">5521€</span></td>
                    </tr>
                    <tr>
                        <td>Asiakasnumero: <span class="customerId">1322</span></td>
                        <td>Päivämäärä:<span class="date">17.05.2016</span></td>
                    </tr>
                    <tr>
                  
                        <td>Kotipaikka: <span>Helsinki</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="inventory">
                <thead>
                    <tr>
                        <th style="width: 4%"><span>Nr</span></th>
                        <th style="width: 30%"><span>Tuotte</span></th>
                        <th><span>Kolli</span></th>
                        <th><span>KPL</span></th>
                        <th><span>Tpl Adt</span></th>
                        <th><span>A-hinta Veroton</span></th>
                        <th><span>A-hinta Verollinen</span></th>
                        <th><span>ALV-%</span></th>
                        <th><span>Veroton Yhteensä</span></th>
                        <th><span>ALV Yhteensä</span></th>
                        <th><span>Verollinen Yhteensä</span></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <table class="balance">
                            <tr>
                <th><span>Veroton</span></th>
                <td><span data-prefix>€</span><span class="nonTaxed">0</span></td>
            </tr>
                    <tr>
                <th><span>ALV</span></th>
                <td><span data-prefix>€</span><span class="tax">0</span></td>
            </tr>
            <tr>
                <th><span>Yhteensä</span></th>
                <td><span data-prefix>€</span><span class="total">600.00</span></td>
            </tr>
            <tr>
                <th><span>Maksettu</span></th>
                <td><span data-prefix>€</span><span  class="total">0.00</span></td>
            </tr>

        </table>
    </article>
    <table class="receiver" style="border-width: 1px;border-top-style: solid;border-top-color: black">
        <tbody>
            <tr>
                <td>{{ Setting::get('store_name') }}</td>
                <td>Ytunnus: {{ Setting::get('store_taxid') }}</td>
                <td>Sähköposti: {{ Setting::get('store_email') }}</td>
                <td>Tilinumero:</td>
                <td>Viitenumero:</td>
            </tr>
            <tr>
                <td>{{ Setting::get('store_address_1') }}</td>
                <td>Kotipaikka: {{ Setting::get('store_city') }}</td>
                <td>IBAN:{{ Setting::get('store_iban') }}</td>
                <td>VAT:{{ Setting::get('store_vatid') }}</td>
                <td>TAX:{{ Setting::get('store_taxid') }} </td>
            </tr>
            <tr>
                <td>{{ Setting::get('store_postal_code') }} {{ Setting::get('store_state') }}</td>
                <td>{{ Setting::get('store_link') }}</td>
                <td>Puhelin:{{ Setting::get('store_mobile') }}</td>
                <td>BIC:{{ Setting::get('store_bic') }}</td>
            </tr>
        </tbody>
    </table>
</body>
    <script type="text/javascript" src="/templates/assets/js/jquery-2.1.1.min.js">
    </script>
<script type="text/javascript" src="/templates/receipt.js"></script>

</html>
