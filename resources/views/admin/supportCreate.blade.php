@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Ticket Details'))

@section('page.content')
    <div class="card card-padding">
        <div class="card-header">
            <h2>
                {{ trans('messages.Open New Ticket') }}
                <small>
                    {{ trans('messages._ticket_details_subtitle') }}
                </small>
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>{{ trans('messages.New Ticket') }}</h3>
        </div>
        <div class="card-body card-padding clearfix">
            <form role="form" @submit.prevent="create">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="c-black f-500 m-b-20 select-nomargin">{{ trans('messages.Department') }}</p>
                            <div class="form-group">
                                <div class="select">
                                    <div class="form-control">
                                        <select class="form-control" v-model="object.department">
                                            <option value="technical">{{ trans('messages.Technical') }}</option>
                                            <option value="bug">{{ trans('messages.Bug') }}</option>
                                            <option value="improvement">{{ trans('messages.Improvement') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->superAdmin)
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="c-black f-500 m-b-20 select-nomargin">{{ trans('messages.Ticket Status') }}</p>
                                <div class="form-group">
                                    <div class="select">
                                        <div class="form-control">
                                            <select class="form-control" v-model="object.status" :disabled="{{!$superAdmin}}">
                                                <option value="open">{{ trans('messages.Open') }}</option>
                                                <option value="closed">{{ trans('messages.Closed') }}</option>
                                                <option value="awaiting_response">{{ trans('messages.Awaiting Response') }}</option>
                                                <option value="pending_close">{{ trans('messages.Pending Close') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group fg-line">
                                <label for="subject">{{ trans('messages.Subject') }}</label>
                                <input type="text" name="subject" class="form-control input-sm" placeholder="{{ trans('messages.Subject') }}" v-model="object.subject">
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="fg-line">
                                    <textarea class="form-control auto-size" name="content" rows="10" placeholder="{{ trans('messages.request_command') }}" v-model="content"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-1 pull-right">
                                <button type="submit" class="btn btn-primary bgm-red">{{ trans('messages.SAVE') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer"></div>
    </div>
@stop

@section('page.footer')
    <script>
        new Vue({
            el: 'body',
            data: {
                object: {
                    id: null,
                    messages: [],
                    department: 'technical',
                    status: 'open',
                },
                content: '',
            },
            computed: {
                localizedObjects: function() {
                    localized = [];
                    if (object.status == 'open') {
                        object.status = '{!! trans('messages.prop.open') !!}';
                    } else if (object.type == 'closed') {
                        object.status = '{!! trans('messages.prop.closed') !!}';
                    } else if (object.type == 'awaiting_response') {
                        object.status = '{!! trans('messages.prop.awaiting_response') !!}';
                    } else if (object.type == 'pending_close') {
                        object.status = '{!! trans('messages.prop.pending_close') !!}';
                    }
                    return localized;
                }
            },
            methods: {
                create: function create(event) {
                    Vue.http.post('/api/v3/tickets', {
                        'content': this.content,
                        'status': this.object.status,
                        'department': this.object.department,
                        'subject': this.object.subject,
                    }).then(function success(response) {
                        location.href = '/admin/support/' + response.data.id + '/edit';
                    });
                }
            },
            ready: function ready() {
            }
        });
    </script>
@stop