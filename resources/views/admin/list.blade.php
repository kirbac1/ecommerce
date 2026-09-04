@extends('partials.admin.page')
@section('sidebar.username', $user_contact->nameAndSurname)
@section('page.title', 'List example page.')

@section('page.content')
    <div class="card">
        <div class="card-header">
            <h2>Hover Row <small>Enable a hover state on table rows within a tbody</small></h2>
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
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Nickname</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(key, val) in objects | filterBy searchFilter">
                        <td>@{{ val.id }}</td>
                        <td>@{{ val.firstName }}</td>
                        <td>@{{ val.lastName }}</td>
                        <td>@{{ val.userName }}</td>
                        <td>@{{ val.nickName }}</td>
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
                objects: [
                    {
                        id: 1,
                        firstName: 'Alexandra',
                        lastName: 'Christopher',
                        userName: '@makinton',
                        nickName: 'Ducky'
                    },{
                        id: 2,
                        firstName: 'Madeleine',
                        lastName: 'Hollaway',
                        userName: '@hollaway',
                        nickName: 'Cheese'
                    },{
                        id: 3,
                        firstName: 'Benjamin',
                        lastName: 'Parnell',
                        userName: '@wayne234',
                        nickName: 'Pokie'
                    },{
                        id: 4,
                        firstName: 'Katherine',
                        lastName: 'Buckland',
                        userName: '@anitabelle',
                        nickName: 'Wokie'
                    },{
                        id: 5,
                        firstName: 'Sebastian',
                        lastName: 'Johnston',
                        userName: '@sebastian',
                        nickName: 'Jaycee'
                    },{
                        id: 6,
                        firstName: 'Mitchell',
                        lastName: 'Belkitt',
                        userName: '@belkitt4u',
                        nickName: 'Goat'
                    },{
                        id: 7,
                        firstName: 'Nicholas',
                        lastName: 'Walmart',
                        userName: '@mwalmart',
                        nickName: 'Spike'
                    }
                ]
            }
        });
    </script>
@stop