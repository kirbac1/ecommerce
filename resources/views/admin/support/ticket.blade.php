@extends('partials.admin.page')
@section('page.meta')
    @parent
    <meta name="ticket_id" content="{{ $ticket_id }}">
@stop

@section('sidebar.username', $user_contact->nameAndSurname)
@section('page.title', 'Support')

@section('page.content')
    <div class="card m-b-0" id="messages-main">
        <div class="single-ticket ms-body">
            <div class="listview lv-message">
                <div class="lv-header-alt bgm-white">
                    <div id="ms-menu-trigger">
                        <div class="line-wrap">
                            <div class="line top"></div>
                            <div class="line center"></div>
                            <div class="line bottom"></div>
                        </div>
                    </div>

                    <div class="lvh-label hidden-xs">
                        <div class="lv-avatar pull-left">
                            <img src="/assets/img/profile-pics/1.jpg" alt="">
                        </div>
                        <span class="c-black">@{{ subject }}</span>
                    </div>

                    <ul class="lv-actions actions">
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" aria-expanded="true">
                                <i class="md md-sort"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="#">Latest</a>
                                </li>
                                <li>
                                    <a href="#">Oldest</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="lv-body">
                    <div class="lv-item media" v-for="(key, val) in messages" v-bind:class="{ 'left': val.fromSupport == '1', 'right': val.fromSupport == '0' }">
                        <div class="lv-avatar"
                             v-bind:class="{ 'pull-left': val.fromSupport == '1', 'pull-right': val.fromSupport == '0' }">
                            <img v-if="val.fromSupport == '1'" src="/assets/img/profile-pics/4.jpg" alt="">
                            <img v-else src="/assets/img/profile-pics/1.jpg" alt="">
                        </div>
                        <div class="media-body">
                            <div class="ms-item">
                                @{{ val.content }}
                            </div>
                            <small class="ms-date"><i class="md md-access-time"></i> @{{ val.created_at }}</small>
                        </div>
                    </div>
                </div>

                <div class="lv-footer ms-reply">
                    <textarea placeholder="Add message..."></textarea>

                    <button><i class="md md-send"></i></button>
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
                messages: [],
                subject: '',
                status: '',
                date: ''
            },
            ready: function ready() {
                var ticket_id = document.querySelector("meta[name='ticket_id']").content;
                Vue.http.get('/api/v2/tickets/' + ticket_id).then(function success(response) {
                    console.log(response.data[0]);
                    this.$set('messages', response.data[0].messages);
                    this.$set('subject', response.data[0].subject);
                    this.$set('status', response.data[0].status);
                    this.$set('date', response.data[0].created_at);
                }.bind(this), function error(response) {
                    console.log('FAILURE', response);
                });
            },
            methods: {
                openTicket: function openTicket(code) {
                    location.href = '/admin/support/' + code;
                }
            }
        });
    </script>
@stop