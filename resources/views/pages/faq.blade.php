@extends('layouts.landing')

@section('title', 'Frequently Asked Questions (FAQ)')

@section('content')
    <section class="section fill-dark">
        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="text-center">Frequently Asked Questions</h1>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="faq">
                <h3>Why should I set an inflation destination to a community pool?</h3>
                <p>By using a community pool, you can earn free lumens. Stellar has built in a 1% yearly inflation rate
                which gets returned back to lumen holders based on their percentage of ownership. In order to earn
                your free lumens, you need to have enough voting power. The only way to achieve a significant amount
                of voting power is by pooling your votes with other lumen holders (1 vote per lumen).
                </p>
                <p>
                    You can learn more about how Stellar inflation works and how to calculate the minimum votes
                    necessary by reading their
                    <a href="https://www.stellar.org/developers/guides/concepts/inflation.html">official documentation</a>.
                </p>
            </div>

            <div class="faq">
                <h3>How do I set or change my inflation destination?</h3>
                <p>
                    The Stellar API supports setting an Inflation Destination option to a third party public key (account ID).
                    Most wallets also allow for you to enter and save an Inflation Destination. If you're using
                    a wallet that doesn't allow setting a custom inflation destination, you should ask them
                    why.
                </p>
                <p>
                    If you do not use a wallet, you can use Stellar's own
                    <a href="https://www.stellar.org/laboratory/#txbuilder?network=public">Transaction Builder</a>
                    to manually create a transaction to set your Inflation Destination. We have created a detailed
                    guide on <a href="{{ route('set-inflation') }}">how to manually set your Stellar inflation destination</a>.
                </p>
            </div>

            <div class="faq">
                <h3>How often is inflation run?</h3>
                <p>
                    Inflation is triggered once weekly by an API call. It's historically been distributed to the pools
                    between Monday night and Tuesday morning. While our system is mostly automated, the payout process
                    is triggered manually as it's necessary to manually sign the transactions for security. We're based
                    in Eastern Standard Time, so you should receive a payout early Tuesday morning.
                </p>
            </div>

            <div class="faq">
                <h3>How many Lumens will I receive from the inflation pool payout?</h3>
                <p>
                    To get an estimate of how many lumens you will receive in the next weekly payout, enter your public key.
                </p>
            </div>

            <div class="faq">
                <h3>Why should I use Lumenous.org over the alternatives?</h3>
                <p>
                    Great question. Our best answer is <em>transparency</em> and <em>technology</em>.
                    You can freely view and contribute to the source code running Lumenous.org on Github.
                    This includes everything, including tests of how we verify code correctness for stroop
                    calculations and payouts to both charities and individuals.
                </p>

                <p>
                    In regards to fees, you can receive a 100% payout if you'd like. We won't judge. Setting a
                    charitable donation percentage is entirely up to you.
                </p>

                <p>
                    In addition to this, we're also the first pool to support a user verification and login system to
                    check your own personal payout history (including charity donations). You may find this useful for
                    any future tax implications.
                </p>
            </div>

            <div class="faq">
                <h3>Is Lumenous.org safe to use?</h3>
                <p>
                    The short answer is yes: You never share your private key with us. Your precious lumens are safe.
                </p>
                <p>
                    In regards to our pool and payouts, we're looking to add support of multi-sig as the underlying
                    PHP SDK is improved upon. If you'd like to help in that department, please contact us or submit
                    a pull request.

                    Our server was configured with a plethora of security enhancements. It's been configured with
                    IDS software, fail2ban, a very closed firewall with whitelisted IP access, and not a single
                    private key in sight for performing the payout handling.
                </p>
            </div>

            <div class="faq">
                <h3>Is Lumenous.org free?</h3>
                <p>
                    Yes, you are free to specify your own charity donation percentage as well as
                    a donation percentage to Lumenous.org for operation. You don't need to pay anybody anything,
                    but we'd hope you'd consider donating to a charitable cause.
                </p>
            </div>
        </div>
    </section>

    <section class="container-alt">
        <div class="container">
            <h2 class="text-center">Become a <strong>Lumenary</strong></h2>
            <p class="lead text-center">Members of Lumenous, called <strong>lumenaries</strong>, believe in giving back to society.</p>
        </div>
    </section>
@endsection
