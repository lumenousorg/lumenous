<template>
    <div class="container container-table">
        <div class="row vertical-10p">
            <div class="container">
                <h3 class="text-center">Account Public Key Not Verified. Please follow the steps below to verify your public key and proceed.</h3>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="well">
                            <ol>
                                <li>Visit the Stellar Laboratory transaction builder page using this <a href="https://www.stellar.org/laboratory/#txbuilder?network=public">link</a> </li>
                                <li>In the <code>Source Account</code> field add your public key <code>{{ user.stellar_public_key }}</code></li>
                                <li>In the <code>Sequence Number</code> field generate the next sequence number by clicking on the <code>Fetch next sequence number for account</code> button</li>
                                <li>Scroll down and in the <code>Operation Type</code> field choose <code>Manage Data</code></li>
                                <li>Set the <code>Entry name</code> field to <code>lmnry_verify_key</code></li>
                                <li>Set the <code>Entry value</code> field to <code>{{ user.lmnry_verify_key }}</code></li>
                                <li>Scroll down and you should see a success message displayed, to proceed click on the <code>Sign in Transaction Signer</code> button</li>
                                <li>On the Transaction Signer page, scroll down and add your Private Key in the <code>Add Signer</code> field</li>
                                <li>You should now be able to see a <code>Transaction signed!</code> success message, now we need to click on the <code>Submit to Post Transaction endpoint</code></li>
                                <li>The last step is to actually submit the transaction using the <code>Submit</code> button. 
                                    That's it, If all the steps were done correctly, your account should be verified shortly.
                                    Please <a href="/login">Re-login</a> to access your account after It has been verified.
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import api from '../api'
            import { mapState } from 'vuex'

            export default {
                name: 'VerifyPublicKey',
                data(router) {
                    return {
                        section: 'VerifyPublicKey',
                        loading: ''
                    }
                },
                computed: {
                    ...mapState([
                            'user'
                    ])
                },
                methods: {

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

    .vertical-20p {
        padding-top: 20%;
    }
    .vertical-10p {
        padding-top: 10%;
    }
    ol{
        font-size: 21px;
        /*text-align: center;*/
        /*list-style-position: inside;*/
    }
    .well{
        background-color: #4e4e4e;
    }


</style>
