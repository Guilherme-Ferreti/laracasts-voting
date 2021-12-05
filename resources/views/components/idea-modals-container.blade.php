@push('modals')
    @can('update', $idea)
        <livewire:edit-idea :idea="$idea" />
    @endcan

    @can('delete', $idea)
        <livewire:delete-idea :idea="$idea" />   
    @endcan

    @auth
        <livewire:mark-idea-as-spam :idea="$idea" />
    @endauth

    @can('markAsNotSpam', \App\Models\Idea::class)
        <livewire:mark-idea-as-not-spam :idea="$idea" />
    @endcan

    @can('markAsNotSpam', \App\Models\Comment::class)
        <livewire:mark-comment-as-not-spam />
    @endcan

    @auth
        <livewire:edit-comment />
        <livewire:delete-comment />
        <livewire:mark-comment-as-spam />
    @endauth
@endpush