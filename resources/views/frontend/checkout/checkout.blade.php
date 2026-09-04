@extends('layouts.default') @section('content') 
<div class="carta-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=common/home">Home</a></li>
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=checkout/cart">Shopping Cart</a></li>
            <li><a href="http://journal.digital-atelier.com/3/index.php?route=checkout/checkout">Checkout</a></li>
        </ul>
        <div class="row">
            <div id="content" class="one-page-checkout col-sm-12">
                <h1 class="heading-title">Quick Checkout</h1>
                <div class="journal-checkout">
                    <div class="left">
                        <div class="checkout-content login-box">
                            <h2 class="secondary-title">Create an Account or Login</h2>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="account" value="register" checked="checked"> Register Account </label>
                            </div>
<!--                             <div class="radio">
                                <label>
                                    <input type="radio" name="account" value="guest"> Guest Checkout </label>
                            </div> -->
                            <div class="radio">
                                <label>
                                    <input type="radio" name="account" value="login"> Returning Customer </label>
                            </div>
                        </div>
                                           <script>
                        $(document).delegate('input[name="account"]', 'change', function() {
                            if (this.value === 'login') {
                                $('.checkout-login').slideDown(300);
                                $('.checkout-register').addClass('checkout-loading').parent().addClass('login-mobile');
                                //$('.checkout-register').slideUp(300);
                            } else {
                                $('.checkout-login').slideUp(300);
                                $('.checkout-register').removeClass('checkout-loading').parent().removeClass('login-mobile');
                                //$('.checkout-register').slideDown(300);
                                if (this.value === 'register') {
                                    $('#password').slideDown(300);
                                } else {
                                    $('#password').slideUp(300);
                                }
                            }
                        });
                    </script>

                        <div class="checkout-content checkout-login" style="">
                            <fieldset>
                                <h2 class="secondary-title">Returning Customer</h2>
                                <div class="form-group">
                                    <label class="control-label" for="input-login_email">E-Mail</label>
                                    <input type="text" name="login_email" value="" placeholder="E-Mail" id="input-login_email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="input-login_password">Password</label>
                                    <input type="password" name="login_password" value="" placeholder="Password" id="input-login_password" class="form-control">
                                    <a href="http://journal.digital-atelier.com/3/index.php?route=account/forgotten">Forgotten Password</a>
                                </div>
                                <div class="form-group">
                                    <input type="button" value="Login" id="button-login" data-loading-text="Loading..." class="btn-primary button">
                                </div>
                            </fieldset>
                        </div>
                        <div class="checkout-content checkout-register">
                            <fieldset id="account">
                                <h2 class="secondary-title">Your Personal Details</h2>
                                <div class="form-group customer-group" style="display: none;">
                                    <label class="control-label">Customer Group</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="customer_group_id" value="1" checked="checked"> Default
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-payment-firstname">First Name</label>
                                    <input type="text" name="firstname" value="" placeholder="First Name" id="input-payment-firstname" class="form-control">
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-payment-lastname">Last Name</label>
                                    <input type="text" name="lastname" value="" placeholder="Last Name" id="input-payment-lastname" class="form-control">
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-payment-email">E-Mail</label>
                                    <input type="text" name="email" value="" placeholder="E-Mail" id="input-payment-email" class="form-control">
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-payment-telephone">Telephone</label>
                                    <input type="text" name="telephone" value="" placeholder="Telephone" id="input-payment-telephone" class="form-control">
                                </div>
                                <div class="form-group fax-input">
                                    <label class="control-label" for="input-payment-fax">Fax</label>
                                    <input type="text" name="fax" value="" placeholder="Fax" id="input-payment-fax" class="form-control">
                                </div>
                            </fieldset>
                            <fieldset id="password" style="">
                                <h2 class="secondary-title">Your Password</h2>
                                <div class="form-group required">
                                    <label class="control-label" for="input-payment-password">Password</label>
                                    <input type="password" name="password" value="" placeholder="Password" id="input-payment-password" class="form-control">
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-payment-confirm">Password Confirm</label>
                                    <input type="password" name="confirm" value="" placeholder="Password Confirm" id="input-payment-confirm" class="form-control">
                                </div>
                            </fieldset>
                            <fieldset id="address">
                                <h2 class="secondary-title">Your Address</h2>
                                <div class=" checkout-payment-form">
                                    <form class="form-horizontal form-payment">
                                        <div id="payment-new" style="display: block;">
                                            <div class="form-group company-input">
                                                <label class="col-sm-2 control-label" for="input-payment-company">Company</label>
                                                <input type="text" name="payment_company" value="" placeholder="Company" id="input-payment-company" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-payment-address-1">Address 1</label>
                                                <input type="text" name="payment_address_1" value="" placeholder="Address 1" id="input-payment-address-1" class="form-control">
                                            </div>
                                            <div class="form-group address-2-input">
                                                <label class="col-sm-2 control-label" for="input-payment-address-2">Address 2</label>
                                                <input type="text" name="payment_address_2" value="" placeholder="Address 2" id="input-payment-address-2" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-payment-city">City</label>
                                                <input type="text" name="payment_city" value="" placeholder="City" id="input-payment-city" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-payment-postcode">Post Code</label>
                                                <input type="text" name="payment_postcode" value="" placeholder="Post Code" id="input-payment-postcode" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-payment-country">Country</label>
                                                <div class="col-sm-10">
                                                    <select name="payment_country_id" id="input-payment-country" class="form-control">
                                                        <option value=""> --- Please Select --- </option>
                                                        <option value="244">Aaland Islands</option>
                                                        <option value="1">Afghanistan</option>
                                                        <option value="2">Albania</option>
                                                        <option value="3">Algeria</option>
                                                        <option value="4">American Samoa</option>
                                                        <option value="5">Andorra</option>
                                                        <option value="6">Angola</option>
                                                        <option value="7">Anguilla</option>
                                                        <option value="8">Antarctica</option>
                                                        <option value="9">Antigua and Barbuda</option>
                                                        <option value="10">Argentina</option>
                                                        <option value="11">Armenia</option>
                                                        <option value="12">Aruba</option>
                                                        <option value="13">Australia</option>
                                                        <option value="14">Austria</option>
                                                        <option value="15">Azerbaijan</option>
                                                        <option value="16">Bahamas</option>
                                                        <option value="17">Bahrain</option>
                                                        <option value="18">Bangladesh</option>
                                                        <option value="19">Barbados</option>
                                                        <option value="20">Belarus</option>
                                                        <option value="21">Belgium</option>
                                                        <option value="22">Belize</option>
                                                        <option value="23">Benin</option>
                                                        <option value="24">Bermuda</option>
                                                        <option value="25">Bhutan</option>
                                                        <option value="26">Bolivia</option>
                                                        <option value="245">Bonaire, Sint Eustatius and Saba</option>
                                                        <option value="27">Bosnia and Herzegovina</option>
                                                        <option value="28">Botswana</option>
                                                        <option value="29">Bouvet Island</option>
                                                        <option value="30">Brazil</option>
                                                        <option value="31">British Indian Ocean Territory</option>
                                                        <option value="32">Brunei Darussalam</option>
                                                        <option value="33">Bulgaria</option>
                                                        <option value="34">Burkina Faso</option>
                                                        <option value="35">Burundi</option>
                                                        <option value="36">Cambodia</option>
                                                        <option value="37">Cameroon</option>
                                                        <option value="38">Canada</option>
                                                        <option value="251">Canary Islands</option>
                                                        <option value="39">Cape Verde</option>
                                                        <option value="40">Cayman Islands</option>
                                                        <option value="41">Central African Republic</option>
                                                        <option value="42">Chad</option>
                                                        <option value="43">Chile</option>
                                                        <option value="44">China</option>
                                                        <option value="45">Christmas Island</option>
                                                        <option value="46">Cocos (Keeling) Islands</option>
                                                        <option value="47">Colombia</option>
                                                        <option value="48">Comoros</option>
                                                        <option value="49">Congo</option>
                                                        <option value="50">Cook Islands</option>
                                                        <option value="51">Costa Rica</option>
                                                        <option value="52">Cote D'Ivoire</option>
                                                        <option value="53">Croatia</option>
                                                        <option value="54">Cuba</option>
                                                        <option value="246">Curacao</option>
                                                        <option value="55">Cyprus</option>
                                                        <option value="56">Czech Republic</option>
                                                        <option value="237">Democratic Republic of Congo</option>
                                                        <option value="57">Denmark</option>
                                                        <option value="58">Djibouti</option>
                                                        <option value="59">Dominica</option>
                                                        <option value="60">Dominican Republic</option>
                                                        <option value="61">East Timor</option>
                                                        <option value="62">Ecuador</option>
                                                        <option value="63">Egypt</option>
                                                        <option value="64">El Salvador</option>
                                                        <option value="65">Equatorial Guinea</option>
                                                        <option value="66">Eritrea</option>
                                                        <option value="67">Estonia</option>
                                                        <option value="68">Ethiopia</option>
                                                        <option value="69">Falkland Islands (Malvinas)</option>
                                                        <option value="70">Faroe Islands</option>
                                                        <option value="71">Fiji</option>
                                                        <option value="72">Finland</option>
                                                        <option value="74">France, Metropolitan</option>
                                                        <option value="75">French Guiana</option>
                                                        <option value="76">French Polynesia</option>
                                                        <option value="77">French Southern Territories</option>
                                                        <option value="126">FYROM</option>
                                                        <option value="78">Gabon</option>
                                                        <option value="79">Gambia</option>
                                                        <option value="80">Georgia</option>
                                                        <option value="81">Germany</option>
                                                        <option value="82">Ghana</option>
                                                        <option value="83">Gibraltar</option>
                                                        <option value="84">Greece</option>
                                                        <option value="85">Greenland</option>
                                                        <option value="86">Grenada</option>
                                                        <option value="87">Guadeloupe</option>
                                                        <option value="88">Guam</option>
                                                        <option value="89">Guatemala</option>
                                                        <option value="241">Guernsey</option>
                                                        <option value="90">Guinea</option>
                                                        <option value="91">Guinea-Bissau</option>
                                                        <option value="92">Guyana</option>
                                                        <option value="93">Haiti</option>
                                                        <option value="94">Heard and Mc Donald Islands</option>
                                                        <option value="95">Honduras</option>
                                                        <option value="96">Hong Kong</option>
                                                        <option value="97">Hungary</option>
                                                        <option value="98">Iceland</option>
                                                        <option value="99">India</option>
                                                        <option value="100">Indonesia</option>
                                                        <option value="101">Iran (Islamic Republic of)</option>
                                                        <option value="102">Iraq</option>
                                                        <option value="103">Ireland</option>
                                                        <option value="104">Israel</option>
                                                        <option value="105">Italy</option>
                                                        <option value="106">Jamaica</option>
                                                        <option value="107">Japan</option>
                                                        <option value="240">Jersey</option>
                                                        <option value="108">Jordan</option>
                                                        <option value="109">Kazakhstan</option>
                                                        <option value="110">Kenya</option>
                                                        <option value="111">Kiribati</option>
                                                        <option value="113">Korea, Republic of</option>
                                                        <option value="114">Kuwait</option>
                                                        <option value="115">Kyrgyzstan</option>
                                                        <option value="116">Lao People's Democratic Republic</option>
                                                        <option value="117">Latvia</option>
                                                        <option value="118">Lebanon</option>
                                                        <option value="119">Lesotho</option>
                                                        <option value="120">Liberia</option>
                                                        <option value="121">Libyan Arab Jamahiriya</option>
                                                        <option value="122">Liechtenstein</option>
                                                        <option value="123">Lithuania</option>
                                                        <option value="124">Luxembourg</option>
                                                        <option value="125">Macau</option>
                                                        <option value="127">Madagascar</option>
                                                        <option value="128">Malawi</option>
                                                        <option value="129">Malaysia</option>
                                                        <option value="130">Maldives</option>
                                                        <option value="131">Mali</option>
                                                        <option value="132">Malta</option>
                                                        <option value="133">Marshall Islands</option>
                                                        <option value="134">Martinique</option>
                                                        <option value="135">Mauritania</option>
                                                        <option value="136">Mauritius</option>
                                                        <option value="137">Mayotte</option>
                                                        <option value="138">Mexico</option>
                                                        <option value="139">Micronesia, Federated States of</option>
                                                        <option value="140">Moldova, Republic of</option>
                                                        <option value="141">Monaco</option>
                                                        <option value="142">Mongolia</option>
                                                        <option value="242">Montenegro</option>
                                                        <option value="143">Montserrat</option>
                                                        <option value="144">Morocco</option>
                                                        <option value="145">Mozambique</option>
                                                        <option value="146">Myanmar</option>
                                                        <option value="147">Namibia</option>
                                                        <option value="148">Nauru</option>
                                                        <option value="149">Nepal</option>
                                                        <option value="150">Netherlands</option>
                                                        <option value="151">Netherlands Antilles</option>
                                                        <option value="152">New Caledonia</option>
                                                        <option value="153">New Zealand</option>
                                                        <option value="154">Nicaragua</option>
                                                        <option value="155">Niger</option>
                                                        <option value="156">Nigeria</option>
                                                        <option value="157">Niue</option>
                                                        <option value="158">Norfolk Island</option>
                                                        <option value="112">North Korea</option>
                                                        <option value="159">Northern Mariana Islands</option>
                                                        <option value="160">Norway</option>
                                                        <option value="161">Oman</option>
                                                        <option value="162">Pakistan</option>
                                                        <option value="163">Palau</option>
                                                        <option value="247">Palestinian Territory, Occupied</option>
                                                        <option value="164">Panama</option>
                                                        <option value="165">Papua New Guinea</option>
                                                        <option value="166">Paraguay</option>
                                                        <option value="167">Peru</option>
                                                        <option value="168">Philippines</option>
                                                        <option value="169">Pitcairn</option>
                                                        <option value="170">Poland</option>
                                                        <option value="171">Portugal</option>
                                                        <option value="172">Puerto Rico</option>
                                                        <option value="173">Qatar</option>
                                                        <option value="174">Reunion</option>
                                                        <option value="175">Romania</option>
                                                        <option value="176">Russian Federation</option>
                                                        <option value="177">Rwanda</option>
                                                        <option value="178">Saint Kitts and Nevis</option>
                                                        <option value="179">Saint Lucia</option>
                                                        <option value="180">Saint Vincent and the Grenadines</option>
                                                        <option value="181">Samoa</option>
                                                        <option value="182">San Marino</option>
                                                        <option value="183">Sao Tome and Principe</option>
                                                        <option value="184">Saudi Arabia</option>
                                                        <option value="185">Senegal</option>
                                                        <option value="243">Serbia</option>
                                                        <option value="186">Seychelles</option>
                                                        <option value="187">Sierra Leone</option>
                                                        <option value="188">Singapore</option>
                                                        <option value="189">Slovak Republic</option>
                                                        <option value="190">Slovenia</option>
                                                        <option value="191">Solomon Islands</option>
                                                        <option value="192">Somalia</option>
                                                        <option value="193">South Africa</option>
                                                        <option value="194">South Georgia &amp; South Sandwich Islands</option>
                                                        <option value="248">South Sudan</option>
                                                        <option value="195">Spain</option>
                                                        <option value="196">Sri Lanka</option>
                                                        <option value="249">St. Barthelemy</option>
                                                        <option value="197">St. Helena</option>
                                                        <option value="250">St. Martin (French part)</option>
                                                        <option value="198">St. Pierre and Miquelon</option>
                                                        <option value="199">Sudan</option>
                                                        <option value="200">Suriname</option>
                                                        <option value="201">Svalbard and Jan Mayen Islands</option>
                                                        <option value="202">Swaziland</option>
                                                        <option value="203">Sweden</option>
                                                        <option value="204">Switzerland</option>
                                                        <option value="205">Syrian Arab Republic</option>
                                                        <option value="206">Taiwan</option>
                                                        <option value="207">Tajikistan</option>
                                                        <option value="208">Tanzania, United Republic of</option>
                                                        <option value="209">Thailand</option>
                                                        <option value="210">Togo</option>
                                                        <option value="211">Tokelau</option>
                                                        <option value="212">Tonga</option>
                                                        <option value="213">Trinidad and Tobago</option>
                                                        <option value="214">Tunisia</option>
                                                        <option value="215">Turkey</option>
                                                        <option value="216">Turkmenistan</option>
                                                        <option value="217">Turks and Caicos Islands</option>
                                                        <option value="218">Tuvalu</option>
                                                        <option value="219">Uganda</option>
                                                        <option value="220">Ukraine</option>
                                                        <option value="221">United Arab Emirates</option>
                                                        <option value="222" selected="selected">United Kingdom</option>
                                                        <option value="223">United States</option>
                                                        <option value="224">United States Minor Outlying Islands</option>
                                                        <option value="225">Uruguay</option>
                                                        <option value="226">Uzbekistan</option>
                                                        <option value="227">Vanuatu</option>
                                                        <option value="228">Vatican City State (Holy See)</option>
                                                        <option value="229">Venezuela</option>
                                                        <option value="230">Viet Nam</option>
                                                        <option value="231">Virgin Islands (British)</option>
                                                        <option value="232">Virgin Islands (U.S.)</option>
                                                        <option value="233">Wallis and Futuna Islands</option>
                                                        <option value="234">Western Sahara</option>
                                                        <option value="235">Yemen</option>
                                                        <option value="238">Zambia</option>
                                                        <option value="239">Zimbabwe</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-payment-zone">Region / State</label>
                                                <div class="col-sm-10">
                                                    <select name="payment_zone_id" id="input-payment-zone" class="form-control">
                                                        <option value=""> --- Please Select --- </option>
                                                        <option value="3513">Aberdeen</option>
                                                        <option value="3514">Aberdeenshire</option>
                                                        <option value="3515">Anglesey</option>
                                                        <option value="3516">Angus</option>
                                                        <option value="3517">Argyll and Bute</option>
                                                        <option value="3518">Bedfordshire</option>
                                                        <option value="3519">Berkshire</option>
                                                        <option value="3520">Blaenau Gwent</option>
                                                        <option value="3521">Bridgend</option>
                                                        <option value="3522">Bristol</option>
                                                        <option value="3523">Buckinghamshire</option>
                                                        <option value="3524">Caerphilly</option>
                                                        <option value="3525">Cambridgeshire</option>
                                                        <option value="3526">Cardiff</option>
                                                        <option value="3527">Carmarthenshire</option>
                                                        <option value="3528">Ceredigion</option>
                                                        <option value="3529">Cheshire</option>
                                                        <option value="3530">Clackmannanshire</option>
                                                        <option value="3531">Conwy</option>
                                                        <option value="3532">Cornwall</option>
                                                        <option value="3949">County Antrim</option>
                                                        <option value="3950">County Armagh</option>
                                                        <option value="3951">County Down</option>
                                                        <option value="3952">County Fermanagh</option>
                                                        <option value="3953">County Londonderry</option>
                                                        <option value="3954">County Tyrone</option>
                                                        <option value="3955">Cumbria</option>
                                                        <option value="3533">Denbighshire</option>
                                                        <option value="3534">Derbyshire</option>
                                                        <option value="3535">Devon</option>
                                                        <option value="3536">Dorset</option>
                                                        <option value="3537">Dumfries and Galloway</option>
                                                        <option value="3538">Dundee</option>
                                                        <option value="3539">Durham</option>
                                                        <option value="3540">East Ayrshire</option>
                                                        <option value="3541">East Dunbartonshire</option>
                                                        <option value="3542">East Lothian</option>
                                                        <option value="3543">East Renfrewshire</option>
                                                        <option value="3544">East Riding of Yorkshire</option>
                                                        <option value="3545">East Sussex</option>
                                                        <option value="3546">Edinburgh</option>
                                                        <option value="3547">Essex</option>
                                                        <option value="3548">Falkirk</option>
                                                        <option value="3549">Fife</option>
                                                        <option value="3550">Flintshire</option>
                                                        <option value="3551">Glasgow</option>
                                                        <option value="3552">Gloucestershire</option>
                                                        <option value="3553">Greater London</option>
                                                        <option value="3554">Greater Manchester</option>
                                                        <option value="3555">Gwynedd</option>
                                                        <option value="3556">Hampshire</option>
                                                        <option value="3557">Herefordshire</option>
                                                        <option value="3558">Hertfordshire</option>
                                                        <option value="3559">Highlands</option>
                                                        <option value="3560">Inverclyde</option>
                                                        <option value="3972">Isle of Man</option>
                                                        <option value="3561">Isle of Wight</option>
                                                        <option value="3562">Kent</option>
                                                        <option value="3563" selected="selected">Lancashire</option>
                                                        <option value="3564">Leicestershire</option>
                                                        <option value="3565">Lincolnshire</option>
                                                        <option value="3566">Merseyside</option>
                                                        <option value="3567">Merthyr Tydfil</option>
                                                        <option value="3568">Midlothian</option>
                                                        <option value="3569">Monmouthshire</option>
                                                        <option value="3570">Moray</option>
                                                        <option value="3571">Neath Port Talbot</option>
                                                        <option value="3572">Newport</option>
                                                        <option value="3573">Norfolk</option>
                                                        <option value="3574">North Ayrshire</option>
                                                        <option value="3575">North Lanarkshire</option>
                                                        <option value="3576">North Yorkshire</option>
                                                        <option value="3577">Northamptonshire</option>
                                                        <option value="3578">Northumberland</option>
                                                        <option value="3579">Nottinghamshire</option>
                                                        <option value="3580">Orkney Islands</option>
                                                        <option value="3581">Oxfordshire</option>
                                                        <option value="3582">Pembrokeshire</option>
                                                        <option value="3583">Perth and Kinross</option>
                                                        <option value="3584">Powys</option>
                                                        <option value="3585">Renfrewshire</option>
                                                        <option value="3586">Rhondda Cynon Taff</option>
                                                        <option value="3587">Rutland</option>
                                                        <option value="3588">Scottish Borders</option>
                                                        <option value="3589">Shetland Islands</option>
                                                        <option value="3590">Shropshire</option>
                                                        <option value="3591">Somerset</option>
                                                        <option value="3592">South Ayrshire</option>
                                                        <option value="3593">South Lanarkshire</option>
                                                        <option value="3594">South Yorkshire</option>
                                                        <option value="3595">Staffordshire</option>
                                                        <option value="3596">Stirling</option>
                                                        <option value="3597">Suffolk</option>
                                                        <option value="3598">Surrey</option>
                                                        <option value="3599">Swansea</option>
                                                        <option value="3600">Torfaen</option>
                                                        <option value="3601">Tyne and Wear</option>
                                                        <option value="3602">Vale of Glamorgan</option>
                                                        <option value="3603">Warwickshire</option>
                                                        <option value="3604">West Dunbartonshire</option>
                                                        <option value="3605">West Lothian</option>
                                                        <option value="3606">West Midlands</option>
                                                        <option value="3607">West Sussex</option>
                                                        <option value="3608">West Yorkshire</option>
                                                        <option value="3609">Western Isles</option>
                                                        <option value="3610">Wiltshire</option>
                                                        <option value="3611">Worcestershire</option>
                                                        <option value="3612">Wrexham</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </fieldset>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="shipping_address" value="1" checked="checked"> My delivery and billing addresses are the same. </label>
                            </div>
                            <fieldset id="shipping-address" style="display: none">
                                <h2 class="secondary-title">Shipping Address</h2>
                                <div class=" checkout-shipping-form">
                                    <form class="form-horizontal form-shipping">
                                        <div id="shipping-new" style="display: block;">
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-firstname">First Name</label>
                                                <input type="text" name="shipping_firstname" value="" placeholder="First Name" id="input-shipping-firstname" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-lastname">Last Name</label>
                                                <input type="text" name="shipping_lastname" value="" placeholder="Last Name" id="input-shipping-lastname" class="form-control">
                                            </div>
                                            <div class="form-group company-input">
                                                <label class="col-sm-2 control-label" for="input-shipping-company">Company</label>
                                                <input type="text" name="shipping_company" value="" placeholder="Company" id="input-shipping-company" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-address-1">Address 1</label>
                                                <input type="text" name="shipping_address_1" value="" placeholder="Address 1" id="input-shipping-address-1" class="form-control">
                                            </div>
                                            <div class="form-group address-2-input">
                                                <label class="col-sm-2 control-label" for="input-shipping-address-2">Address 2</label>
                                                <input type="text" name="shipping_address_2" value="" placeholder="Address 2" id="input-shipping-address-2" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-city">City</label>
                                                <input type="text" name="shipping_city" value="" placeholder="City" id="input-shipping-city" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-postcode">Post Code</label>
                                                <input type="text" name="shipping_postcode" value="" placeholder="Post Code" id="input-shipping-postcode" class="form-control">
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-country">Country</label>
                                                <div class="col-sm-10">
                                                    <select name="shipping_country_id" id="input-shipping-country" class="form-control">
                                                        <option value=""> --- Please Select --- </option>
                                                        <option value="244">Aaland Islands</option>
                                                        <option value="1">Afghanistan</option>
                                                        <option value="2">Albania</option>
                                                        <option value="3">Algeria</option>
                                                        <option value="4">American Samoa</option>
                                                        <option value="5">Andorra</option>
                                                        <option value="6">Angola</option>
                                                        <option value="7">Anguilla</option>
                                                        <option value="8">Antarctica</option>
                                                        <option value="9">Antigua and Barbuda</option>
                                                        <option value="10">Argentina</option>
                                                        <option value="11">Armenia</option>
                                                        <option value="12">Aruba</option>
                                                        <option value="13">Australia</option>
                                                        <option value="14">Austria</option>
                                                        <option value="15">Azerbaijan</option>
                                                        <option value="16">Bahamas</option>
                                                        <option value="17">Bahrain</option>
                                                        <option value="18">Bangladesh</option>
                                                        <option value="19">Barbados</option>
                                                        <option value="20">Belarus</option>
                                                        <option value="21">Belgium</option>
                                                        <option value="22">Belize</option>
                                                        <option value="23">Benin</option>
                                                        <option value="24">Bermuda</option>
                                                        <option value="25">Bhutan</option>
                                                        <option value="26">Bolivia</option>
                                                        <option value="245">Bonaire, Sint Eustatius and Saba</option>
                                                        <option value="27">Bosnia and Herzegovina</option>
                                                        <option value="28">Botswana</option>
                                                        <option value="29">Bouvet Island</option>
                                                        <option value="30">Brazil</option>
                                                        <option value="31">British Indian Ocean Territory</option>
                                                        <option value="32">Brunei Darussalam</option>
                                                        <option value="33">Bulgaria</option>
                                                        <option value="34">Burkina Faso</option>
                                                        <option value="35">Burundi</option>
                                                        <option value="36">Cambodia</option>
                                                        <option value="37">Cameroon</option>
                                                        <option value="38">Canada</option>
                                                        <option value="251">Canary Islands</option>
                                                        <option value="39">Cape Verde</option>
                                                        <option value="40">Cayman Islands</option>
                                                        <option value="41">Central African Republic</option>
                                                        <option value="42">Chad</option>
                                                        <option value="43">Chile</option>
                                                        <option value="44">China</option>
                                                        <option value="45">Christmas Island</option>
                                                        <option value="46">Cocos (Keeling) Islands</option>
                                                        <option value="47">Colombia</option>
                                                        <option value="48">Comoros</option>
                                                        <option value="49">Congo</option>
                                                        <option value="50">Cook Islands</option>
                                                        <option value="51">Costa Rica</option>
                                                        <option value="52">Cote D'Ivoire</option>
                                                        <option value="53">Croatia</option>
                                                        <option value="54">Cuba</option>
                                                        <option value="246">Curacao</option>
                                                        <option value="55">Cyprus</option>
                                                        <option value="56">Czech Republic</option>
                                                        <option value="237">Democratic Republic of Congo</option>
                                                        <option value="57">Denmark</option>
                                                        <option value="58">Djibouti</option>
                                                        <option value="59">Dominica</option>
                                                        <option value="60">Dominican Republic</option>
                                                        <option value="61">East Timor</option>
                                                        <option value="62">Ecuador</option>
                                                        <option value="63">Egypt</option>
                                                        <option value="64">El Salvador</option>
                                                        <option value="65">Equatorial Guinea</option>
                                                        <option value="66">Eritrea</option>
                                                        <option value="67">Estonia</option>
                                                        <option value="68">Ethiopia</option>
                                                        <option value="69">Falkland Islands (Malvinas)</option>
                                                        <option value="70">Faroe Islands</option>
                                                        <option value="71">Fiji</option>
                                                        <option value="72">Finland</option>
                                                        <option value="74">France, Metropolitan</option>
                                                        <option value="75">French Guiana</option>
                                                        <option value="76">French Polynesia</option>
                                                        <option value="77">French Southern Territories</option>
                                                        <option value="126">FYROM</option>
                                                        <option value="78">Gabon</option>
                                                        <option value="79">Gambia</option>
                                                        <option value="80">Georgia</option>
                                                        <option value="81">Germany</option>
                                                        <option value="82">Ghana</option>
                                                        <option value="83">Gibraltar</option>
                                                        <option value="84">Greece</option>
                                                        <option value="85">Greenland</option>
                                                        <option value="86">Grenada</option>
                                                        <option value="87">Guadeloupe</option>
                                                        <option value="88">Guam</option>
                                                        <option value="89">Guatemala</option>
                                                        <option value="241">Guernsey</option>
                                                        <option value="90">Guinea</option>
                                                        <option value="91">Guinea-Bissau</option>
                                                        <option value="92">Guyana</option>
                                                        <option value="93">Haiti</option>
                                                        <option value="94">Heard and Mc Donald Islands</option>
                                                        <option value="95">Honduras</option>
                                                        <option value="96">Hong Kong</option>
                                                        <option value="97">Hungary</option>
                                                        <option value="98">Iceland</option>
                                                        <option value="99">India</option>
                                                        <option value="100">Indonesia</option>
                                                        <option value="101">Iran (Islamic Republic of)</option>
                                                        <option value="102">Iraq</option>
                                                        <option value="103">Ireland</option>
                                                        <option value="104">Israel</option>
                                                        <option value="105">Italy</option>
                                                        <option value="106">Jamaica</option>
                                                        <option value="107">Japan</option>
                                                        <option value="240">Jersey</option>
                                                        <option value="108">Jordan</option>
                                                        <option value="109">Kazakhstan</option>
                                                        <option value="110">Kenya</option>
                                                        <option value="111">Kiribati</option>
                                                        <option value="113">Korea, Republic of</option>
                                                        <option value="114">Kuwait</option>
                                                        <option value="115">Kyrgyzstan</option>
                                                        <option value="116">Lao People's Democratic Republic</option>
                                                        <option value="117">Latvia</option>
                                                        <option value="118">Lebanon</option>
                                                        <option value="119">Lesotho</option>
                                                        <option value="120">Liberia</option>
                                                        <option value="121">Libyan Arab Jamahiriya</option>
                                                        <option value="122">Liechtenstein</option>
                                                        <option value="123">Lithuania</option>
                                                        <option value="124">Luxembourg</option>
                                                        <option value="125">Macau</option>
                                                        <option value="127">Madagascar</option>
                                                        <option value="128">Malawi</option>
                                                        <option value="129">Malaysia</option>
                                                        <option value="130">Maldives</option>
                                                        <option value="131">Mali</option>
                                                        <option value="132">Malta</option>
                                                        <option value="133">Marshall Islands</option>
                                                        <option value="134">Martinique</option>
                                                        <option value="135">Mauritania</option>
                                                        <option value="136">Mauritius</option>
                                                        <option value="137">Mayotte</option>
                                                        <option value="138">Mexico</option>
                                                        <option value="139">Micronesia, Federated States of</option>
                                                        <option value="140">Moldova, Republic of</option>
                                                        <option value="141">Monaco</option>
                                                        <option value="142">Mongolia</option>
                                                        <option value="242">Montenegro</option>
                                                        <option value="143">Montserrat</option>
                                                        <option value="144">Morocco</option>
                                                        <option value="145">Mozambique</option>
                                                        <option value="146">Myanmar</option>
                                                        <option value="147">Namibia</option>
                                                        <option value="148">Nauru</option>
                                                        <option value="149">Nepal</option>
                                                        <option value="150">Netherlands</option>
                                                        <option value="151">Netherlands Antilles</option>
                                                        <option value="152">New Caledonia</option>
                                                        <option value="153">New Zealand</option>
                                                        <option value="154">Nicaragua</option>
                                                        <option value="155">Niger</option>
                                                        <option value="156">Nigeria</option>
                                                        <option value="157">Niue</option>
                                                        <option value="158">Norfolk Island</option>
                                                        <option value="112">North Korea</option>
                                                        <option value="159">Northern Mariana Islands</option>
                                                        <option value="160">Norway</option>
                                                        <option value="161">Oman</option>
                                                        <option value="162">Pakistan</option>
                                                        <option value="163">Palau</option>
                                                        <option value="247">Palestinian Territory, Occupied</option>
                                                        <option value="164">Panama</option>
                                                        <option value="165">Papua New Guinea</option>
                                                        <option value="166">Paraguay</option>
                                                        <option value="167">Peru</option>
                                                        <option value="168">Philippines</option>
                                                        <option value="169">Pitcairn</option>
                                                        <option value="170">Poland</option>
                                                        <option value="171">Portugal</option>
                                                        <option value="172">Puerto Rico</option>
                                                        <option value="173">Qatar</option>
                                                        <option value="174">Reunion</option>
                                                        <option value="175">Romania</option>
                                                        <option value="176">Russian Federation</option>
                                                        <option value="177">Rwanda</option>
                                                        <option value="178">Saint Kitts and Nevis</option>
                                                        <option value="179">Saint Lucia</option>
                                                        <option value="180">Saint Vincent and the Grenadines</option>
                                                        <option value="181">Samoa</option>
                                                        <option value="182">San Marino</option>
                                                        <option value="183">Sao Tome and Principe</option>
                                                        <option value="184">Saudi Arabia</option>
                                                        <option value="185">Senegal</option>
                                                        <option value="243">Serbia</option>
                                                        <option value="186">Seychelles</option>
                                                        <option value="187">Sierra Leone</option>
                                                        <option value="188">Singapore</option>
                                                        <option value="189">Slovak Republic</option>
                                                        <option value="190">Slovenia</option>
                                                        <option value="191">Solomon Islands</option>
                                                        <option value="192">Somalia</option>
                                                        <option value="193">South Africa</option>
                                                        <option value="194">South Georgia &amp; South Sandwich Islands</option>
                                                        <option value="248">South Sudan</option>
                                                        <option value="195">Spain</option>
                                                        <option value="196">Sri Lanka</option>
                                                        <option value="249">St. Barthelemy</option>
                                                        <option value="197">St. Helena</option>
                                                        <option value="250">St. Martin (French part)</option>
                                                        <option value="198">St. Pierre and Miquelon</option>
                                                        <option value="199">Sudan</option>
                                                        <option value="200">Suriname</option>
                                                        <option value="201">Svalbard and Jan Mayen Islands</option>
                                                        <option value="202">Swaziland</option>
                                                        <option value="203">Sweden</option>
                                                        <option value="204">Switzerland</option>
                                                        <option value="205">Syrian Arab Republic</option>
                                                        <option value="206">Taiwan</option>
                                                        <option value="207">Tajikistan</option>
                                                        <option value="208">Tanzania, United Republic of</option>
                                                        <option value="209">Thailand</option>
                                                        <option value="210">Togo</option>
                                                        <option value="211">Tokelau</option>
                                                        <option value="212">Tonga</option>
                                                        <option value="213">Trinidad and Tobago</option>
                                                        <option value="214">Tunisia</option>
                                                        <option value="215">Turkey</option>
                                                        <option value="216">Turkmenistan</option>
                                                        <option value="217">Turks and Caicos Islands</option>
                                                        <option value="218">Tuvalu</option>
                                                        <option value="219">Uganda</option>
                                                        <option value="220">Ukraine</option>
                                                        <option value="221">United Arab Emirates</option>
                                                        <option value="222" selected="selected">United Kingdom</option>
                                                        <option value="223">United States</option>
                                                        <option value="224">United States Minor Outlying Islands</option>
                                                        <option value="225">Uruguay</option>
                                                        <option value="226">Uzbekistan</option>
                                                        <option value="227">Vanuatu</option>
                                                        <option value="228">Vatican City State (Holy See)</option>
                                                        <option value="229">Venezuela</option>
                                                        <option value="230">Viet Nam</option>
                                                        <option value="231">Virgin Islands (British)</option>
                                                        <option value="232">Virgin Islands (U.S.)</option>
                                                        <option value="233">Wallis and Futuna Islands</option>
                                                        <option value="234">Western Sahara</option>
                                                        <option value="235">Yemen</option>
                                                        <option value="238">Zambia</option>
                                                        <option value="239">Zimbabwe</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group required">
                                                <label class="col-sm-2 control-label" for="input-shipping-zone">Region / State</label>
                                                <div class="col-sm-10">
                                                    <select name="shipping_zone_id" id="input-shipping-zone" class="form-control">
                                                        <option value=""> --- Please Select --- </option>
                                                        <option value="3513">Aberdeen</option>
                                                        <option value="3514">Aberdeenshire</option>
                                                        <option value="3515">Anglesey</option>
                                                        <option value="3516">Angus</option>
                                                        <option value="3517">Argyll and Bute</option>
                                                        <option value="3518">Bedfordshire</option>
                                                        <option value="3519">Berkshire</option>
                                                        <option value="3520">Blaenau Gwent</option>
                                                        <option value="3521">Bridgend</option>
                                                        <option value="3522">Bristol</option>
                                                        <option value="3523">Buckinghamshire</option>
                                                        <option value="3524">Caerphilly</option>
                                                        <option value="3525">Cambridgeshire</option>
                                                        <option value="3526">Cardiff</option>
                                                        <option value="3527">Carmarthenshire</option>
                                                        <option value="3528">Ceredigion</option>
                                                        <option value="3529">Cheshire</option>
                                                        <option value="3530">Clackmannanshire</option>
                                                        <option value="3531">Conwy</option>
                                                        <option value="3532">Cornwall</option>
                                                        <option value="3949">County Antrim</option>
                                                        <option value="3950">County Armagh</option>
                                                        <option value="3951">County Down</option>
                                                        <option value="3952">County Fermanagh</option>
                                                        <option value="3953">County Londonderry</option>
                                                        <option value="3954">County Tyrone</option>
                                                        <option value="3955">Cumbria</option>
                                                        <option value="3533">Denbighshire</option>
                                                        <option value="3534">Derbyshire</option>
                                                        <option value="3535">Devon</option>
                                                        <option value="3536">Dorset</option>
                                                        <option value="3537">Dumfries and Galloway</option>
                                                        <option value="3538">Dundee</option>
                                                        <option value="3539">Durham</option>
                                                        <option value="3540">East Ayrshire</option>
                                                        <option value="3541">East Dunbartonshire</option>
                                                        <option value="3542">East Lothian</option>
                                                        <option value="3543">East Renfrewshire</option>
                                                        <option value="3544">East Riding of Yorkshire</option>
                                                        <option value="3545">East Sussex</option>
                                                        <option value="3546">Edinburgh</option>
                                                        <option value="3547">Essex</option>
                                                        <option value="3548">Falkirk</option>
                                                        <option value="3549">Fife</option>
                                                        <option value="3550">Flintshire</option>
                                                        <option value="3551">Glasgow</option>
                                                        <option value="3552">Gloucestershire</option>
                                                        <option value="3553">Greater London</option>
                                                        <option value="3554">Greater Manchester</option>
                                                        <option value="3555">Gwynedd</option>
                                                        <option value="3556">Hampshire</option>
                                                        <option value="3557">Herefordshire</option>
                                                        <option value="3558">Hertfordshire</option>
                                                        <option value="3559">Highlands</option>
                                                        <option value="3560">Inverclyde</option>
                                                        <option value="3972">Isle of Man</option>
                                                        <option value="3561">Isle of Wight</option>
                                                        <option value="3562">Kent</option>
                                                        <option value="3563" selected="selected">Lancashire</option>
                                                        <option value="3564">Leicestershire</option>
                                                        <option value="3565">Lincolnshire</option>
                                                        <option value="3566">Merseyside</option>
                                                        <option value="3567">Merthyr Tydfil</option>
                                                        <option value="3568">Midlothian</option>
                                                        <option value="3569">Monmouthshire</option>
                                                        <option value="3570">Moray</option>
                                                        <option value="3571">Neath Port Talbot</option>
                                                        <option value="3572">Newport</option>
                                                        <option value="3573">Norfolk</option>
                                                        <option value="3574">North Ayrshire</option>
                                                        <option value="3575">North Lanarkshire</option>
                                                        <option value="3576">North Yorkshire</option>
                                                        <option value="3577">Northamptonshire</option>
                                                        <option value="3578">Northumberland</option>
                                                        <option value="3579">Nottinghamshire</option>
                                                        <option value="3580">Orkney Islands</option>
                                                        <option value="3581">Oxfordshire</option>
                                                        <option value="3582">Pembrokeshire</option>
                                                        <option value="3583">Perth and Kinross</option>
                                                        <option value="3584">Powys</option>
                                                        <option value="3585">Renfrewshire</option>
                                                        <option value="3586">Rhondda Cynon Taff</option>
                                                        <option value="3587">Rutland</option>
                                                        <option value="3588">Scottish Borders</option>
                                                        <option value="3589">Shetland Islands</option>
                                                        <option value="3590">Shropshire</option>
                                                        <option value="3591">Somerset</option>
                                                        <option value="3592">South Ayrshire</option>
                                                        <option value="3593">South Lanarkshire</option>
                                                        <option value="3594">South Yorkshire</option>
                                                        <option value="3595">Staffordshire</option>
                                                        <option value="3596">Stirling</option>
                                                        <option value="3597">Suffolk</option>
                                                        <option value="3598">Surrey</option>
                                                        <option value="3599">Swansea</option>
                                                        <option value="3600">Torfaen</option>
                                                        <option value="3601">Tyne and Wear</option>
                                                        <option value="3602">Vale of Glamorgan</option>
                                                        <option value="3603">Warwickshire</option>
                                                        <option value="3604">West Dunbartonshire</option>
                                                        <option value="3605">West Lothian</option>
                                                        <option value="3606">West Midlands</option>
                                                        <option value="3607">West Sussex</option>
                                                        <option value="3608">West Yorkshire</option>
                                                        <option value="3609">Western Isles</option>
                                                        <option value="3610">Wiltshire</option>
                                                        <option value="3611">Worcestershire</option>
                                                        <option value="3612">Wrexham</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                     
                                </div>
                            </fieldset>

                        </div>
                    </div>
                    <div class="right">
                        <section class="section-left">
                            <div class="spw">
                                <div class="checkout-content checkout-shipping-methods">
                                    <h2 class="secondary-title">Shipping Method</h2>
                                    <p><strong>Pick Up</strong></p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="shipping_method" value="free.free" checked="checked"> Pick Up - €0.00</label>
                                    </div>
    <!--                                 <p><strong>Flat Rate</strong></p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="shipping_method" value="flat.flat"> Flat Shipping Rate - $7.88</label>
                                    </div>
                                    <p><strong>Per Item</strong></p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="shipping_method" value="item.item"> Per Item Shipping Rate - $14.00</label>
                                    </div> -->
                                </div>
                                <div class="checkout-content checkout-payment-methods">
                                    <h2 class="secondary-title">Payment Method</h2>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method" value="cod" checked="checked"> Cash On Delivery </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method" value="bank_transfer"> Bank Transfer </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="payment_method" value="sagepay_direct"> Credit Card / Debit Card </label>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="section-right">
                            <div class="checkout-content coupon-voucher">
                                <h2 class="secondary-title">Do you Have a Coupon or Voucher?</h2>
                                <div class="panel-body checkout-coupon">
                                    <label class="col-sm-2 control-label" for="input-coupon">Enter coupon code</label>
                                    <div class="input-group">
                                        <input type="text" name="coupon" value="" placeholder="Enter coupon code" id="input-coupon" class="form-control">
                                        <span class="input-group-btn">
                <input type="button" value="Apply Coupon" id="button-coupon" data-loading-text="Loading..." class="btn-primary button">
            </span>
                                    </div>
                                </div>
                                <div class="panel-body checkout-voucher">
                                    <label class="col-sm-2 control-label" for="input-voucher">Enter voucher code</label>
                                    <div class="input-group">
                                        <input type="text" name="voucher" value="" placeholder="Enter voucher code" id="input-voucher" class="form-control">
                                        <span class="input-group-btn">
                <input type="button" value="Apply Voucher" id="button-voucher" data-loading-text="Loading..." class="btn-primary button">
            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-content checkout-cart">
                                <h2 class="secondary-title">Shopping Cart</h2>
                                <div class="table-responsive checkout-product">
                           <table  v-show="!isCartEmpty" class="table table-bordered">
                            <thead>
                                <tr>
                                         
                                    <td class="text-left name">Product Name</td>
                                    <td class="text-left model">QtyPerPack</td>
                                    <td class="text-left model">PackQty</td>
                                    <td class="text-left quantity">Quantity</td>
                                    <td class="text-right price">Unit Price</td>
                                    <td class="text-right total">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr  v-for="product in cart.products">
                                    
                                    <td class="text-left name"><a href="http://journal.digital-atelier.com/3/index.php?route=product/product&amp;product_id=57">@{{product.product.name}}</a>
                                    </td>
                                    <td class="text-left model">@{{product.product.qtyPerPack}}</td>
                                    <td class="text-left model">
                                        <input style="    width: 70px;border-radius: 4px;"  type="text" v-model="product.quantity" size="1" class="form-control">
                                    </td>
                                    <td class="text-left quantity">
                @{{calculateQty(product.quantity,product.product.qtyPerPack)}}
                                    </td>
                                      @if (Auth::user()->isCustomer)
                                    <td class="text-right price">@{{ unitPrice(product.product.priceEach) }}</td>
                                    <td class="text-right total">@{{calculatePrice(product.quantity,product.product.priceEach,product.product.qtyPerPack) }}</td>
                                    @endif
                                </tr>
                            </tbody>
                            <tfoot>
                                                      <tr>
                                                <td colspan="4" class="text-right "></td>
                                                <td class="text-right "><strong>Without Tax-Total</strong></td>
                                                <td class="text-right ">@{{cart.products | total 'quantity' 'priceEach' 'qtyPerPack'}}</td>
                                            </tr>
                                             <tr>
                                                  <td colspan="4" class="text-right "></td>
                                                <td class="text-right "><strong>Tax Amount</strong></td>
                                                <td class="text-right ">@{{cart.products | total 'quantity' 'taxAmount' 'qtyPerPack'}}</td>
                                            </tr>
                                            <tr>
                                                 <td colspan="4" class="text-right "></td>
                                                <td class="text-right "><strong>Total</strong></td>
                                                <td class="text-right ">@{{cart.products | total 'quantity' 'taxedPrice' 'qtyPerPack'}}</td>
                                            </tr>
                        </tfoot>

                        </table>
                    </div>
                </form>

                            <div class="checkout-content confirm-section">
                                <div>
                                    <h2 class="secondary-title">Add Comments About Your Order</h2>
                                    <label>
                                        <textarea name="comment" rows="8" class="form-control"></textarea>
                                    </label>
                                </div>
                                <div class="checkbox check-newsletter">
                                    <label for="newsletter">
                                        <input type="checkbox" name="newsletter" value="1" id="newsletter"> I wish to subscribe to the Journal newsletter. </label>
                                </div>
                                <div class="radio check-privacy">
                                    <label>
                                        <input type="checkbox" name="privacy" value="1"> I have read and agree to the <a href="http://journal.digital-atelier.com/3/index.php?route=information/information/agree&amp;information_id=3" class="agree"><b>Privacy Policy</b></a> </label>
                                </div>
                                <div class="radio check-terms">
                                    <label>
                                        <input type="checkbox" name="agree" value="1"> I have read and agree to the <a href="http://journal.digital-atelier.com/3/index.php?route=information/information/agree&amp;information_id=5" class="agree"><b>Terms &amp; Conditions</b></a> </label>
                                </div>
                                <div class="confirm-order">
                                    <button id="journal-checkout-confirm-button" data-loading-text="Loading.." class="button confirm-button">Confirm Order</button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
   </div>



<!-- template for the modal component -->
<script type="x/template" id="modal-template">
    <div class="modal-mask" @click="show = false" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <slot name="header">
                    </slot>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <img style="max-width:300px; max-height:400px" src="@{{img}}">
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="modal-default-button" @click="show = false">
                            CLOSE
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</script>
<!-- use the modal component, pass in the prop -->
<modal :img="img" :show.sync="showModal">
    <!--
      you can use custom content here to overwrite
      default content
    -->
</modal>
@stop @section('footer')

<script type="text/javascript">

new Vue({
    el: 'body',
    data: {
        showModal: false,
        add2CartDisabled:false,
        limit: "15",
        img: "",
        productsInCart: [],
        checkedCategories: [],
        categories: [],
        products: [],
        relatedProducts: [],
        orders: [],
        displayedProduct: {},
        displayedOrder: {},
        REGISTER_USER_ENDPOINT: "/api/v3/customer/register",
        LOGIN_USER_ENDPOINT: "/api/v3/customer/login",
        query: "",
        manufacturers:[],
        customer: {
            "id": 2,
            "customer_group_id": "",
            "type": "",
            "name": "",
            "surname": "",
            "company": "",
            "email1": "haylie83@example.net",
            "email2": "olga48@example.com",
            "website": "http://www.morar.com/atque-quae-dignissimos-similique-nam",
            "phone": "+1-534-380-6317",
            "mobile": "1-215-798-8898 x56543",
            "vatid": "VAT93287",
            "taxid": "TAX64315",
            "street1": "982 Pouros Courts Apt. 374",
            "street2": "74316 Kohler Mall Apt. 123",
            "city": "Charityland",
            "state": "East Toyborough",
            "zipcode": "03553-1815",
            "country": "Lao People's Democratic Republic",
            "notes": "Quia quia repellat voluptatum placeat dignissimos rerum assumenda. Voluptas possimus fuga consectetur illum omnis est id. Recusandae aut ipsa omnis culpa quia dolorem.",
            "enabled": "1",
            "created_at": "2016-05-02 04:34:44",
            "updated_at": "2016-05-02 04:34:44",
            "discountPercent": "7.00",
            "customer_group": "TOP1",
            "id_token": null
        },
        wishlist: {
            "products":  [],
            "length": ""
        },

        cart: {
            "total": 0,
            "products":  [],
            "length": 0
        },
        total: 123
    },
    watch: {
        checkedCategories: function(val, oldVal) {


            var url = "/api/v3/products";

            if (this.checkedCategories.length != 0) {

                url = "/api/v3/search/products/" + this.checkedCategories[0];
            }


            Vue.http.get(url).then(function success(response) {

                this.productCount = response.data.count;
                this.$set('products', response.data.result);

            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });
        },
        limit: function(argument) {


            Vue.http.get('/api/v3/search/products?limit=' + this.limit).then(function success(response) {
                this.productCount = response.data.count;
                this.$set('products', response.data.result);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });


        }
    },
    computed: {
        isLoggedIn: function() {

            return (this.customer.id_token === null || this.customer.id_token === undefined) ? false : true;

        },
        localizedObjects: function() {
            // localized = [];
            // this.objects.forEach(function(object) {
            //     if (object.type == 'company') {
            //         object.type = '{!! trans('
            //         messages.prop.company ') !!}';
            //     } else if (object.type == 'person') {
            //         object.type = '{!! trans('
            //         messages.prop.person ') !!}';
            //         object.company_name = 'N/A';
            //     }

            //     localized.push(object);
            // });
            // return localized;
        },
        isCartEmpty: function(argument) {

            return (this.cart.products.length === 0) ? true : false;
        },
        cartLength: function() {
            return this.cart.products.length;
        },
        wishlistLength: function() {
            return this.wishlist.products.length;
        }
    },
    methods: {
        searchSubmit:function (argument) {
            
            if (this.query != "") {
                location.href = "/search?search="+this.query;
            }
        },
        resetFilter: function(argument) {
            this.checkedCategories = [];
        },
        quickBuy: function(id) {
            this.add2Cart(id);
            setTimeout(function(argument) {
                location.href = "/account/checkout";
            }, 1000);


        },
        fetchItems: function(category) {

            Vue.http.get('/api/v3/products/' + category).then(function success(response) {

                this.$set('objects', response.data);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });
        },
        add2Cart: function(id) {

       
            qty = parseFloat($("#"+id).val());
            if (qty ==0 ) {
                qty = 1;
            }

            this.add2CartDisabled = true;
            if (this.addQuantity2Existing(id,qty)) {
                return;
            } else {

                Vue.http.get('/api/v3/products/' + id).then(function success(response) {
                    product = response.data;


                    var item = {
                        "quantity": qty,
                        "product": product
                    };

                    this.cart.products.push(item);
                    this.cart.length = this.cart.products.length;


                    localStorage.setItem("cart", JSON.stringify(this.cart));
                    this.add2CartDisabled = false;
                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });

            }


        },
        checkCart: function() {

            // get from local in case browser is reopened
            var cart = localStorage.getItem("cart");

            if (cart === null || cart === undefined || cart === "{}") {
                return;
            }

            cart = JSON.parse(cart)
            this.$set('cart', cart);
            // this.productsInCart = this.cart.products;


        },
        removeFromCart: function(id) {


            var length = this.cart.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.cart.products[i].product.id === id) {

                    this.cart.products.splice(i, 1);

                    localStorage.setItem("cart", JSON.stringify(this.cart));

                    break;

                }

            }
        },
        isExistInWishList: function(id) {

            var length = this.wishlist.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.wishlist.products[i].id === id) {
                    return true;

                }

            }
            return false;

        },
        add2Wishlist: function(id) {

            if (this.isExistInWishList(id)) {
                return;
            } else {

                Vue.http.get('/api/v3/products/' + id).then(function success(response) {
                    product = response.data;

                    this.wishlist.products.push(product);
                    this.wishlist.length = this.wishlist.products.length;

                    localStorage.setItem("wishlist", JSON.stringify(this.wishlist));

                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });

            }

        },
        checkWishList: function() {

            // get from local in case browser is reopened
            var wishlist = localStorage.getItem("wishlist");

            if (wishlist === null || wishlist === undefined || wishlist === "{}") {
                return;
            }
            wishlist = JSON.parse(wishlist)
            this.$set('wishlist', wishlist);

        },
        removeFromWishlist: function(id) {


            var length = this.wishlist.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.wishlist.products[i].id === id) {

                    this.wishlist.products.splice(i, 1);

                    localStorage.setItem("wishlist", JSON.stringify(this.wishlist));
                    break;

                }

            }
        },
        addQuantity2Existing: function(id) {


            var length = this.cart.products.length;

            for (var i = 0; i < length; i++) {

                // if inside the cart
                if (this.cart.products[i].product.id === id) {

                    this.cart.total += parseFloat(this.cart.products[i].product.priceEach);

                    this.cart.products[i].quantity += qty;
                    localStorage.setItem("cart", JSON.stringify(this.cart));
            
                    return true;
                }

            }
            return false;
        },
        getParameterByName: function(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
        quickView: function(image) {
            this.img = "/catalog/"+image ;
            this.showModal = true;
            console.log(id);
        },
        modalClose: function() {
            this.showModal = false;
        },
        checkboxToggle: function() {
            setTimeout(alert(this.checkedCategories[0]), 3000);

        },
        calculatePrice: function(qty, price, qtyPerPack) {

            if (this.isLoggedIn) {
                val = parseFloat(qty) * parseFloat(price) * parseFloat(qtyPerPack);
                return val.toFixed(4);
            }

        },
        calculateQty: function(qty, qtyPerPack) {
            val = parseFloat(qty) * parseFloat(qtyPerPack);
            return val.toFixed(4);
        },
        checkCustomer: function(argument) {


            // get from local in case browser is reopened
            var customer = localStorage.getItem("carta_customer");

            if (customer === null || customer === undefined || customer === "{}") {
                return;
            }
            customer = JSON.parse(customer)
            this.$set('customer', customer);
        },

        register: function(argument) {

      
          
            this.$http.post(this.REGISTER_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT BE CREATED');

            });
        },
        login: function(argument) {
            alert("ss")
            return;
            this.$http.post(this.LOGIN_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                this.customer.id_token= response.data.id_token;

                localStorage.

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT BE CREATED');

            });
        },
        forgotten: function(argument) {
            alert("ss")
            return;
            this.$http.post(this.LOGIN_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT LOGIN');

            });
        },
        updateCustomer: function(argument) {

            alert("ss")
            return;
            this.$http.put(this.LOGIN_USER_ENDPOINT, this.customer, this.options).then(function(response) {

                location.href = "/account";
            }, function(response) {
                alert('USER CANNOT LOGIN');

            });
        }

    },
    ready: function() {
        this.checkCart();
        this.checkWishList();
        this.checkCustomer();

        Vue.http.get('/api/v3/categories').then(function success(response) {


            this.$set('categories', response.data[1].children);

        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });
        Vue.http.get('/api/v3/products').then(function success(response) {
            this.productCount = response.data.count;
            this.$set('products', response.data.result);
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });

        Vue.http.get('/api/v3/customers/' + this.customer.id).then(function success(response) {

            this.$set('orders', response.data.orders);
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });

        Vue.http.get('/api/v3/manufacturers').then(function success(response) {

            this.$set('manufacturers', response.data);

            setInterval(function () {
         $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    autoplayTimeout:1000,
    autoplayHoverPause:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:3,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }
    }
}) 
    },4000);

          
        }.bind(this), function error(response) {
            console.log('FAILURE', response);
        });


        this.query = this.getParameterByName("search");

        if (this.query != null && this.query != undefined) {
            Vue.http.get('/api/v3/search/products/' + this.query + "?limit=" + this.limit).then(function success(response) {
                this.productCount = response.data.count;
                this.$set('products', response.data.result);
            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });


        }

        this.query = this.getParameterByName("id");

        if (this.query != null && this.query != undefined) {
            Vue.http.get('/api/v3/products/' + this.query).then(function success(response) {
                this.displayedProduct = response.data;

                var manufacturer = this.displayedProduct.manufacturer;

                Vue.http.get('/api/v3/search/products/' + manufacturer).then(function success(response) {


                    this.$set('relatedProducts', response.data.result);

                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });


            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });




        }
        this.orderId = this.getParameterByName("order");

        if (this.orderId != null && this.orderId != undefined) {
            Vue.http.get('/api/v3/search/orders/' + this.orderId).then(function success(response) {
                this.displayedOrder = response.data;

            }.bind(this), function error(response) {
                console.log('FAILURE', response);
            });


        }

    },

});



</script>
@stop