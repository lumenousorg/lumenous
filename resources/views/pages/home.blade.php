@extends('layouts.landing')

@section('title', 'Home')

@section('content')
    <div class="hero-image">
        <div class="hero-text top">
            <h1><strong>Donate</strong> part of your <strong>Stellar Inflation</strong> to <strong>Charity</strong></h1>
            <p class="lead">Join our inflation pool and become a <strong>lumenary</strong>.</p>
        </div>
        <div class="hero-text bottom">
                <p class="lead text-center text-highlight">
                    <strong>Donation Address:</strong>
                </p>

                <div class="address input-group">
                    <input id="public-key" type="text" value="{{ lumenous\Services\StellarService::getPublicKey() }}" class="form-control input-lg" onClick="this.select();" readonly />

                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-lg copy-public-key" data-clipboard-target="#public-key" title="Copy to Clipboard">
                            <i class="fa fa-copy"></i>
                        </button>
                    </span>
                </div>

                @if (lumenous\Services\StellarService::usingTestnet())
                    <p class="text-center mt-1">
                        <small class="text-highlight">
                            Use the <a href="https://www.stellar.org/laboratory/#account-creator?network=test">Stellar Labs: Account Creator</a>
                            and fund it via friendbot to test our service.
                        </small>
                    </p>
                @else
                    <p class="text-center mt-1">
                        <small class="text-highlight">
                            <span class="fa fa-fw fa-heart text-danger"></span> Donations to this address are both welcomed and appreciated <span class="fa fa-fw fa-heart text-danger"></span>
                        </small>
                    </p>
                @endif
            </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center">What is Lumenous?</h2>
                    <p class="lead">
                        Lumenous.org is a 100% open source Stellar.org <a href="https://www.stellar.org/developers/guides/concepts/inflation.html" target="_blank">inflation pool</a>.
                        It seeks to not only be a source of inspiration for developers, but a viable alternative to other fee-based and
                        closed-source community pools. It aligns with the vision of Stellar.org, a nonprofit, who is on a mission to:
                    </p>

                    <blockquote>
                        <em>
                            "...expand access to low-cost financial
                            services to fight poverty and maximize individual potential"
                        </em>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    <section class="section container-alt">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center">Charities We Support</h2>
                    <p class="lead text-center">
                        We meticulously select charities that have both a global impact and a coveted four star
                        rating from Charity Navigator.
                    </p>

                    <div class="card fill-highlight2 text-light">
                        <div class="content">
                            <img src="" class="img-responsive" />
                            <h3 class="card-title mt-0">Direct Relief</h3>
                            <div class="card-description">
                                <p>
                                    Life-saving medical aid without regard to politics, religion, or ability to pay.
                                    Improve the health and lives of people affected by poverty and emergencies.
                                </p>

                                <a href="https://www.charitynavigator.org/index.cfm?bay=search.summary&orgid=3626" target="_blank" class="btn btn-sm btn-info">View Rating</a>
                                <a href="http://www.directrelief.org/" target="_blank" class="btn btn-sm btn-info">Visit Website</a>
                            </div>
                        </div>
                    </div>

                    <div class="card fill-highlight2 text-light">
                        <div class="content">
                            <img src="" class="img-responsive" />
                            <h3 class="card-title mt-0">Water Mission</h3>
                            <div class="card-description">
                                <p>
                                    With your help, Water Mission builds sustainable solutions for people suffering from water
                                    scarcity. They provide hope through solar-powered safe water solutions that meet the needs
                                    of entire communities.
                                </p>

                                <a href="https://www.charitynavigator.org/index.cfm?bay=search.summary&orgid=10709" target="_blank" class="btn btn-sm btn-info">View Rating</a>
                                <a href="http://www.watermission.org/" target="_blank" class="btn btn-sm btn-info">Visit Website</a>
                            </div>
                        </div>
                    </div>

                    <div class="card fill-highlight2 text-light">
                        <div class="content">
                            <img src="" class="img-responsive" />
                            <h3 class="card-title mt-0">MAP International</h3>
                            <div class="card-description">
                                <p>
                                    MAP International is a Christian organization providing life-changing medicines and
                                    health supplies to people in need. MAP serves all people, regardless of religion, gender,
                                    race, nationality, or ethnic background.
                                </p>

                                <a href="https://www.charitynavigator.org/index.cfm?bay=search.summary&orgid=4042" target="_blank" class="btn btn-sm btn-info">View Rating</a>
                                <a href="http://www.map.org/" target="_blank" class="btn btn-sm btn-info">Visit Website</a>
                            </div>
                        </div>
                    </div>

                    <div class="card fill-highlight2 text-light">
                        <div class="content">
                            <img src="" class="img-responsive" />
                            <h3 class="card-title mt-0">Environmental Defense Fund</h3>
                            <div class="card-description">
                                <p>
                                    Since 1967, the EDF has found innovative ways to solve big environmental problems. The
                                    EDF focuses on stabilizing the climate, feeding the world, and protecting our health.
                                </p>

                                <a href="https://www.charitynavigator.org/index.cfm?bay=search.summary&orgid=3671" target="_blank" class="btn btn-sm btn-info">View Rating</a>
                                <a href="https://www.edf.org/" target="_blank" class="btn btn-sm btn-info">Visit Website</a>
                            </div>
                        </div>
                    </div>

                    <div class="card fill-highlight2">
                        <div class="content">
                            <img src="" class="img-responsive" />
                            <h3 class="card-title mt-0">Books For Africa</h3>
                            <div class="card-description">
                                <p>
                                    Books for Africa collects, sorts, ships, and distributes books to students of all ages
                                    in Africa. Their goal: to end the book famine in Africa. Books For Africa remains
                                    the largest shipper of donated text and library books to the African continent.
                                </p>

                                <a href="https://www.charitynavigator.org/index.cfm?bay=search.summary&orgid=3373" target="_blank" class="btn btn-sm btn-info">View Rating</a>
                                <a href="http://www.booksforafrica.org/" target="_blank" class="btn btn-sm btn-info">Visit Website</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center">Become a <strong>Lumenary</strong></h2>
                    <p class="lead text-center">Members of Lumenous, called <strong>lumenaries</strong>, believe in giving back to society.</p>

                    <p class="mb-2">
                        To become a member of the Lumenous family, you simply need to set your Stellar Account's inflation destination
                        to our donation address (public key).
                    </p>

                    <p class="text-center text-highlight mb-0">
                        <strong>Donation Address:</strong>
                    </p>

                    <div class="address input-group mb-2">
                        @if (lumenous\Services\StellarService::usingTestnet())
                            <span class="input-group-addon bg-warning">TESTNET</span>
                        @endif

                        <input id="public-key" type="text" value="{{ lumenous\Services\StellarService::getPublicKey() }}" class="form-control" onClick="this.select();" readonly />

                        <span class="input-group-btn">
                            <button class="btn btn-primary copy-public-key" data-clipboard-target="#public-key" title="Copy to Clipboard">
                                <i class="fa fa-copy"></i>
                            </button>
                        </span>
                    </div>

                    <p>
                        Once completed, you can optionally register for an account with us in order to set
                        your charity donation percentage as well as service fee percentage. All you need to begin is
                        your public key.
                    </p>

                    <p class="text-center">
                        <a href="/register" class="btn btn-lg btn-primary">
                            Register Now
                            <i class="fa fa-fw fa-angle-right"></i>
                        </a>
                    </p>

                    <p class="text-center text-highlight">
                        <small>
                            By default, you will receive <strong>100% payout</strong>.
                        </small>
                    </p>

                    <h4 class="text-center mt-3 mb-1">
                        Not sure how to set your inflation destination?
                    </h4>
                    <p class="text-center">
                        Read our guide on
                        <a href="{{ route('set-inflation') }}">How to Set Your Stellar Inflation Destination</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
