<div
    x-data="{ isOpen: false }"
    class="relative"
>
    <button
        type="button"
        @click="
            isOpen = !isOpen
            if (isOpen) {
                $nextTick(() => $refs.comment.focus())
            }
        "
        class="flex items-center justify-center h-11 w-32 text-sm bg-blue text-white font-semibold rounded-xl border border-blue hover:bg-blue-hover transition duration-150 ease-in px-6 py-3"
    >
        {{ __('Reply') }}
    </button>
    <div
        class="absolute z-10 w-64 md:w-104 text-left font-semibold text-sm bg-white shadow-dialog rounded-xl mt-2"
        x-cloak
        x-show="isOpen"
        x-init="
            Livewire.on('commentWasAdded', () => {
                isOpen = false
            })

            Livewire.hook('message.processed', (message, component) => {
                if (['gotoPage', 'previousPage', 'nextPage'].includes(message.updateQueue[0].method)) {
                    const firstComment = document.querySelector('.comment-container:first-child')
                    firstComment.scrollIntoView({ behavior: 'smooth'})
                }
                
                if (['commentWasAdded', 'statusWasUpdated'].includes(message.updateQueue[0].payload.event)
                        && message.component.fingerprint.name === 'idea-comments') {
                    const lastComment = document.querySelector('.comment-container:last-child')
                    lastComment.scrollIntoView({ behavior: 'smooth'})
                    lastComment.classList.add('bg-green-50')
                    setTimeout(() => {
                        lastComment.classList.remove('bg-green-50')
                    }, 5000)
                }
            })

            @if (session('scrollToComment'))
                console.log('{{ session('scrollToComment') }}')
                const commentToScrollTo = document.querySelector('#comment-{{ session('scrollToComment') }}')
                commentToScrollTo.scrollIntoView({ behavior: 'smooth'})
                commentToScrollTo.classList.add('bg-green-50')
                setTimeout(() => {
                    commentToScrollTo.classList.remove('bg-green-50')
                }, 5000)
            @endif
        "
        x-transition.origin.top.left
        @click.away="isOpen = false"
        @keydown.escape.window="isOpen = false"
    >
        @auth
            <form wire:submit.prevent="addComment" action="#" class="space-y-4 px-4 py-6">
                <div>
                    <textarea wire:model="comment" x-ref="comment" name="post_comment" id="post_comment" cols="30" rows="4" required class="w-full text-sm bg-gray-100 rounded-xl placeholder-gray-900 border-none px-4 py-2" placeholder="{{ __('Go ahead, don\'t be shy. Share your thoughts...') }}"></textarea>
                    @error('comment')
                        <p class="text-red text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col md:flex-row items-center md:space-x-3">
                    <button
                        type="submit"
                        class="flex items-center justify-center h-11 w-full md:w-1/2 text-sm bg-blue text-white font-semibold rounded-xl border border-blue hover:bg-blue-hover transition duration-150 ease-in px-6 py-3"
                    >
                        {{ __('Post Comment') }}
                    </button>
                </div>
            </form>
        @else
            <div class="px-4 py-6">
                <p class="font-normal">{{ __('Please login or create an account to post a comment') }}.</p>
                <div class="flex items-center space-x-3 mt-8">
                    <a
                        wire:click.prevent="redirectToLogin"
                        href="{{ route('login') }}"
                        class="w-1/2 h-11 text-sm text-center bg-blue text-white font-semibold rounded-xl hover:bg-blue-hover transition duration-150 ease-in px-6 py-3"
                    >
                        {{ __('Login') }}
                    </a>
                    <a
                        wire:click.prevent="redirectToRegister"
                        href="{{ route('register') }}"
                        class="flex items-center justify-center w-1/2 h-11 text-xs bg-gray-200 font-semibold rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3"
                    >
                        {{ __('Sign Up') }}
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>
