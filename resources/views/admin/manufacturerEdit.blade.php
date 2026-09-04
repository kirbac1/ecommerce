@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Manufacturer'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            @if($manufacturer !== null)
                <div class="btn-group btn-group pull-right">
                    <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    <button class="btn btn-primary bgm-bluegray" v-on:click.prevent="update">{{ trans('messages.SAVE') }}</button>
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
            @endif
            <h2>
                {{ trans('messages.Edit Manufacturer') }}
                <small>
                    {{ trans('messages._edit_manufacturer_subtitle') }}
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
                        <p class="f-500 m-b-20 select-nomargin">{{ trans('messages.Visible') }}</p>
                        <div class="form-group">
                            <div class="select">
                                <div class="form-control">
                                    <select class="form-control" name="visible" v-model="object.visible">
                                        <option value="1">{{ trans('messages.Visible') }}</option>
                                        <option value="0">{{ trans('messages.Hidden') }}</option>
                                    </select>
                                </div>
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
                    visible: true
                },
                createbuttonEnabled: true,
                measureunits: [],
                manufacturers: []
            },
            methods: {
                update: function(event) {
                    var that = this;
                    Vue.http.put('/api/v3/manufacturers/{{ $manufacturer->id or '' }}', this.object).then(function success(response) {
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
                            location.href = '/admin/manufacturers/' + that.object.id + '/edit'
                        });
                    }.bind(this), function error(response) {
                        @include('partials.admin.swalDataSavedFail')
                    });
                },
                create: function(event) {
                    var that = this;
                    this.createbuttonEnabled = false;
                    Vue.http.post('/api/v3/manufacturers', this.object).then(function success(response) {
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
                            location.href = '/admin/manufacturers/' + that.object.id + '/edit';
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
                            Vue.http.delete('/api/v3/manufacturers/' + that.object.id).then(function success(response) {
                                swal({
                                    title: "{{ trans('messages.Success!') }}",
                                    text: "{{ trans('messages.The element was deleted.') }}",
                                    type: 'success',
                                    html: true,
                                    timer: 3000,
                                    confirmButtonColor: '#2196f3',
                                    closeOnConfirm: false
                                }, function() {
                                    location.href = '/admin/manufacturers';
                                });
                            }.bind(this), function error(response) {
                                @include('partials.admin.swalDataSavedFail')
                            });
                        }
                    });
                }
            },
            ready: function ready() {
                var that = this;
                @if($manufacturer !== null)
                    Vue.http.get('/api/v3/manufacturers/{{ $manufacturer->id }}').then(function success(response) {
                        that.$set('object', response.data);
                    }, function error(response) {
                        console.error('FAILURE retrieving data.');
                    });
                @endif
            }
        });
    </script>
@stop