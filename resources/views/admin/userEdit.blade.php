@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit User'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($editUser !== null)
                <div class="btn-group btn-group pull-right">
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update">{{ trans('messages.SAVE') }}</button>
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit User') }}
                <small>
                    {{ trans('messages._edit_user_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <form role="form">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group fg-line">
                            <label for="name">{{ trans('messages.Name') }}</label>
                            <input type="text" name="name" class="form-control input-sm" placeholder="{{ trans('messages.Name') }}" v-model="object.name">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group fg-line">
                            <label for="surname">{{ trans('messages.Surname') }}</label>
                            <input type="text" name="surname" class="form-control input-sm" placeholder="{{ trans('messages.Surname') }}" v-model="object.surname">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group fg-line">
                            <label for="email">{{ trans('messages.E-Mail') }}</label>
                            <input type="text" name="email" class="form-control input-sm" placeholder="{{ trans('messages.E-Mail') }}" v-model="object.email">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="password">{{ trans('messages.Password') }}</label>
                            @if($editUser !== null)
                                <input type="password" name="password" class="form-control input-sm" placeholder="{{ trans('messages.Insert password to change it') }}" v-model="object.password">
                            @else
                                <input type="password" name="password" class="form-control input-sm" placeholder="{{ trans('messages.Password') }}" v-model="object.password">
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group fg-line">
                            <label for="password_confirmation">{{ trans('messages.Password Confirmation') }}</label>
                            <input type="password" name="password_confirmation" class="form-control input-sm" placeholder="{{ trans('messages.Repeat Password') }}" v-model="object.password_confirmation">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Type') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="type" v-model="object.type">
                                        <option value="admin">{{ trans('messages.prop.admin') }}</option>
                                        <option value="shippings">{{ trans('messages.prop.shippings') }}</option>
                                        <option value="cashier">{{ trans('messages.prop.cashier') }}</option>
                                        <!--<option value="webuser">{{ trans('messages.prop.webuser') }}</option>-->
                                        <!--<option value="customer">{{ trans('messages.prop.customer') }}</option>-->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Language') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="type" v-model="object.language">
                                        <option value="fi">{{ trans('messages.Finnish') }}</option>
                                        <option value="en">{{ trans('messages.English') }}</option>
                                        <option value="it">{{ trans('messages.Italian') }}</option>
                                    </select>
                                </div>
                            </div>
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
                    enabled: true,
                    language: 'fi',
                    type: 'cashier',
                    password: '',
                    password_confirmation: ''
                },
                createbuttonEnabled: true,
            },
            methods: {
                update: function(event) {
                    var that = this;
                    if (this.object.password === undefined) this.object.password = '';
                    if (this.object.password_confirmation === undefined) this.object.password_confirmation = '';
                    if (this.object.password !== this.object.password_confirmation) {
                        console.log({
                            password: this.object.password,
                            confirmation: this.object.password_confirmation
                        });
                        swal({
                            title: "{{ trans('messages.Password Error!') }}",
                            text: "{{ trans('messages.The password must match the password confirmation!') }}",
                            timer: 10000,
                            showConfirmButton: true,
                            confirmButtonColor: '#2196f3',
                            type: 'warning',
                            html: true,
                            closeOnConfirm: false
                        });
                        return false;
                    }
                    Vue.http.put('/api/v3/users/{{ $editUser->id or '' }}', this.object).then(function success(response) {
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
                            location.href = '/admin/users';
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    if (this.object.password === undefined) this.object.password = '';
                    if (this.object.password_confirmation === undefined) this.object.password_confirmation = '';
                    if ((this.object.password !== this.object.password_confirmation) || (!this.object.password)) {
                        swal({
                            title: "{{ trans('messages.Password Error!') }}",
                            text: "{{ trans('messages.The password cannot be empty and must match the password confirmation!') }}",
                            timer: 10000,
                            showConfirmButton: true,
                            confirmButtonColor: '#2196f3',
                            type: 'warning',
                            html: true,
                            closeOnConfirm: false
                        });
                        return false;
                    }
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/users', this.object).then(function success(response) {
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
                            location.href = '/admin/users/' + that.object.id + '/edit';
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
                        cancelButtonColor: '#2196f3',
                        showConfirmButton: true,
                        showCancelButton: true,
                        closeOnConfirm: false,
                        type: 'warning',
                        html: true
                    }, function(choice) {
                        if (choice) {
                            Vue.http.delete('/api/v3/users/' + that.object.id).then(function success(response) {
                                swal({
                                    title: "{{ trans('messages.Success!') }}",
                                    text: "{{ trans('messages.The element was deleted.') }}",
                                    type: 'success',
                                    html: true,
                                    timer: 10000,
                                    closeOnConfirm: false,
                                    confirmButtonColor: '#2196f3'
                                }, function() {
                                    location.href = '/admin/users';
                                });
                            }.bind(this), function error(response) {
                                @include('partials.admin.swalDataSavedFail')
                            });
                        }
                    });
                }
            },
            ready: function ready() {
                @if($editUser !== null)
                    Vue.http.get('/api/v3/users/{{ $editUser->id }}').then(function success(response) {
                        response.data.enabled = response.data.enabled === "1";
                        this.$set('object', response.data);
                    }.bind(this), function error(response) {
                        console.error('FAILURE retrieving data.');
                    });
                @endif
            }
        });
    </script>
@stop