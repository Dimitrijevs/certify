<div class="flex flex-col text-sm">
    <a href="/" class="w-full flex space-x-2 p-2.5 border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 duration-300 items-center">
        <x-tabler-home class="h-5 w-5 text-gray-400 dark:text-gray-500" />
        <p class="text-gray-700 dark:text-gray-200">{{ __('other.home_page') }}</p>
    </a>

    <a href="/app/customer-questions/create" class="w-full flex space-x-2 p-2.5 border-b border-gray-100 hover:bg-gray-50 dark:hover:bg-gray-800 duration-300 items-center">
        <x-tabler-folder-question class="h-5 w-5 text-gray-400 dark:text-gray-500" />
        <p class="text-gray-700 dark:text-gray-200">{{ __('question.add_request') }}</p>
    </a>
</div>
