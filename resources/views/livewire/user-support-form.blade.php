<div x-data="{
    open: false,
    handleOpen() {
        this.open = !this.open;

        if (this.open) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    }
}">
    <p class="mt-4 paragraph">Can't find the answer you're looking for? Reach out
        to our <button @click="handleOpen()" type="button"
            class="font-semibold text-blue-600 dark:text-blue-600 hover:text-blue-500 dark:hover:text-blue-500 duration-300 cursor-pointer">customer
            support</button> team.</p>

    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="flex flex-col max-w-4xl fixed top-28 w-3/5 left-1/2 transform -translate-x-1/2 bg-white p-6 shadow-lg rounded-lg z-50 dark:bg-gray-900 dark:text-white">

        <div class="w-full justify-between flex mb-4">
            <p class="heading font-semibold text-md">Support Request</p>
            <button class="text-gray-400" @click="handleOpen()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>

        <form wire:submit="save" class="w-full flex flex-col gap-2">
            <div class="space-y-4">
                <div class="w-full">
                    <label class="mb-2 heading">Title</label>
                    <x-required-sign />
                    <input type="text" name="title" wire:model="title"
                        class="w-full rounded-lg border-gray-300 max-h-[36px] dark:bg-gray-800 dark:text-white">
                    @error('title')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <label class="mb-2 heading">Description</label>
                    <x-required-sign />
                    <textarea name="description" wire:model="description" id="" rows="4"
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:text-white"></textarea>
                    @error('description')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex space-x-4">
                <button class="rounded-lg bg-blue-600 px-3 py-2 text-white duration-300 hover:bg-blue-500">
                    <span>Submit</span>
                </button>

                <button type="button" @click="handleOpen()"
                    class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 text-gray-900 dark:text-white duration-300 border border-gray-300 dark:border-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white">
                    <span>Cancel</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Overlay -->
    <div x-show="open" class="fixed inset-0 h-screen bg-black bg-opacity-70 z-40"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>
</div>
