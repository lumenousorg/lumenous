<template>
    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Lumenous is open source!</h4>
                Click on icon to check it out on github. <a href="https://github.com/cballou/lumenaries.org" target="_blank"><i class="fa fa-github fa-2x"></i></a>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Number Of Payouts</span>
                        <span class="info-box-number">{{ stats.numberOfPayouts }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Personal Payout</span>
                        <span class="info-box-number">{{ stats.totalAccountPayouts | toLumens }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Charity Payout</span>
                        <span class="info-box-number">{{ stats.totalCharityPayouts | toLumens }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!--        <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-body">
                                <div class="col-sm-6 col-xs-12">
                                    <p class="text-center">
                                        <strong>Web Traffic Overview</strong>
                                    </p>
                                    <canvas id="trafficBar" ></canvas>
                                </div>
                                <hr class="visible-xs-block">
                                <div class="col-sm-6 col-xs-12">
                                    <p class="text-center">
                                        <strong>Language Overview</strong>
                                    </p>
                                    <canvas id="languagePie"></canvas>
                                </div>
                            </div>
                        </div>
                        <small class="space"><b>Pro Tip</b> Don"t forget to star us on github!</small>
                    </div>
                </div>-->
        <!-- /.row -->

        <!-- Main row -->
        <!--        <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
        
                            <div class="info-box-content">
                                <span class="info-box-text">Inventory</span>
                                <span class="info-box-number">5,200</span>
        
                                <div class="progress">
                                    <div class="progress-bar" style="width: 50%"></div>
                                </div>
                                <span class="progress-description">
                                    50% Increase
                                </span>
                            </div>
                             /.info-box-content 
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>
        
                            <div class="info-box-content">
                                <span class="info-box-text">Mentions</span>
                                <span class="info-box-number">92,050</span>
        
                                <div class="progress">
                                    <div class="progress-bar" style="width: 20%"></div>
                                </div>
                                <span class="progress-description">
                                    20% Increase
                                </span>
                            </div>
                             /.info-box-content 
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>
        
                            <div class="info-box-content">
                                <span class="info-box-text">Downloads</span>
                                <span class="info-box-number">114,381</span>
        
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    70% Increase
                                </span>
                            </div>
                             /.info-box-content 
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>
        
                            <div class="info-box-content">
                                <span class="info-box-text">Direct Messages</span>
                                <span class="info-box-number">163,921</span>
        
                                <div class="progress">
                                    <div class="progress-bar" style="width: 40%"></div>
                                </div>
                                <span class="progress-description">
                                    40% Increase
                                </span>
                            </div>
                             /.info-box-content 
                        </div>
                    </div>
                </div>-->
        <!-- /.row -->
    </section>
    <!-- /.content -->
</template>

<script>
    import Chart from 'chart.js';
    import api from '../../api';
    import { mapState } from 'vuex';


    export default {
        data() {
            return {
                generateRandomNumbers(numbers, max, min) {
                    var a = []
                    for (var i = 0; i < numbers; i++) {
                        a.push(Math.floor(Math.random() * (max - min + 1)) + max)
                    }
                    return a
                },
                stats: [],
                url: '/dashboard'
            }
        },
        computed: {
            coPilotNumbers() {
                return this.generateRandomNumbers(12, 1000000, 10000)
            },
            personalNumbers() {
                return this.generateRandomNumbers(12, 1000000, 10000)
            },
            isMobile() {
                return (window.innerWidth <= 800 && window.innerHeight <= 600)
            },
            ...mapState([
                    'user'
            ])
        },
        methods: {
            getStats() {
                api.request('get', this.url + '/users/' + this.user.id + '/stats')
                        .then(response => {
                            if (response.status !== 200) {
                                this.error = response.statusText
                                return
                            }
                            this.stats = response.data
                        })
                        .catch(error => {
                            // Request failed.
                            console.log('error', error.response)
                            this.error = error.response.statusText
                        })
            }
        },
        mounted() {
//            this.$nextTick(() => {
//                var ctx = document.getElementById('trafficBar').getContext('2d')
//                var config = {
//                    type: 'line',
//                    data: {
//                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
//                        datasets: [{
//                                label: 'CoPilot',
//                                fill: false,
//                                borderColor: '#284184',
//                                pointBackgroundColor: '#284184',
//                                backgroundColor: 'rgba(0, 0, 0, 0)',
//                                data: this.coPilotNumbers
//                            }, {
//                                label: 'Personal Site',
//                                borderColor: '#4BC0C0',
//                                pointBackgroundColor: '#4BC0C0',
//                                backgroundColor: 'rgba(0, 0, 0, 0)',
//                                data: this.personalNumbers
//                            }]
//                    },
//                    options: {
//                        responsive: true,
//                        maintainAspectRatio: !this.isMobile,
//                        legend: {
//                            position: 'bottom',
//                            display: true
//                        },
//                        tooltips: {
//                            mode: 'label',
//                            xPadding: 10,
//                            yPadding: 10,
//                            bodySpacing: 10
//                        }
//                    }
//                }
//
//                new Chart(ctx, config) // eslint-disable-line no-new
//
//                var pieChartCanvas = document.getElementById('languagePie').getContext('2d')
//                var pieConfig = {
//                    type: 'pie',
//                    data: {
//                        labels: ['HTML', 'JavaScript', 'CSS'],
//                        datasets: [{
//                                data: [56.6, 37.7, 4.1],
//                                backgroundColor: ['#00a65a', '#f39c12', '#00c0ef'],
//                                hoverBackgroundColor: ['#00a65a', '#f39c12', '#00c0ef']
//                            }]
//                    },
//                    options: {
//                        responsive: true,
//                        maintainAspectRatio: !this.isMobile,
//                        legend: {
//                            position: 'bottom',
//                            display: true
//                        }
//                    }
//                }
//
//                new Chart(pieChartCanvas, pieConfig) // eslint-disable-line no-new
//            });
            this.getStats();
        }
    }
</script>
<style>
    .info-box {
        cursor: pointer;
    }
    .info-box-content {
        text-align: center;
        vertical-align: middle;
        display: inherit;
    }
    .fullCanvas {
        width: 100%;
    }
</style>
