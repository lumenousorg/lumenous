@extends('layouts.landing')

@section('title', 'How to Set Your Stellar Inflation Destination')

@section('content')
    <section class="section fill-dark">
        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="text-center">How to Set Your Stellar Inflation Destination</h1>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <p class="lead mb-3">
                    In this guide, we'll cover how you can manually set your Stellar Inflation Destination.
                </p>

                <ol class="fancy-list">
                    <li>
                        Navigate to the official <a href="https://www.stellar.org/laboratory/#txbuilder?network=public" target="_blank">Stellar Laboratory: Transaction Builder</a>.
                        This page will allow you to craft and send a custom transaction operation to set your inflation destination.
                        Ensure you are on the Transaction Builder tab and using the public network as depicted in the photo.

                        <img src="" class="" />
                    </li>
                    <li>
                        Copy your Stellar Account's public key from your wallet or computer.
                        Paste it into the input labeled <em>"Source Account"</em>.
                    </li>
                    <li>
                        A blue button will appear entitled <em>"Fetch next sequence number for account starting with..."</em>
                        under the <strong>Transaction Sequence Number</strong> section. Click it to automatically
                        populate the input with the correct sequence number.
                    </li>
                    <li>
                        Using your browser, scroll down the page until you see the section with the label <strong>Operation Type</strong>.
                        Click on the corresponding dropdown menu and select the option <em>"Set Options"</em>.
                    </li>
                    <li>
                        Below this dropdown, you will need to copy and paste our Stellar Account's public key into the
                        field entitled <em>"Inflation Destination"</em>. Our inflation destination is:

                        <div class="address input-group">
                            <input id="public-key" type="text" value="{{ lumenous\Services\StellarService::getPublicKey() }}" class="form-control" onClick="this.select();" readonly />

                            <span class="input-group-btn">
                                <button class="btn btn-primary copy-public-key" data-clipboard-target="#public-key" title="Copy to Clipboard">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </span>
                        </div>
                    </li>
                    <li>
                        Scroll down to the bottom of the page and click the blue button entitled <em>"Sign in Transaction Signer"</em>.
                    </li>
                    <li>
                        You will be redirected to a new page which contains a section entitled <strong>Add Signers</strong>.
                        You will need to copy your Stellar Account's private key from your wallet or computer and paste
                        it into the input labeled <em>"Add Signer"</em>.
                    </li>
                    <li>
                        Scroll down to the bottom of the page and click the blue button entitled <em>"Submit to Post Transaction endpoint"</em>.
                    </li>
                    <li>
                        You will be redirected to a new page to complete the submission of your fully build transaction.
                        Click on the blue button entitled <em>"Submit"</em> in order to send your transaction to the
                        Horizon network. Upon submission, you will see a JSON encoded response confirming the successful
                        change of inflation destination. Congratulations! If you haven't done so already, now is the
                        perfect time to <a href="/register">register with us</a> and optionally set your donation percentage.
                    </li>
                </ol>
            </div>
        </div>
    </section>
@endsection

