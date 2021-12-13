@component('mail::message')
# {{ __('Idea Status Update') }}

{{ __('The idea') }}: {{ $idea->title }}

{{ __('has been updated to a status of') }}:

{{ __('messages.ideas.' . $idea->status->name) }}

@component('mail::button', ['url' => route('idea.show', $idea)])
{{ __('View Idea') }}
@endcomponent

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
