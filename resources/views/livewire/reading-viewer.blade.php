<div class="prose dark:prose-invert max-w-none">
    {!! Str::markdown($step->reading_content ?? '', ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}
</div>
