<x-modal-confirm 
    eventToOpenModal="custom-show-spam-modal"
    eventToCloseModal="ideaWasMarkedAsSpam"
    modalTitle="{{ __('Mark Idea as Spam') }}"
    modalDescription="{{ __('Are you sure you want to mark this idea as spam?') }}"
    modalConfirmButtonText="{{ __('Mark as Spam') }}"
    wireClick="markAsSpam"
/>