<nav class="fixed w-full backdrop-blur-sm left-0 right-0 z-50 py-6" x-data="{
    open: false,
    isDark: false,
    changeTheme() {
        this.isDark = !this.isDark;

        if (this.isDark) {
            document.documentElement.classList.add('dark');
            document.body.classList.remove('bg-cyan-200');
            document.body.classList.add('bg-gray-900');

            localStorage.setItem('dark', 'true');
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('bg-gray-900');
            document.body.classList.add('bg-cyan-200');

            localStorage.removeItem('dark');
        }
    },
    editSidebar() {
        this.open = !this.open;

        if (this.open) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    }
}" x-init="() => {
    if (localStorage.getItem('dark')) {
        document.documentElement.classList.add('dark');
        document.body.classList.add('bg-gray-900');
        isDark = true;
    } else {
        document.body.classList.add('bg-cyan-200');
        isDark = false;
    }
}">
    <div class="sm:max-w-5xl mx-6 lg:mx-auto ">
        <div class="flex justify-between items-center">
            <a href="#top-section">
                <h1
                    class="text-4xl heading hover:text-gray-600 dark:hover:text-cyan-100 duration-200 cursor-pointer">
                    certify
                </h1>
            </a>

            <div class="flex space-x-4 items-center">
                <div class="">
                    <x-tabler-sun @click="changeTheme()" x-show="!isDark"
                        class="text-gray-800 dark:text-cyan-200 cursor-pointer hover:text-gray-600 dark:hover:text-cyan-100 duration-200" />

                    <x-tabler-moon @click="changeTheme()" x-show="isDark"
                        class="text-gray-800 dark:text-cyan-200 cursor-pointer hover:text-gray-600 dark:hover:text-cyan-100 duration-200" />
                </div>

                <div class="">
                    <x-tabler-menu @click="editSidebar()"
                        class="text-gray-800 dark:text-cyan-200 cursor-pointer hover:text-gray-600 dark:hover:text-cyan-100 duration-200" />
                </div>

                <div class="">
                    <a href="/app/login">
                        <x-tabler-user
                            class="text-gray-800 dark:text-cyan-200 cursor-pointer hover:text-gray-600 dark:hover:text-cyan-100 duration-200" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div x-show="open" class="fixed inset-0 h-screen bg-black bg-opacity-70 z-40"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    <div x-show="open"
        class="absolute right-0 top-0 w-[360px] bg-white dark:bg-gray-900 shadow-md h-screen z-50 py-4 px-6"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
        <div class="flex w-full justify-between items-center mb-8">
            <h2 class="heading text-xl">Navigation</h2>
            <div>
                <x-tabler-x @click="editSidebar()" class="paragraph cursor-pointer hover:text-gray-600 duration-200" />
            </div>
        </div>

        <ul class="space-y-3 text-md">
            <li class="paragraph duration-300 hover:scale-105"><a href="#top-section" @click="open = false">Getting
                    Started!</a></li>
            <li class="paragraph duration-300 hover:scale-105"><a href="#online-learning"
                    @click="open = false">Advantages of Online Learning</a></li>
            <li class="paragraph duration-300 hover:scale-105"><a href="#wait" @click="open = false">Why To
                    Wait?</a></li>
            <li class="paragraph duration-300 hover:scale-105"><a href="#getting-started" @click="open = false">Why
                    choose Us!</a></li>
            <li class="paragraph duration-300 hover:scale-105"><a href="#faq" @click="open = false">Frequently Asked
                    Questiona</a></li>
        </ul>
    </div>
</nav>
