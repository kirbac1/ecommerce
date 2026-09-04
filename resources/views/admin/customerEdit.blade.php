@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Customer'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($customer !== null)
                <div class="btn-group btn-group pull-right">
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update">{{ trans('messages.SAVE') }}</button>
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Customer') }}
                <small>
                    {{ trans('messages._edit_customer_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div v-if="object.type == 'company'">
                        <div class="col-sm-2">
                            <div class="form-group fg-line">
                                <label for="company">{{ trans('messages.Company Name') }}</label>
                                <input type="text" name="company" class="form-control input-sm" placeholder="{{ trans('messages.Company Name') }}" v-model="object.company">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line">
                                <label for="name">{{ trans('messages.Name') }}</label>
                                <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="object.name">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group fg-line">
                                <label for="surname">{{ trans('messages.Surname') }}</label>
                                <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="object.surname">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="col-sm-3">
                            <div class="form-group fg-line">
                                <label for="name">{{ trans('messages.Name') }}</label>
                                <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="object.name">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group fg-line">
                                <label for="surname">{{ trans('messages.Surname') }}</label>
                                <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Enter Surname') }}" v-model="object.surname">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="taxid">{{ trans('messages.VAT ID') }}</label>
                            <input type="text" name="taxid" class="form-control input-sm" placeholder="{{ trans('messages.Enter VAT ID') }}" v-model="object.taxid">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Entity Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="type" v-model="object.type">
                                        <option value="person">{{ trans('messages.Person') }}</option>
                                        <option value="company">{{ trans('messages.Company') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Customer Group') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="customer_group_id" v-model="object.customer_group_id">
                                        <option v-for="(cgkey, cgval) in customergroups" value="@{{ cgval.id }}">@{{ cgval.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="email1">{{ trans('messages.Primary E-Mail') }}</label>
                            <input type="text" name="email1" class="form-control input-sm" placeholder="{{ trans('messages.Primary E-Mail') }}" v-model="object.email1">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="email2">{{ trans('messages.Secondary E-Mail') }}</label>
                            <input type="text" name="email2" class="form-control input-sm" placeholder="{{ trans('messages.Secondary E-Mail') }}" v-model="object.email2">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group fg-line">
                            <label for="zipcode">{{ trans('messages.Zipcode') }}</label>
                            <input type="text" name="zipcode" class="form-control input-sm" placeholder="{{ trans('messages.Zipcode') }}" v-model="object.zipcode">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="street1">{{ trans('messages.Street1') }}</label>
                            <input type="text" name="street1" class="form-control input-sm" placeholder="{{ trans('messages.Street1') }}" v-model="object.street1">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="street2">{{ trans('messages.Street2') }}</label>
                            <input type="text" name="street2" class="form-control input-sm" placeholder="{{ trans('messages.Street2') }}" v-model="object.street2">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="city">{{ trans('messages.City') }}</label>
                            <input type="text" name="city" class="form-control input-sm" placeholder="{{ trans('messages.City') }}" v-model="object.city">
                        </div>
                    </div>
                </div>
                <div class="row">
                   <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.Phone') }}</label>
                            <input type="text" name="phone" class="form-control input-sm" placeholder="{{ trans('messages.Phone') }}" v-model="object.phone">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Mobile') }}</label>
                            <input type="text" name="mobile" class="form-control input-sm" placeholder="{{ trans('messages.Mobile') }}" v-model="object.mobile">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="state">{{ trans('messages.State') }}</label>
                            <input type="text" name="state" class="form-control input-sm" placeholder="{{ trans('messages.State') }}" v-model="object.state">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="country">{{ trans('messages.Country') }}</label>
                            <input type="text" name="country" class="form-control input-sm" placeholder="{{ trans('messages.Country') }}" v-model="object.country">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <p class="f-500 m-b-20">{{ trans('messages.Enabled') }}</p>
                            <div class="checkbox m-b-15">
                                <label>
                                    <input type="checkbox" v-model="object.enabled" name="enabled">
                                    <i class="input-helper"></i>{{ trans('messages.Enables the user') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="password">{{ trans('messages.Password') }}</label>
                            <input type="password" name="password" class="form-control input-sm" placeholder="{{ trans('messages.Password') }}" v-model="object.password">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <label for="password_repeat">{{ trans('messages.Repeat Password') }}</label>
                            <input type="password" name="password_repeat" class="form-control input-sm" placeholder="{{ trans('messages.Repeat Password') }}" v-model="object.password_repeat">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <textarea class="form-control auto-size" name="notes" placeholder="{{ trans('messages.Notes...') }}" v-model="object.notes"></textarea>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @if($customer)
        @if(count($customer->orders))
            <div class="card">
                <div class="card-header">
                    <h2>
                        {{ trans('messages.Orders') }}
                    </h2>
                </div>

                <div class="card-body card-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('messages.Order ID') }}</th>
                                <th>{{ trans('messages.Date') }}</th>
                                <th>{{ trans('messages.Taxed Total') }}</th>
                                <th>{{ trans('messages.Untaxed Total') }}</th>
                                <th>{{ trans('messages.Taxes Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders->reverse() as $order)
                                <tr class="clickableRow">
                                    <td v-on:click="gotoLink('/admin/orders/{{ $order->id }}/edit')">{{ $order->id }}</td>
                                    <td v-on:click="gotoLink('/admin/orders/{{ $order->id }}/edit')">{{ $order->created_at }}</td>
                                    <td v-on:click="gotoLink('/admin/orders/{{ $order->id }}/edit')">&euro;{{ $order->taxed_total }}</td>
                                    <td v-on:click="gotoLink('/admin/orders/{{ $order->id }}/edit')">&euro;{{ $order->untaxed_total }}</td>
                                    <td v-on:click="gotoLink('/admin/orders/{{ $order->id }}/edit')">&euro;{{ $order->taxes_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
@stop

@section('page.footer')
    <script>
        new Vue({
            el: 'body',
            data: {
                object: {
                    customer_group_id: {{ Setting::get('default_customer_group_id', 1) }},
                    type: "{{ Setting::get('default_customer_type', 'person') }}",
                   
                },
                customergroups: [],
                createbuttonEnabled: true,
            },
            methods: {
                gotoLink: function(url) {
                    location.href = url;
                },
                update: function(event) {
                      this.object.vatid = this.object.taxid;

                    Vue.http.put('/api/v3/customers/{{ $customer->id or '' }}', this.object).then(function success(response) {
                        this.$set('object', response.data);
                        @include('partials.admin.swalDataSavedSuccess')
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                   this.object.vatid = this.object.taxid;
                    var that = this;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/customers', this.object).then(function success(response) {
                        this.$set('object', response.data);
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
                            location.href = '/admin/customers';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                destroy: function(event) {
                    var that = this;
                    swal({
                        title: "{{ trans('messages.title_sure_to_delete?') }}",
                        text: "{{ trans('messages.body_sure_to_delete?') }}",
                        confirmButtonText: "{{ trans('messages.CONFIRM') }}",
                        confirmButtonColor: '#f44336',
                        cancelButtonText: "{{ trans('messages.CANCEL') }}",
                        cancelButtonColor: '#607d8b',
                        showConfirmButton: true,
                        showCancelButton: true,
                        type: 'warning',
                        html: true,
                        closeOnConfirm: false
                    }, function(choice) {
                        if (choice) {
                            Vue.http.delete('/api/v3/customers/' + that.object.id).then(function success(response) {
                                swal({
                                    title: "{{ trans('messages.Success!') }}",
                                    text: "{{ trans('messages.The element was deleted.') }}",
                                    type: 'success',
                                    html: true,
                                    timer: 3000,
                                    confirmButtonColor: '#2196f3',
                                    closeOnConfirm: false
                                }, function() {
                                    location.href = '/admin/customers';
                                });
                            }.bind(this), function error(response) {
                                @include('partials.admin.swalDataSavedFail')
                            });
                        }
                    });
                }
            },
            ready: function ready() {
                @if($customer !== null)
                    Vue.http.get('/api/v3/customers/{{ $customer->id or '' }}').then(function success(response) {
                        response.data.enabled = response.data.enabled === "1";
                        this.object = Object.assign({}, this.object, response.data);
                    }.bind(this), function error(response) {
                        console.error('FAILURE retrieving data.');
                    });
                @endif
                Vue.http.get('/api/v3/customergroups').then(function success(response) {
                    this.$set('customergroups', response.data);
                }.bind(this), function error(response) {
                    console.error('FAILURE retrieving customer groups.');
                });
            }
        });
    </script>
@stop