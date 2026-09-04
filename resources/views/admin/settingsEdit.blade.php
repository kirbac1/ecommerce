@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Settings'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            <div class="btn-group btn-group pull-right">
                <button class="btn btn-primary bgm-red" v-on:click.prevent="update">{{ trans('messages.SAVE') }}</button>
            </div>
            <h2>
                {{ trans('messages.Settings') }}
                <small>
                    {{ trans('messages._settings_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="store_name" class="form-control input-sm" placeholder="{{ trans('messages.Name') }}" v-model="object.store_name">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_address_1">{{ trans('messages.Address 1') }}</label>
                            <input type="text" name="store_address_1" class="form-control input-sm" placeholder="{{ trans('messages.Address 1') }}" v-model="object.store_address_1">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_address_2">{{ trans('messages.Address 2') }}</label>
                            <input type="text" name="store_address_2" class="form-control input-sm" placeholder="{{ trans('messages.Address 2') }}" v-model="object.store_address_2">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_postal_code">{{ trans('messages.Postal Code') }}</label>
                            <input type="text" name="store_postal_code" class="form-control input-sm" placeholder="{{ trans('messages.Postal Code') }}" v-model="object.store_postal_code">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_city">{{ trans('messages.City') }}</label>
                            <input type="text" name="store_city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="object.store_city">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_state">{{ trans('messages.City') }}</label>
                            <input type="text" name="store_state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="object.store_state">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_vatid">{{ trans('messages.VAT ID') }}</label>
                            <input type="text" name="store_vatid" class="form-control input-sm" placeholder="{{ trans('messages.VAT ID') }}" v-model="object.store_vatid">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_taxid">{{ trans('messages.Tax ID') }}</label>
                            <input type="text" name="store_taxid" class="form-control input-sm" placeholder="{{ trans('messages.Tax ID') }}" v-model="object.store_taxid">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_iban">{{ trans('messages.IBAN') }}</label>
                            <input type="text" name="store_iban" class="form-control input-sm" placeholder="{{ trans('messages.IBAN') }}" v-model="object.store_iban">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_bic">{{ trans('messages.BIC') }}</label>
                            <input type="text" name="store_bic" class="form-control input-sm" placeholder="{{ trans('messages.BIC') }}" v-model="object.store_bic">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_email">{{ trans('messages.E-Mail') }}</label>
                            <input type="text" name="store_email" class="form-control input-sm" placeholder="{{ trans('messages.E-Mail') }}" v-model="object.store_email">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_url">{{ trans('messages.URL') }}</label>
                            <input type="text" name="store_url" class="form-control input-sm" placeholder="{{ trans('messages.URL') }}" v-model="object.store_url">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_telephone">{{ trans('messages.Telephone') }}</label>
                            <input type="text" name="store_telephone" class="form-control input-sm" placeholder="{{ trans('messages.Telephone') }}" v-model="object.store_telephone">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_mobile">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="store_mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="object.store_mobile">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_motto">{{ trans('messages.Motto') }}</label>
                            <input type="text" name="store_motto" class="form-control input-sm" placeholder="{{ trans('messages.Motto') }}" v-model="object.store_motto">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="ecommerce_title">{{ trans('messages.E-Commerce Title') }}</label>
                            <input type="text" name="ecommerce_title" class="form-control input-sm" placeholder="{{ trans('messages.E-Commerce Title') }}" v-model="object.ecommerce_title">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_profitpercent">{{ trans('messages.Default Profit Percent') }}</label>
                            <input type="text" name="store_profitpercent" class="form-control input-sm" placeholder="{{ trans('messages.Default Profit Percent') }}" v-model="object.store_profitpercent">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Default Customer Group') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="default_customer_group_id" v-model="object.default_customer_group_id">
                                        <option v-for="(cgkey, cgval) in customergroups" value="@{{ cgval.id }}">@{{ cgval.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Default Customer Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="default_customer_group_id" v-model="object.default_customer_type">
                                        <option value="person">{{ trans('messages.Person') }}</option>
                                        <option value="company">{{ trans('messages.Company') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="col-sm-3">
                       <div class="form-group fg-line">
                            <label for="due_date">{{ trans('messages.Valid') }}</label>
                            <input type="text" name="valid" class="form-control input-sm" placeholder="{{ trans('messages.Valid') }}" v-model="object.valid">
                        </div>
                    </div>
                     <div class="col-sm-3">
                            <div class="form-group fg-line">
                            <label for="reminder_fee">{{ trans('messages.Reminder Fee') }}</label>
                            <input type="text" name="late_payment" class="form-control input-sm" placeholder="{{ trans('messages.Reminder Fee') }}" v-model="object.reminder_fee">
                            </div>
                    </div>
                    <div class="col-sm-3">
                            <div class="form-group fg-line">
                            <label for="late_payment_percent">{{ trans('messages.Late Payment Percent') }}</label>
                            <input type="text" name="late_payment_percent" class="form-control input-sm" placeholder="{{ trans('messages.Late Payment Percent') }}" v-model="object.late_payment_percent">
                            </div>
                    </div>
                      <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="store_laskuDestination">{{ trans('messages.Invoice Destination') }}</label>
                            <input type="checkbox" id="checkbox" name="store_profitpercent" v-model="object.store_laskuDestination">
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@stop

@section('page.footer')
    <script>
        new Vue({
            el: 'body',
            data: {
                object: {
                    store_name: '',
                    reminder_fee:'',
                    valid:'',
                    late_payment_percent:'',
                    store_address_1: '',
                    store_address_2: '',
                    store_postal_code: '',
                    store_state: '',
                    store_city: '',
                    store_vatid: '',
                    store_taxid: '',
                    store_iban: '',
                    store_bic: '',
                    store_email: '',
                    store_url: '',
                    store_telephone: '',
                    store_mobile: '',
                    store_motto: '',
                    ecommerce_title: '',
                    default_customer_group_id: '',
                    default_customer_type: '',
                    store_profitpercent: 30,
                },
                customergroups: [],
            },
            methods: {
                update: function(event) {
                    Vue.http.post('/api/v3/settings', { settings: this.object }).then(function success(response) {
                        this.object = Object.assign({}, this.object, response.data);
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonColor: '#2196f3',
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/settings';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
            },
            ready: function ready() {
                Vue.http.get('/api/v3/settings').then(function success(response) {
                    this.object = Object.assign({}, this.object, response.data);
                    if ((this.object.store_profitpercent == '') || (!this.object.store_profitpercent)) {
                        this.object.store_profitpercent = 30;
                    }
                }.bind(this), function error(response) {
                    console.error('FAILURE retrieving data.');
                });

                Vue.http.get('/api/v3/customergroups').then(function success(response) {
                    this.$set('customergroups', response.data);
                }.bind(this), function error(response) {
                    console.error('FAILURE retrieving customer groups.');
                });
            }
        });
    </script>
@stop