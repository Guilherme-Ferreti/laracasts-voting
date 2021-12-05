<x-modal-confirm 
    livewireEventToOpenModal="markAsSpamCommentWasSet"
    eventToCloseModal="commentWasMarkedAsSpam"
    modalTitle="Mark Comment as Spam"
    modalDescription="Are you sure you want to mark this comment as spam?"
    modalConfirmButtonText="Mark as Spam"
    wireClick="markAsSpam"
/>