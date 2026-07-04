@php $title = __('trivia.title'); @endphp
<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($screen === 'welcome')
                @include('livewire.trivia-quiz-welcome')

            @elseif ($screen === 'quiz')
                @include('livewire.trivia-quiz-question')

            @elseif ($screen === 'results')
                @include('livewire.trivia-quiz-results')
            @endif

        </div>
    </div>
</div>
