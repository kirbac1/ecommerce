@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Ticket Details'))

@section('page.content')
    <div class="card card-padding">
        <div class="card-header">
            @if($ticket !== null)
                <div class="btn-group btn-group pull-right">
                    @if($superAdmin)
                        <button class="btn btn-primary bgm-red" v-on:click.prevent="destroy">{{ trans('messages.DELETE') }}</button>
                    @endif
                </div>
            @else
                <button class="btn btn-primary bgm-red pull-right" v-on:click.prevent="create" :disabled="!createbuttonEnabled">{{ trans('messages.CREATE') }}</button>
            @endif
            <div class="pull-right" style="margin-right: 20px; font-size: 1.2em;">Status: <b>@{{ object.status }}</b></div>
            <h2>
                {{ $ticket->subject or ''}}
                <small>
                    {{ trans('messages._ticket_details_subtitle') }}
                </small>
            </h2>
        </div>
    </div>

    <div class="card-body card-padding clearfix" v-for="message in object.messages">
        <div class="card">
            <div class="card-header">
                <div class="col-sm-10" style="padding-left: 0 !important;">
                    <h2 v-if="message.sentBySupport !== '0'"><i class="md md-school"></i>&nbsp;&nbsp;@{{ message.user.name }} @{{ message.user.surname }}</h2>
                    <h2 v-else><i class="md md-person"></i>&nbsp;&nbsp;@{{ message.user.name }} @{{ message.user.surname }}</h2>
                </div>
                <div class="col-sm-2">
                    <h5>@{{ message.created_at }}</h5>
                </div>
            </div>
            <div class="card-body card-padding">
                <p>@{{ message.content }}</p>
            </div>
        </div>
    </div>

    @if($ticket->status !== 'closed')
        <div class="card">
            <div class="card-header">
                <h3>{{ trans('messages.Answer') }}</h3>
            </div>
            <div class="card-body card-padding clearfix">
                <form role="form" @submit.prevent="update">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10">
                                <textarea class="form-control" name="content" placeholder="{{ trans('messages.answer_command') }}" data-autosize-on="true" style="overflow: hidden; word-wrap: break-word; height: 43.8px;" v-model="content"></textarea>
                            </div>
                            <div class="col-sm-1 pull-right">
                                <button type="submit" class="btn btn-primary bgm-red">{{ trans('messages.SAVE') }}</button>
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
                                                <select class="form-control" v-model="object.status">
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
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    @endif
@stop

@section('page.footer')
    <script>
        new Vue({
            el: 'body',
            data: {
                object: {
                    id: null,
                    messages: [],
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
                update: function save(event) {
                    Vue.http.post('/api/v3/tickets', {
                        'content': this.content,
                        'ticket_id': this.object.id,
                        'status': this.object.status,
                    }).then(function success(response) {
                        location.href = '/admin/support';
                    });
                },
                destroy: function destroy(event) {
                    Vue.http.delete("/api/v3/tickets/{{ $ticket->id or ''}}").then(function success(response) {
                        location.href = '/admin/support';
                    });
                },
            },
            ready: function ready() {
                @if($ticket)
                    Vue.http.get('/api/v3/tickets/{{ $ticket->id }}').then(function success(response) {
                        this.$set('object', response.data);
                    }.bind(this), function error(response) {
                        console.log('FAILURE', response);
                    });
                @endif
            }
        });
    </script>
@stop