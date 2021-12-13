<x-modal-confirm 
    eventToOpenModal="custom-show-delete-modal"
    eventToCloseModal="ideaWasDeleted"
    modalTitle="{{ __('Delete Idea') }}"
    modalDescription="{{ __('Are you sure you want to delete this idea? This action cannot be undone.') }}"
    modalConfirmButtonText="{{ __('Delete') }}"
    wireClick="deleteIdea"
/>