<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
        
    <link rel="stylesheet" href="/templates/assets/css/invoice.css">
    <link rel="license" href="http://www.opensource.org/licenses/mit-license/">
</head>

<body>
<button class="print" style="cursor: pointer;">Lataa</button>
<button class="invoice" style="cursor: pointer;">LUO</button>



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
        <h1 style="float: left;  letter-spacing: 0em; ">LASKU</h1>
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
            <!--             <table class="meta">
                <tr>
                    <th><span >Invoice #</span></th>
                    <td><span >101138</span></td>
                </tr>
                <tr>
                    <th><span >Date</span></th>
                    <td><span contenteditable>January 1, 2012</span></td>
                </tr>
                <tr>
                    <th><span >Amount Due</span></th>
                    <td><span contenteditable>600.00</span></td>
                </tr>
            </table> -->
            <table class="info receiver" style="border-style:solid; border-color: black; border-width: 1px; float: right; width: 40%">
                <tbody>
                    <tr>
                        <td>Laskun numero: <span></span></td>
                        <td>Maksettava: <span class="total">5521€</span></td>
                    </tr>
                    <tr>
                        <td> Asiakasnumero: <span></span></td>
                        <td>Huomautusaika: <span>3 vrk</span></td>
                    </tr>
                    <tr>
                        <td>Päivämäärä:<span class="date"></span></td>
                        <td style="font-weight: bold">Viivästyskorko: <span>13,00%</span></td>
                    </tr>
                    <tr>
                        <td>Maksuehdot:<span>14pv netto</span></td>
                        <td style="font-weight: bold">Muistutusmaksu:<span>5,00€</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold">Eräpäivä: <span class="due"></span></td>
                        <td>Kotipaikka:<span> Helsinki</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="inventory">
                <thead>
                    <tr>
                        <th style="width: 4%"><span>Nr</span></th>
                        <th style="width:  30%"><span>Tuotte</span></th>
                        <th><span>Kolli</span></th>
                        <th><span>KPL</span></th>
                        <th><span style="float: right;">Tpl Adt</span></th>
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
                <th><span>ALV</span></th>
                <td><span data-prefix>€</span><span class="nonTaxed"></span></td>
            </tr>
            <tr>
                <th><span>ALV</span></th>
                <td><span data-prefix>€</span><span class="tax"></span></td>
            </tr>
            <tr>
                <th><span>Yhteensä</span></th>
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
<!--     <div class="page">
        <div class="content">
        </div>
        <div class="invoice">
            <label class="invoice--label">
                TILISIIRTO
            </label>
            <span class="invoice--terms">
      The payment will be cleared for the recipient in accordance with the
      General terms for payment transmission and only on the basis of the
      account number given by the payer.
    </span>
            <div class="recipient-bank">
                <label class="recipient-bank--label">
                    <span>Saajan tilinumero</span>
                </label>
                <label class="recipient-bank--iban--label">
                    IBAN
                </label>
                <span class="recipient-bank--iban">
        <ul class="recipient-bank--iban--list">
          <li>
            <span class="recipient-bank--iban--list--bank">
              SAMPO PANKKI
            </span>
                <span class="recipient-bank--iban--list--number">
              FI16 8000 1400 0502 67
            </span>
                </li>
                <li>
                    <span class="recipient-bank--iban--list--bank">
              NORDEA
            </span>
                    <span class="recipient-bank--iban--list--number">
              FI00 2001 3000 0012 34
            </span>
                </li>
                <li>
                    <span class="recipient-bank--iban--list--bank">
              OP
            </span>
                    <span class="recipient-bank--iban--list--number">
              FI21 5234 5600 0007 85
            </span>
                </li>
                <li>
                    <span class="recipient-bank--iban--list--bank">
              AKTIA
            </span>
                    <span class="recipient-bank--iban--list--number">
              FI16 8000 1400 0502 67
            </span>
                </li>
                </ul>
                </span>
                <label class="recipient-bank--bic--label">
                    BIC
                </label>
                <span class="recipient-bank--bic">
        <ul class="recipient-bank--bic--list">
          <li>DABAFIHH</li>
          <li>NDEAFIHH</li>
          <li>OKOYFIHH</li>
          <li>HELSFIHH</li>
        </ul>
      </span>
            </div>
            <div class="recipient">
                <label class="recipient--label">
                    <span>Saaja</span>
                </label>
                <span class="recipient--name">
        
Ugur Bakkal<br>
 Kastelholmantie 2
                <br> 00900 Helsinki
                <br> Puh. 09-753 1022

      </span>
            </div>
            <div class="payer">
                <label class="payer--personal--label">
                    <span>Maksajan nimi ja osoite</span>
                </label>
                <span class="payer--personal">
        MAIJA MAKSAJA<br>
        VIIVATIE 15<br>
        09999 KOODILA
      </span>
                <label class="payer--signature--label">
                    <span>
          Allekirjoitus

        </span>
                </label>
                <span class="payer--signature">
      </span>
                <label class="payer--from-account--label">
                    <span>Tililtä nro</span>
                </label>
                <span class="payer--from-account">
        123456-7890
      </span>
            </div>
            <div class="payment">
                <span class="payment--info">
        Osuuteni itsenäisyyspäivän lounaasta. t. Maijа
      </span>
                <label class="payment--reference--label">
                    <span>Viitenro </span>
                </label>
                <span class="payment--reference">
        12 34561
      </span>
                <label class="payment--due--label">
                    Eräpäivä
                </label>
                <span class="payment--due">
  
      </span>
                <label class="payment--amount--label">
                    Euro
                </label>
                <span class="total payment--amount">
        50,00
      </span>
            </div>
            <div class="barcode">
            </div>
        </div>
    </div> -->
</body>
  <script type="text/javascript" src="/assets/js/jquery-2.1.1.min.js">
    </script>
<script type="text/javascript" src="/templates/invoice.js"></script>


</html>
