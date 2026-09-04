<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta id="_token" value="{{ csrf_token() }}">
    <link rel="stylesheet" href="/assets/css/bootstrap-3.3.6.min.css">
    <title>Test.</title>
</head>
<body>
    <div id="app" class="container">
        <userlist></userlist>
    </div>

    <script src="/assets/js/jquery-2.2.0.min.js"></script>
    <script src="/assets/js/bootstrap-3.3.6.min.js"></script>
    <script src="/assets/js/vue-1.0.17.js"></script>
    <script src="/assets/js/vue-resource-0.7.0.js"></script>
    <script src="/assets/js/vue-router-0.7.11.js"></script>
    <script>
        Vue.component('userlist', {
            template: $('#userlist-template')
        });
    </script>

    <script src="/assets/js/app.js"></script>

    <template id="userlist-template">
        <h1>User list</h1>
        <ul class="list-group">
            <li class="list-group-item" v-for="user in users">
                @{{ user.email }}
            </li>
        </ul>
    </template>
</body>
</html>