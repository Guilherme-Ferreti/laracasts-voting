<x-modal-confirm 
    livewireEventToOpenModal="markAsNotSpamCommentWasSet"
    eventToCloseModal="commentWasMarkedAsNotSpam"
    modalTitle="{{ __('Reset Spam Counter') }}"
    modalDescription="{{ __('Are you sure you want to mark this comment as NOT spam? This will reset the spam counter to 0.') }}"
    modalConfirmButtonText="{{ __('Reset Spam Counter') }}"
    wireClick="markAsNotSpam"
/>