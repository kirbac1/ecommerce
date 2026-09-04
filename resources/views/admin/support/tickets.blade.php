@extends('partials.admin.page')
@section('sidebar.username', $user_contact->nameAndSurname)
@section('page.title', 'Support')

@section('page.content')
    <div class="card">
        <div class="card-header">
            <h2>Support tickets <small>Enable a hover state on table rows within a tbody</small></h2>
        </div>

        <div id="data-table-basic-header" class="bootgrid-header container-fluid">
            <div class="row">
                <div class="col-sm-12 actionBar">
                    <div class="search form-group">
                        <div class="input-group">
                            <span class="md icon input-group-addon glyphicon-search"></span>
                            <input type="text" class="search-field form-control" placeholder="Search" v-model="searchFilter">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover ticketList">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <!-- active | info | warning | success | danger -->
                <tr data-ticketid="@{{ val.code }}"
                    v-for="(key, val) in objects | filterBy searchFilter"
                    v-bind:class="{ danger: val.status == 'open', warning: val.status == 'awaiting_response', info: val.status == 'pending_close' }"
                    v-on:click="openTicket(val.code)"
                >
                    <td>@{{ val.code }}</td>
                    <td>@{{ val.subject }}</td>
                    <td>@{{ val.status }}</td>
                    <td>@{{ val.created_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('page.footer')
    <script>
        new Vue({
            el: 'body',
            data: {
                searchFilter: '',
                objects: []
            },
            ready: function ready() {
                Vue.http.get('/api/v2/tickets').then(function success(response) {
                    this.$set('objects', response.data);
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