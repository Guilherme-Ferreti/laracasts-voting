<x-modal-confirm 
    eventToOpenModal="custom-show-not-spam-modal"
    eventToCloseModal="ideaWasMarkedAsNotSpam"
    modalTitle="{{ __('Reset Spam Counter') }}"
    modalDescription="{{ __('Are you sure you want to mark this idea as NOT spam? This will reset the spam counter to 0.') }}"
    modalConfirmButtonText="{{ __('Reset Spam Counter') }}"
    wireClick="markAsNotSpam"
/>