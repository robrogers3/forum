@component('mail::message')
# You missed the last step!

In order to post threads please first confirm your email address.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm Your Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
