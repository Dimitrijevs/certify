<nav class="fixed w-full backdrop-blur-sm left-0 right-0 z-50 py-6" x-data="{ open: false }">
    <div class="max-w-5xl mx-4 sm:mx-auto">
        <div class="flex justify-between items-center">
            <h1 class="text-4xl heading hover:text-gray-600 duration-200 cursor-pointer">
                certifyNow
            </h1>

            <div>
                <x-tabler-menu @click="open = !open"
                    class="text-gray-800 cursor-pointer hover:text-gray-600 duration-200" />
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div x-show="open" class="fixed inset-0 h-screen bg-black bg-opacity-60 z-40"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    <div x-show="open" class="absolute right-0 top-0 w-[360px] bg-white shadow-md h-screen z-50 py-4 px-6"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <div class="flex w-full justify-between items-center mb-8">
            <h2 class="heading text-xl">Navigation</h2>
            <div>
                <x-tabler-x @click="open = false"
                    class="text-gray-800 cursor-pointer hover:text-gray-600 duration-200" />
            </div>
        </div>

        <ul class="space-y-3 text-md">
            <li class="hover:text-gray-600 text-gray-800 duration-300 hover:scale-105"><a href="#top-section"
                    @click="open = false">Getting Started!</a></li>
            <li class="hover:text-gray-600 text-gray-800 duration-300 hover:scale-105"><a href="#online-learning"
                    @click="open = false">Advantages of Online Learning</a></li>
            <li class="hover:text-gray-600 text-gray-800 duration-300 hover:scale-105"><a href="#wait" @click="open = false">Why To
                    Wait?</a></li>
            <li class="hover:text-gray-600 text-gray-800 duration-300 hover:scale-105"><a href="#getting-started"
                    @click="open = false">Why choose Us!</a></li>
            <li class="hover:text-gray-600 text-gray-800 duration-300 hover:scale-105"><a href="#faq"
                    @click="open = false">Frequently Asked Questiona</a></li>
        </ul>
    </div>
</nav>
