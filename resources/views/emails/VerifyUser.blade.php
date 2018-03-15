{{ $user->email }}

@component('mail::button', ['url' => url('/users/verify',[$user->verification_code->code]), 'color' => 'green'])
Verify email
@endcomponent