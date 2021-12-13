@component('mail::message')
# {{ __('A comment was posted on your idea') }}

{{ $comment->user->name }} {{ __('commented on your idea') }}:

**{{ $comment->idea->title }}**

{{ __('Comment') }}: {{ $comment->body }}

@component('mail::button', ['url' => route('idea.show', $comment->idea)])
{{ __('Go to Idea') }}
@endcomponent

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
