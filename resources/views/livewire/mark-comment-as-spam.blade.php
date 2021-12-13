<x-modal-confirm 
    livewireEventToOpenModal="markAsSpamCommentWasSet"
    eventToCloseModal="commentWasMarkedAsSpam"
    modalTitle="{{ __('Mark Comment as Spam') }}"
    modalDescription="{{ __('Are you sure you want to mark this comment as spam?') }}"
    modalConfirmButtonText="{{ __('Mark as Spam') }}"
    wireClick="markAsSpam"
/>