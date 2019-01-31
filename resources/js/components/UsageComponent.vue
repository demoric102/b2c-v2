<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div>
            <div class="card card-default">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            Usage Table
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- No products Notice -->
                    <p class="mb-0" v-if="users.length === 0">
                        You have no users.
                    </p>

                    <!-- Personal Access users -->
                    <table class="table mb-0" v-if="users.length > 0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Hits</th>
                                <th>Misses</th>
                                <!-- <th>Logs</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="user in users">
                                <!-- Client Name -->
                                <td style="vertical-align: middle;">
                                    {{ user.name }}
                                </td>
                                <td style="vertical-align: middle;">
                                    {{ user.email }}
                                </td>
                                <td style="vertical-align: middle;">
                                    {{ user.hits }}
                                </td>
                                <td style="vertical-align: middle;">
                                    {{ user.misses }}
                                </td>
                                <!-- <td style="vertical-align: middle;">
                                    <a class="action-link text-info" v-bind:href="'/admin/view/usage/'+user.id">Usage</a>
                                </td> -->

                                <!-- Delete Button -->
                                <td style="vertical-align: middle;">
                                    <a class="action-link text-danger" @click="showEditUserForm(user)">
                                        Edit Features
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>

        <!-- Create Token Modal -->
        <div class="modal fade" id="modal-edit-user" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Modify user
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="form.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in form.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Token Form -->
                        <form role="form" @submit.prevent="store">
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Name</label>

                                <div class="col-md-6">
                                    <input readonly id="create-token-name" type="text"  class="form-control" name="name" @keyup.enter="edit" v-model="editForm.name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Email</label>

                                <div class="col-md-6">
                                    <input readonly id="create-token-description" type="text" class="form-control" name="description" @keyup.enter="edit" v-model="editForm.email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Phone</label>

                                <div class="col-md-6">
                                    <input readonly id="create-token-price" type="text" class="form-control" name="price" @keyup.enter="edit" v-model="editForm.phone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Report Type</label>
                                <div class="col-md-6">
                                    <select name="report_type" class="form-control" @keyup.enter="edit" v-model="editForm.report_type">
                                        <option value="6111">Consumer Basic</option>
                                        <option value="104">Consumer Basic Premium</option>
                                        <option value="6401">Consumer Compact</option>
                                        <option value="106">Consumer Compact Premium</option>
                                        <option value="6110">Consumer Classic</option>
                                        <option value="105">Consumer Classic Premium</option>
                                        <option value="6113">Corporate Basic</option>
                                        <option value="6402">Corporate Compact</option>
                                        <option value="6112">Corporate Classic</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Response Type</label>
                                <div class="col-md-6">
                                    <select name="response_type" class="form-control" @keyup.enter="edit" v-model="editForm.response_type">
                                        <option value="3">PDF</option>
                                        <option value="1">XML</option>
                                        <option value="4">PDF and XML</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Live Request Username</label>

                                <div class="col-md-6">
                                    <input id="create-token-name" type="text"  class="form-control" name="live_request_username" @keyup.enter="edit" v-model="editForm.live_request_username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Live Request Password</label>

                                <div class="col-md-6">
                                    <input id="create-token-description" type="text" class="form-control" name="live_request_password" @keyup.enter="edit" v-model="editForm.live_request_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Activation Status</label>

                                <div class="col-md-6">
                                    <select name="activate" class="form-control" @keyup.enter="edit" v-model="editForm.activate">
                                        <option value="active">Activate</option>
                                        <option value="inactive">Deactivate</option>
                                    </select>
                                </div>
                            </div>


                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="update">
                            Modify
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                id: '',
                user: '',
                users: [],

                editForm: {
                    errors: [],
                    name: '',
                    email: '',
                    phone: '',
                    response_type: '',
                    report_type: '',
                    live_request_username: '',
                    live_request_password: '',
                    activate: ''
                },

                editUserForm: [],

                form: {
                    activate: '',
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getUsers();

                $('#modal-create-product').on('shown.bs.modal', () => {
                    $('#create-product-name').focus();
                });
            },

            /**
             * Get all of the personal access users for the user.
             */
            getUsers() {
                axios.get('/admin/users')
                        .then(response => {
                            this.users = response.data;
                        });
            },
            /**
             * Show the form for creating new users.
             */

            showEditUserForm(user) {
                this.editForm.id = user.id;
                this.editForm.name = user.name;
                this.editForm.email = user.email;
                this.editForm.phone = user.phone;
                this.editForm.activate = user.activate;
                this.editForm.response_type = user.response_type;
                this.editForm.report_type = user.product_type;
                this.editForm.live_request_username = user.live_request_username;
                this.editForm.live_request_password = user.live_request_password;

                $('#modal-edit-user').modal('show');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/admin/user/update/' + this.editForm.id,
                    this.editForm, '#modal-edit-user'
                );
            },

            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getUsers();

                        form.name = '';
                        form.email = '';
                        form.phone = '';
                        form.activate = '';
                        form.response_type = '';
                        form.report_type = '';
                        form.errors = [];

                        $(modal).modal('hide');
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Create a new personal access token.
             */
            store(user) {
                
                //this.product = null;
                this.form.errors = [];

                axios.put('/admin/users/edit' + user.id)
                        .then(response => {
                            this.form.activate = user.activate;
                            this.form.response_type = user.response_type;
                            this.form.report_type = user.report_type;
                            this.users.push(response.data.user);
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
                        $('#modal-edit-user').modal('hide');
                        this.getUsers();
            },
            /**
             * Revoke the given token.
             */
        }
    }
</script>
