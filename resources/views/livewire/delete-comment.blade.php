<x-modal-confirm 
    livewireEventToOpenModal="deleteCommentWasSet"
    eventToCloseModal="commentWasDeleted"
    modalTitle="{{ __('Delete Comment') }}"
    modalDescription="{{ __('Are you sure you want to delete this comment? This action cannot be undone.') }}"
    modalConfirmButtonText="{{ __('Delete') }}"
    wireClick="deleteComment"
/>