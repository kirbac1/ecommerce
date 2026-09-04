<!doctype html>
<html>

<head>
    <meta charset="utf-8">
         
    <title>TILAUSVAHVISTUS</title>
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>

<body>
<button class="print" style="cursor: pointer;">Lataa</button>

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
        <h1 style="float: left;  letter-spacing: 0em; ">LÄHETYS</h1>
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
                        <td>Asiakasnumero: <span>1322</span></td>
                       
                    </tr>
                    <tr>
                        <td>Päivämäärä:<span class="date"></span></td>
                        
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
                    </tr>
                </thead>
                <tbody>
               
                </tbody>
            </table>
        </div>
     
    </article>
    <table class="receiver" style="border-width: 1px;border-top-style: solid;border-top-color: black">
        <tbody>
            <tr>
                <td>Cemet Oy</td>
                <td>Ytunnus:2265244-8</td>
                <td>Sähköposti: info@alanyatukku.fi</td>
                <td>Tilinumero:</td>
                <td>Viitenumero:</td>
            </tr>
            <tr>
                <td>Kastelholmantie 2</td>
                <td>Kotipaikka: Helsinki</td>
                <td>Puhelin:097531022 </td>
                <td>IBAN:FI9612373000164936</td>
                <td>VAT:FI22652448</td>
            </tr>
            <tr>
                <td>00900 HELSINKI</td>
                <td>http://alanyatukku.fi</td>
                <td>Puhelin:+358401377838</td>
                <td>BIC:NDEAFIHH</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<div id="editor"></div>
</body>
  <script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
    </script>
<script type="text/javascript" src="/templates/shipment.js"></script>

</html>
