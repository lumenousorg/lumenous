<template>
    <div class="container container-table">
        <div class="row vertical-10p">
            <div class="container">

                <div class="text-center">
                    <h1>
                        <strong>LUMENOUS</strong><br>
                        <span class="text-muted">REGISTER</span>
                    </h1>
                </div>

                <div class="col-md-4 col-sm-offset-4 text-center">
                    <!-- register form -->
                    <form class="ui form registerForm"  @submit.prevent="register">

                        <div v-bind:class="{ 'has-error': validationErrors.email }">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                                <input class="form-control" name="email" placeholder="Email" type="email" v-model="email">
                            </div>
                            <span class="help-block" v-if="validationErrors.email">{{ validationErrors.email[0] }}</span>
                        </div>

                        <div v-bind:class="{ 'has-error': validationErrors.email_confirmation }">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                                <input class="form-control" name="email_confirmation" placeholder="Confirm Email" type="email" v-model="email_confirmation">
                            </div>
                            <span class="help-block" v-if="validationErrors.email_confirmation">{{ validationErrors.email_confirmation[0] }}</span>
                        </div>

                        <div v-bind:class="{ 'has-error': validationErrors.stellar_public_key }">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-key"></i></span>
                                <input class="form-control" name="stellar_public_key" placeholder="Stellar Lumen Public Key" type="text" v-model="stellar_public_key">
                            </div>
                            <span class="help-block" v-if="validationErrors.stellar_public_key">{{ validationErrors.stellar_public_key[0] }}</span>
                        </div>

                        <div v-bind:class="{ 'has-error': validationErrors.password }">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input class="form-control" name="password" placeholder="Password" type="password" v-model="password">
                            </div>
                            <span class="help-block" v-if="validationErrors.password">{{ validationErrors.password[0] }}</span>
                        </div>

                        <div v-bind:class="{ 'has-error': validationErrors.password_confirmation }">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input class="form-control" name="password_confirmation" placeholder="Confirm Password" type="password" v-model="password_confirmation">
                            </div>
                            <span class="help-block" v-if="validationErrors.password_confirmation">{{ validationErrors.password_confirmation[0] }}</span>
                        </div>

                        <button type="submit" v-bind:class="'btn btn-primary btn-lg ' + loading">
                            Register
                            <i class="fa fa-fw fa-angle-right"></i>
                        </button>
                    </form>

                    <p class="mt-1">
                        Already have an account? <a href="/login"><small>Login</small></a>.
                    </p>

                    <!-- errors -->
                    <div v-if=response class="text-red"><p>{{response}}</p></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../api'

            export default {
                name: 'Register',
                data(router) {
                    return {
                        section: 'Register',
                        loading: '',
                        email: '',
                        email_confirmation: '',
                        stellar_public_key: '',
                        password: '',
                        password_confirmation: '',
                        response: '',
                        validationErrors: []
                    }
                },
                methods: {
                    register() {
                        const {email, email_confirmation, stellar_public_key, password, password_confirmation} = this

                        this.toggleLoading()
                        this.resetResponse()
                        this.$store.commit('TOGGLE_LOADING')

                        /* Making API call to authenticate a user */
                        api.request('post', '/register', {email, email_confirmation, stellar_public_key, password, password_confirmation})
                                .then(response => {
                                    this.toggleLoading()

                                    var data = response.data
                                    this.$router.push({path: '/login', query: {message: data.message}})

                                })
                                .catch(error => {
                                    this.$store.commit('TOGGLE_LOADING')

                                    // handle validation errors
                                    if (error.response.status === 422) {

                                        this.response = error.response.data.message
                                        this.validationErrors = error.response.data.errors
                                    } else {
                                        this.response = 'Server appears to be offline'

                                    }

                                    this.toggleLoading()
                                })
                    },
                    toggleLoading() {
                        this.loading = (this.loading === '') ? 'loading' : ''
                    },
                    resetResponse() {
                        this.response = ''
                    }
                }
            }
</script>

<style>
    html, body, .container-table {
        height: 100%;
        background-color: #282B30 !important;
    }
    .container-table {
        display: table;
        color: white;
    }
    .vertical-center-row {
        display: table-cell;
        vertical-align: middle;
    }
    .vertical-20p {
        padding-top: 20%;
    }
    .vertical-10p {
        padding-top: 10%;
    }
    .logo {
        width: 15em;
        padding: 3em;
    }
    .registerForm .input-group {
        padding-bottom: 1em;
        height: 4em;
    }
    .input-group input {
        height: 4em;
    }
</style>
