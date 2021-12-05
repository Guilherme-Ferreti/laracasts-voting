<x-modal-confirm 
    livewireEventToOpenModal="markAsNotSpamCommentWasSet"
    eventToCloseModal="commentWasMarkedAsNotSpam"
    modalTitle="Reset Spam Counter"
    modalDescription="Are you sure you want to mark this comment as NOT spam? This will reset the spam counter to 0."
    modalConfirmButtonText="Reset Spam Counter"
    wireClick="markAsNotSpam"
/>