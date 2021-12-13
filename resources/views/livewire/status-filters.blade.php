<nav class="hidden md:flex items-center justify-between text-xs text-gray-400">
    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li>
            <a 
                wire:click.prevent="setStatus('All')" 
                href="{{ route('idea.index', ['status' => 'All']) }}" 
                class="transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'All') text-gray-900 border-blue @endif">{{ __('All Ideas') }} ({{ $statusesCount['all_statuses'] }})
            </a>
        </li>
        <li>
            <a 
                wire:click.prevent="setStatus('Considering')" 
                href="{{ route('idea.index', ['status' => 'Considering']) }}" 
                class="transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'Considering') text-gray-900 border-blue @endif">{{ __('Considering') }} ({{ $statusesCount['considering'] }})
            </a>
        </li>
        <li>
            <a 
                wire:click.prevent="setStatus('In Progress')" 
                href="{{ route('idea.index', ['status' => 'In Progress']) }}" 
                class="transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'In Progress') text-gray-900 border-blue @endif">{{ __('In Progress') }} ({{ $statusesCount['in_progress'] }})
            </a>
        </li>
    </ul>

    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li>
            <a 
                wire:click.prevent="setStatus('Implemented')" 
                href="{{ route('idea.index', ['status' => 'Implemented']) }}" 
                class="transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'Implemented') text-gray-900 border-blue @endif">{{ __('Implemented') }} ({{ $statusesCount['implemented'] }})
            </a>
        </li>
        <li>
            <a 
                wire:click.prevent="setStatus('Closed')" 
                href="{{ route('idea.index', ['status' => 'Closed']) }}" 
                class="transition duration-150 ease-in border-b-4 pb-3 hover:border-blue @if ($status === 'Closed') text-gray-900 border-blue @endif">{{ __('Closed') }} ({{ $statusesCount['closed'] }})
            </a>
        </li>
    </ul>
</nav>