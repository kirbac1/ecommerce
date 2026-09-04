@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Import_Export Products'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            <h2>{{ trans('messages.Import_Export Products') }}</h2>
            <small>{{ trans('messages._import_export_subtitle') }}</small>
        </div>

        <div class="card-body card-padding">
            <div class="row">
                <div class="col-sm-6">
                    <h4>{{ trans('messages.Import Products') }}</h4>
                    <p>{{ trans('messages._choose_to_import') }}</p>

                    <p class="c-black f-500 m-b-5 m-t-20">1. {{ trans('messages.Select CSV file to upload.') }}</p>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <span class="btn btn-primary btn-file m-r-10">
                            <span class="fileinput-new">{{ trans('messages.Select File') }}</span>
                            <span class="fileinput-exists">{{ trans('messages.Change') }}</span>
                            <input type="file" name="..." v-el="fileInput" v-model="importFilename" @change="onFileChange">
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a><br>

                        <p class="c-black f-500 m-b-5 m-t-20">2. {{ trans('messages.Click "UPLOAD".') }}</p>
                        <button class="btn btn-primary" :disabled="!enableUpload" @click.prevent="doUpload">{{ trans('messages.UPLOAD') }}</button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <p class="c-black m-b-20">{{ trans('messages.Export Products') }}</p>
                    <button class="btn btn-primary" :disabled="disableDownload" @click.prevent="doDownload">{{ trans('messages.EXPORT ALL PRODUCTS') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page.footer')
    <script>
        new Vue({
            el: 'body',
            data: {
                importFilename: '',
                importFile: '',
                enableUpload: false,
                disableDownload: false,
            },
            methods: {
                onFileChange: function(e) {
                    var that = this;
                    var reader = new FileReader();
                    var file = e.target.files[0];
                    reader.addEventListener('load', function() {
                        that.importFile = reader.result;
                        that.enableUpload = true;
                    }, false);
                    reader.readAsText(file);
                },
                doUpload: function _import(event) {
                    that = this;
                    this.$http.post('/admin/productmigration/import', {
                        'file_content': this.importFile
                    }).then(function(result) {
                        setTimeout(function() {
                            that.enableUpload = true;
                            swal({
                                type: 'success',
                                title: "{!! trans('messages._title_import_success') !!}",
                                text: "{!! trans('messages._import_success') !!}",
                                html: true,
                                showCancelButton: false,
                                showConfirmButton: true,
                                allowOutsideClick: true,
                                timer: 5000,
                                confirmButtonColor: '#2196f3',
                            });
                        }, 2000);
                    }).catch(function(e) {
                        setTimeout(function() {
                            that.enableUpload = true;
                            swal({
                                type: 'error',
                                title: "{!! trans('messages._title_import_error') !!}",
                                text: "{!! trans('messages._import_error') !!}",
                                html: true,
                                showCancelButton: false,
                                showConfirmButton: true,
                                allowOutsideClick: true,
                                timer: 5000,
                                confirmButtonColor: '#2196f3',
                            });
                        }, 2000);
                    });

                    this.enableUpload = false;
                    var spinnerHTML = "<br><br><div class='preloader pl-xxl'><svg class='pl-circular' viewBox='25 25 50 50'><circle class='plc-path' cx='50' cy='50' r='20' /></svg></div>";
                    swal({
                        title: "<h3>{{ trans('messages._title_please_wait') }}</h3>",
                        text: "<div>{!! trans('messages._upload_in_progress') !!}</div>" + spinnerHTML,
                        html: true,
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                },
                doDownload: function _export(event) {
                    this.disableDownload = true;
                    var spinnerHTML = "<br><br><div class='preloader pl-xxl'><svg class='pl-circular' viewBox='25 25 50 50'><circle class='plc-path' cx='50' cy='50' r='20' /></svg></div>";
                    swal({
                        title: "<h3>{{ trans('messages._title_please_wait') }}</h3>",
                        text: "<div>{!! trans('messages._preparing_download') !!}</div>" + spinnerHTML,
                        html: true,
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                    this.$http.get('/admin/productmigration/export').then(function(result) {
                        downloadExport('products.csv', result.data);
                        this.disableDownload = false;

                        setTimeout(function() {
                            swal({
                                type: 'success',
                                title: "{!! trans('messages._title_export_success') !!}",
                                text: "{!! trans('messages._export_success') !!}",
                                html: true,
                                showCancelButton: false,
                                showConfirmButton: true,
                                allowOutsideClick: true,
                                timer: 5000,
                                confirmButtonColor: '#2196f3',
                            });
                        }, 2000);
                    }).catch(function(e) {
                        this.disableDownload = false;

                        setTimeout(function() {
                            swal({
                                type: 'error',
                                title: "{!! trans('messages._title_export_error') !!}",
                                text: "{!! trans('messages._export_error') !!}",
                                html: true,
                                showCancelButton: false,
                                showConfirmButton: true,
                                allowOutsideClick: true,
                                timer: 5000,
                                confirmButtonColor: '#2196f3',
                            });
                        }, 2000);
                    });
                }
            },
            ready: function ready() {
            }
        });

        function downloadExport(filename, content) {
            var pom = document.createElement('a');
            pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
            pom.setAttribute('download', filename);

            if (document.createEvent) {
                var event = document.createEvent('MouseEvents');
                event.initEvent('click', true, true);
                pom.dispatchEvent(event);
            }
            else {
                pom.click();
            }
        }
    </script>
@stop