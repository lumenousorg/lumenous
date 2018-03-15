@extends('layouts.landing')

@section('title', 'Contact Us')

@section('content')
    <section class="section fill-dark">
        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="text-center">About Us</h1>
            </div>
        </div>
    </section>

    <section class="section">

        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <form class="js-validation-fe-contact" action="/contact-us" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                        <div class="col-12">
                            <label for="name">Name</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Enter your name.." value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span id="name-error" class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-12" for="email">Email</label>
                        <div class="col-12">
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your email.." value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span id="email-error" class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row{{ $errors->has('subject') ? ' has-error' : '' }}">
                        <div class="col-12">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control form-control-lg" id="subject" name="subject" placeholder="Enter your subject.." value="{{ old('subject') }}">
                            @if ($errors->has('subject'))
                                <span id="subject-error" class="help-block">{{ $errors->first('subject') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row{{ $errors->has('message') ? ' has-error' : '' }}">
                        <label class="col-12" for="message">Message</label>
                        <div class="col-12">
                            <textarea class="form-control form-control-lg" id="message" name="message" rows="10" placeholder="Enter your message..">{{ old('message') }}</textarea>
                            @if ($errors->has('message'))
                                <span id="message-error" class="help-block">{{ $errors->first('message') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-hero btn-rounded btn-alt-primary min-width-175">
                                <i class="fa fa-send mr-5"></i>
                                Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

