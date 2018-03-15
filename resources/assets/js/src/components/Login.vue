<template>
    <div class="container container-table">
        <div class="row vertical-10p">
            <div class="container">
                <div class="col-sm-12 text-center" v-if="message">
                    <p class="text-primary">{{ message }}</p>
                </div>

                <div class="text-center">
                    <h1>
                        <strong>LUMENOUS</strong><br>
                        <span class="text-muted">LOGIN</span>
                    </h1>
                </div>

                <div class="text-center col-md-4 col-sm-offset-4">
                    <!-- login form -->
                    <form class="ui form loginForm"  @submit.prevent="checkCreds">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                            <input class="form-control" name="email" placeholder="Email" type="text" v-model="email">
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                            <input class="form-control" name="password" placeholder="Password" type="password" v-model="password">
                        </div>

                        <button type="submit" v-bind:class="'btn btn-primary btn-lg ' + loading">
                            Login
                            <i class="fa fa-fw fa-angle-right"></i>
                        </button>
                    </form>

                    <p class="mt-1">
                        <a href="/password/reset" class="pull-left"><small>Forgot Password?</small></a>
                        <a href="/register" class="pull-right"><small>Register</small></a>
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
            import config from '../config'

            export default {
                name: 'Login',
                data(router) {
                    return {
                        section: 'Login',
                        loading: '',
                        email: '',
                        password: '',
                        response: '',
                        message: ''
                    }
                },
                mounted() {
                    // `this` points to the vm instance
                    this.message = this.$route.query.message;
                },
                methods: {
                    checkCreds() {
                        const {email, password} = this

                        this.toggleLoading()
                        this.resetResponse()
                        this.$store.commit('TOGGLE_LOADING')

                        /* Making API call to authenticate a user */
                        api.request('post', '/oauth/token', {'username': email, password, 'grant_type': 'password', 'client_id': config.client_id, 'client_secret': config.client_secret, 'scope': '*'}, '')
                                .then(response => {
                                    this.toggleLoading()

                                    var data = response.data

                                    /* Setting user in the state and caching record to the localStorage */
                                    if (data.access_token) {
                                        var token = 'Bearer ' + data.access_token

                                        this.$store.commit('SET_TOKEN', token)

                                        if (window.localStorage) {

                                            window.localStorage.setItem('token', token)

                                            api.request('get', '/user')
                                                    .then(response => {
                                                        this.toggleLoading()

                                                        var data = response.data
                                                        this.$store.commit('SET_USER', data.user)
                                                        window.localStorage.setItem('user', JSON.stringify(data.user))
                                                        this.$router.push(data.redirect ? data.redirect : '/dashboard')

                                                    })
                                                    .catch(error => {
                                                        this.$store.commit('TOGGLE_LOADING')

                                                        this.toggleLoading()
                                                    })
                                        }

                                    }
                                })
                                .catch(error => {
                                    this.$store.commit('TOGGLE_LOADING')
                                    console.log(error)
                                    if (error.response.status === 401) {
                                        /* Checking if error object was returned from the server */
                                        if (error.response.data.error === 'invalid_credentials') {
                                            this.response = error.response.data.message
                                        }
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
    .loginForm .input-group {
        padding-bottom: 1em;
        height: 4em;
    }
    .input-group input {
        height: 4em;
    }
</style>
