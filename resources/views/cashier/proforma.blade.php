<!doctype html>
<html>

<head>
    <meta charset="utf-8">
         
        <style type="text/css">
            
            
        </style> 
    <title>TILAUSVAHVISTUS</title>
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>

<body>
@include('partials.cashier.logout')
<button class="print" style="cursor: pointer;">Lataa</button>
<button class="proforma" style="cursor: pointer;">LUO</button>
<div id="proforma">
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
        <h1 style="float: left;  letter-spacing: 0em; ">TILAUSVAHVISTUS</h1>
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
                        <td>Tilaus numero: <span>232</span></td>
                        <td>Tilaus yhteensä: <span class="total">5521€</span></td>
                    </tr>
                    <tr>
                        <td>Asiakasnumero: <span>1322</span></td>
                        <td>Huomautusaika:8 pv</td>
                    </tr>
                    <tr>
                        <td>Päivämäärä:<span class="date"></span></td>
                        <td style="font-weight: bold">Viivästyskorko:<span>13.0% </span></td>
                    </tr>
                    <tr>
                        <td>Maksuehdot:<span>8pv netto</span></td>
                        <td style="font-weight: bold">Muistutusmaksu:<span> 5 eur</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold">Eräpäivä: <span class="due">31.05.2016</span></td>
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
                        <th><span>Yh. Määrä</span></th>
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
                <td><span data-prefix>€</span><span class="nonTaxed">600.00</span></td>
            </tr>
            <tr>
                <th><span>Alv Yhteensä</span></th>
                <td><span data-prefix>€</span><span class="tax">600.00</span></td>
            </tr>
            <tr>
                <th><span>Tilaus Yhteensä</span></th>
                <td><span data-prefix>€</span><span class="total">120.00</span></td>
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
</div>
<div id="editor"></div>
</body>
  <script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
    </script>
<script type="text/javascript" src="/templates/proforma.js"></script>

</html>
