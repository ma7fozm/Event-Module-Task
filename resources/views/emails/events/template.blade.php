@component('mail::message')
# {{$name}} Event Created in Pending state
Event Name : {{$name}} <br>
Event Details : {{$description}}

@component('mail::button', ['url' =>  route('index')])
Approve Event
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
