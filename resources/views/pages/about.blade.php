@extends('layouts.landing')

@section('title', 'About Us')

@section('content')
    <section class="section fill-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h1 class="text-center">About Us</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <p class="text-center">
                        <img src="/img/craftblue.png" title="Craft Blue" />
                    </p>

                    <p class="lead">
                        Lumenous.org is built and managed by Craft Blue. Craft Blue is comprised of a niche team of
                        <a href="https://www.craftblue.com" target="_blank">expert Laravel and PHP consultants</a> who specialize
                        in the development of custom web applications. We're headquartered in Charlotte, NC
                        and have a track record of remotely building and delivering complex projects for startups, businesses, and
                        digital agencies alike.
                    </p>

                    <p>
                        Craft Blue has been a long time proponent and supporter of Stellar. We believe in internally
                        funding and building applications on top of Stellar's Horizon network which complement the organization's
                        mission and goals.
                    </p>

                    <p>
                        We're also the team behind <a href="https://www.stellarvanity.com" target="_blank">Stellar Vanity</a>,
                        which is a vanity name generator for Stellar public keys. They're great for branding your Stellar
                        public keys.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

