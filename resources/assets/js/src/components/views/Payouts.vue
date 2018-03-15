<template>
    <section class="content">
        <div class="row center-block">
            <h2>Overview</h2>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">All Payouts (in XLM)</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Total</th>
                                    <th>Fees</th>
                                    <th>Personal</th>
                                    <th>Charity</th>
                                    <th>Time (UTC)</th>
                                    <th>Sent</th>
                                </tr>
                                <tr v-for="payout in payouts">
                                    <td>{{ payout.id }}</td>
                                    <td>{{ payout.total_payout_amount | toLumens}}</td>
                                    <td>{{ payout.transaction_fee | toLumens}}</td>
                                    <td>{{ payout.account_payout_amount | toLumens }}</td>
                                    <td>{{ payout.charity_payout_amount | toLumens }}</td>
                                    <td>{{ payout.created_at | prettyDateTime }}</td>
                                    <td>
                                        <i v-if="payout.submitted" class="fa fa-check text-primary fa-2x"></i>
                                        <i v-if="!payout.submitted" class="fa fa-close text-danger fa-2x"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>


    </section>
</template>

<script>
    import { mapState } from 'vuex'
            import api from '../../api'
            import filters from '../../filters'



            export default {
                name: 'Payouts',
                data() {
                    return {
                        url: '/payouts',
                        payouts: []
                    }
                },
                methods: {
                    getPayouts() {
                        api.request('get', this.url + '/users/' + this.user.id)
                                .then(response => {
                                    if (response.status !== 200) {
                                        this.error = response.statusText
                                        return
                                    }
                                    this.payouts = response.data.payouts
                                })
                                .catch(error => {
                                    // Request failed.
                                    console.log('error', error.response)
                                    this.error = error.response.statusText
                                })
                    }
                },
                computed: {
                    ...mapState([
                            'user'
                    ])},
                mounted() {
                    this.getPayouts();
                }
            }
</script>

<style>

</style>
