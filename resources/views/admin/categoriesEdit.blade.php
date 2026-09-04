@extends('partials.admin.page')
@section('sidebar.username', $user->nameAndSurname)
@section('page.title', trans('messages.Edit Categories'))

@section('page.content')
    <div class="card">
        <div class="card-header">
            <div class="btn-group btn-group pull-right">
                <button class="btn btn-primary bgm-red" v-on:click.prevent="update">{{ trans('messages.SAVE') }}</button>
            </div>
            <h2>
                {{ trans('messages.Categories') }}
                <small>
                    {{ trans('messages._edit_categories_subtitle') }}
                </small>
            </h2>
        </div>

        <div class="card-body card-padding">
            <div id="categoryList">
                <ul id="sTreePlus" class="thumbnail categories">
                    <item v-for="(index, category) in categories" :model="category" :dblclick="rename"></item>
                </ul>
            </div>
        </div>
    </div>
@stop

@section('page.footer')
    <script type="text/x-template" id="item-template">
        <li :id="model.id" class="categoryParent">
            <div :id="model.id">
                <h4 v-if="!editing" @dblclick="rename(model)" @mouseover="hover" @mouseleave="leaveHover">
                    <span class="md md-view-headline draggable-anchor"></span>
                    <span class="category_container">@{{ model.name }}
                        <button v-if="hovering" class="btn btn-default btn-icon floating-button floating-button-add" @click="addCategory(model)"><i class="md md-plus"></i></button>
                        <button v-if="hovering" class="btn btn-default btn-icon floating-button floating-button-del" @click="deleteCategory(model)"><i class="md md-delete"></i></button>
                    </span>
                </h4>
                <div v-if="editing">
                    <h4>
                        <span class="md md-view-headline draggable-anchor"></span>
                        <input
                                class="form-control input-sm"
                                v-model="model.name"
                                v-category-focus="category == editedCategory"
                                @keyup.enter="doneEdit(model)"
                                @keyup.esc="cancelEdit(model)"
                        >
                    </h4>

                </div>
                <ul v-if="hasChildren">
                    <item class="item" v-for="(index, model) in model.children" :model="model" :id="model.id"></item>
                </ul>
            </div>
        </li>
    </script>

    <style>
        .category_container {
            position: relative;
        }
        .btn-icon.floating-button {
            position: absolute;
            padding: 3px;
            right: -30px;
            top: -18px;
            width: 27px;
            height: 27px;
        }
        .btn-icon.floating-button-add {
            -ms-transform: rotate(45deg); /* IE 9 */
            -webkit-transform: rotate(45deg); /* Chrome, Safari, Opera */
            transform: rotate(45deg);
            right: -30px;
            background-color: #8BC34A !important;
            color: white !important;
        }
        .md-plus:before {
            content: "\f29a";
        }
        .btn-icon.floating-button-del {
            right: -60px;
            background-color: #F44336  !important;
            color: white !important;
        }
        .categories, #sortableListsPlaceholder, #sortableListsBase, .sortableListsBase ul, .sortableListsBase li, .sortableListsOpen, .sortableListsOpen ul, .sortableListsCurrent ul, .sortableListsCurrent li {
            list-style: none !important;
            border: 0;
            padding-left: 0;
        }
        .sortableListsBase li, .sortableListsOpen li, .sortableListsCurrent li { padding-left: 20px; padding-right: 20px; }
        .categories .caption { padding: 5px;}
        .categories ul {
            padding-left: 20px;
        }
        .categories ul { list-style: none; }
        .draggable-anchor { font-size: 1.2em; }
        #categoryList .form-control { display: inline-block; width: auto; font-size: 1.0em;}
    </style>

    <script src="/assets/js/jquery-sortable-lists.js"></script>
    <script>
        Vue.component('item', {
            template: '#item-template',
            props: {
                model: [],
                editing: false,
                originalValue: '',
                hovering: false,
            },
            methods: {
                addCategory(item) {
                    Vue.http.post('/api/v3/categories/addChild', {
                        name: '{{ trans('messages.Untitled') }}',
                        parent: item.id,
                    }).then(function success(response) {
                        vue.$set('categories', response.data);
                    }), function error(response) {
                        console.error('FAILURE while adding category to ' + item.id);
                    };
                },
                deleteCategory(item) {
                    Vue.http.delete('/api/v3/categories/' + item.id).then(function success(response) {
                        vue.$set('categories', response.data);
                    }, function error(response) {
                        console.error('FAILURE deleting ' + item.id);
                    });
                },
                rename: function rename(item) {
                    item.editing = true;
                    this.editing = true;
                    this.originalValue = this.model.name;
                },
                cancelEdit: function cancelEdit(item) {
                    this.editing = false;
                    this.model.name = this.originalValue;
                },
                doneEdit: function doneEdit(item) {
                    this.editing = false;
                    Vue.http.put('/api/v3/categories/' + this.model.id, this.model).then(function success(response) {
                        //
                    }, function error(response) {
                        console.error('FAILURE updating categories.');
                    });
                },
                hover: function hover(e) {
                    this.hovering = true;
                },
                leaveHover: function leaveHover(e) {
                    this.hovering = false;
                }
            },
            data: function() {
                return { open: false}
            },
            computed: {
                hasChildren: function() {
                    return this.model.children && this.model.children.length;
                }
            },

            // a custom directive to wait for the DOM to be updated
            // before focusing on the input field.
            // http://vuejs.org/guide/custom-directive.html
            directives: {
                'category-focus': function (value) {
                    if (!value) {
                        return;
                    }
                    var el = this.el;
                    Vue.nextTick(function () {
                        el.focus();
                    });
                }
            }
        });

        var vue = new Vue({
            el: 'body',
            data: {
                categories: [],
            },
            computed: {
            },
            methods: {
                update: function update() {},
            },
            ready: function ready() {
                var that = this;
                Vue.http.get('/api/v3/categories').then(function success(response) {
                    that.$set('categories', response.data);
                }, function error(response) {
                    console.error('FAILURE retrieving categories.');
                });
            },
        });

        var optionsPlus = {
            insertZonePlus: true,
            placeholderCss: {'background-color': '#FDFDFD'},
            hintCss: {'background-color':'#F1F8E9'},
            opener: {
                active: true,
                as: 'html',  // if as is not set plugin uses background image
                close: '<i class="fa fa-minus c3"></i>',
                open: '<i class="fa fa-plus"></i>',
                openerCss: {
                    'display': 'inline-block',
                    'float': 'left',
                    'margin-left': '-35px',
                    'margin-right': '5px',
                    'font-size': '1.1em'
                }
            },
            isAllowed: function isAllowed(element, target) {
                // If belongs to the .categoryList (root container), you can't add it.
                depth = target.parents('.categoryParent').length;
                return depth > 0;
            },
            complete: function complete(element) {
                var parent = element.parents('.categoryParent');
                var depth = element.parents('.categoryParent').length;
                var left = element.prevUntil('ul');
                var right = element.nextUntil('ul');
                var oldel = element;

                Vue.http.put('/api/v3/categories/' + element.attr('id') + '/move', {
                    parent: parent.attr('id'),
                    left: left.attr('id'),
                    right: right.attr('id'),
                    depth: depth,
                }).then(function success(response) {
                    vue.$set('categories', response.data);
                    oldel.remove();
                },function error(response) {
                    console.error('FAILURE during move ' + myId + ' under ' + parentId);
                });

            }
        };

        $('#sTreePlus').sortableLists( optionsPlus );
    </script>
@stop
