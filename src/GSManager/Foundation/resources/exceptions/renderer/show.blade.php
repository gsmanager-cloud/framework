<x-gsmanager-exceptions-renderer::layout :$exception>
    <div class="renderer container mx-auto lg:px-8">
        <x-gsmanager-exceptions-renderer::navigation :$exception />

        <main class="px-6 pb-12 pt-6">
            <div class="container mx-auto">
                <x-gsmanager-exceptions-renderer::header :$exception />

                <x-gsmanager-exceptions-renderer::trace-and-editor :$exception />

                <x-gsmanager-exceptions-renderer::context :$exception />
            </div>
        </main>
    </div>
</x-gsmanager-exceptions-renderer::layout>
