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
                            Product Table
                        </span>

                        <a class="action-link" tabindex="-1" @click="showCreateProductForm">
                            Create New Product
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- No products Notice -->
                    <p class="mb-0" v-if="products.length === 0">
                        You have not created any personal access products.
                    </p>

                    <!-- Personal Access products -->
                    <table class="table table-borderless mb-0" v-if="products.length > 0">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Price</th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="product in products">
                                <!-- Client Name -->
                                <td style="vertical-align: middle;">
                                    {{ product.name }}
                                </td>
                                <td style="vertical-align: middle;">
                                    {{ product.description }}
                                </td>
                                <td style="vertical-align: middle;">
                                    N{{ product.price }}
                                </td>

                                <!-- Delete Button -->
                                <!-- <td style="vertical-align: middle;">
                                    <a class="action-link text-danger" @click="revoke(product)">
                                        Delete
                                    </a>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Token Modal -->
        <div class="modal fade" id="modal-create-product" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Create Product
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
                                    <input id="create-token-name" type="text" class="form-control" name="name" v-model="form.name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Description</label>

                                <div class="col-md-6">
                                    <input id="create-token-description" type="text" class="form-control" name="description" v-model="form.description">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Price</label>

                                <div class="col-md-6">
                                    <input id="create-token-price" type="text" class="form-control" name="price" v-model="form.price">
                                </div>
                            </div>


                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Create
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

                products: [],

                form: {
                    name: '',
                    description: '',
                    price: '',
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
                this.getProducts();

                $('#modal-create-product').on('shown.bs.modal', () => {
                    $('#create-product-name').focus();
                });
            },

            /**
             * Get all of the personal access products for the user.
             */
            getProducts() {
                axios.get('/admin/products')
                        .then(response => {
                            this.products = response.data;
                        });
            },
            /**
             * Show the form for creating new products.
             */
            showCreateProductForm() {
                $('#modal-create-product').modal('show');
            },

            /**
             * Create a new personal access token.
             */
            store() {
                
                //this.product = null;
                this.form.errors = [];

                axios.post('/admin/products/create', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.description = '';
                            this.form.price = '';

                            this.products.push(response.data.product);
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
                        $('#modal-create-product').modal('hide');
                        this.getProducts();
            },
            /**
             * Revoke the given token.
             */
            revoke(product) {
                axios.delete('/admin/products/delete/' + product.id)
                        .then(response => {
                            this.getProducts();
                        });
            }
        }
    }
</script>
