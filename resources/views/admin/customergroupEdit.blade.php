@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Customer Group'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($customergroup !== null)
                <div class="btn-group btn-group pull-right">
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update" id="createbutton">{{ trans('messages.SAVE') }}</button>
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Customer Group') }}
                <small>
                    {{ trans('messages._edit_customer_group_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Enter Name') }}" v-model="object.name">
                        </div>
                    </div>

                     <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="discountPercent">{{ trans('messages.Profit Percent') }}</label>
                            <input type="text" name="discountPercent" class="form-control input-sm" placeholder="{{ trans('messages.Profit Percent') }}" v-model="cProfitPercent">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="discountPercent">{{ trans('messages.Discount Percent') }}</label>
                            <input type="text" name="discountPercent" class="form-control input-sm" placeholder="{{ trans('messages.Discount Percent') }}" v-model="object.discountPercent">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group fg-line">
                            <p class="f-500 m-b-20">{{ trans('messages.Enabled') }}</p>
                            <div class="checkbox m-b-15">
                                <label>
                                    <input type="checkbox" v-model="object.enabled" name="enabled">
                                    <i class="input-helper"></i>{{ trans('messages.Enables or disables the group') }}
                                </label>
                            </div>
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
                    "name":"",
                    "discountPercent":"",
                    "enabled":false,
                    "profit":""

                },
                customergroups: [],
                createbuttonEnabled: true,
            },
            computed:{
                cProfitPercent:{
                    get:function () {
                        return this.object.profit;
                    },
                    set:function (val) {
                        this.object.discountPercent = (1-((100+parseFloat(val))/115))*100;
                    }
                }
            },
            methods: {
                update: function(event) {
                    Vue.http.put('/api/v3/customergroups/{{ $customergroup->id or '' }}', this.object).then(function success(response) {
                        this.$set('object', response.data);
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            confirmButtonColor: '#2196f3',
                            showConfirmButton: true,
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/customergroups';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/customergroups', this.object).then(function success(response) {
                        this.$set('object', response.data);
                        swal({
                            title: "{{ trans('messages.Data saved!') }}",
                            text: "{{ trans('messages.The data you sent was correctly saved!') }}",
                            timer: 3000,
                            confirmButtonColor: '#2196f3',
                            showConfirmButton: true,
                            type: 'success',
                            html: true,
                            closeOnConfirm: false
                        }, function() {
                            location.href = '/admin/customergroups/' + that.object.id + '/edit';
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
                            Vue.http.delete('/api/v3/customergroups/' + that.object.id).then(function success(response) {
                                swal({
                                    title: "{{ trans('messages.Success!') }}",
                                    text: "{{ trans('messages.The element was deleted.') }}",
                                    type: 'success',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#2196f3',
                                    html: true,
                                    timer: 3000,
                                    closeOnConfirm: false
                                }, function() {
                                    location.href = '/admin/customergroups';
                                });
                            }.bind(this), function error(response) {
                                @include('partials.admin.swalDataSavedFail')
                            });
                        }
                    });
                }
            },
            ready: function ready() {
                @if($customergroup !== null)
                    Vue.http.get('/api/v3/customergroups/{{ $customergroup->id }}').then(function success(response) {
                        response.data.enabled = response.data.enabled === "1";
                        this.$set('object', response.data);
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