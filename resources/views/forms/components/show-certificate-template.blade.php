<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="grid grid-cols-2 gap-4 h-[320px]">
            <div @click="state = 1"
                class="col-span-1 overflow-hidden flex items-center justify-center cursor-pointer hover:opacity-80 duration-150">
                <img src="{{ asset('images/logos/bg-certificate.png') }}" alt="certificate layout 1"
                    class="max-w-full max-h-full object-contain rounded-lg"
                    :class="state == 1 ? 'border-8 border-blue-500' : 'border-8 border-gray-400'">
            </div>

            <div @click="state = 2"
                class="col-span-1 overflow-hidden flex items-center justify-center cursor-pointer hover:opacity-80 duration-150">
                <img src="{{ asset('images/logos/bg-certificate-2.png') }}" alt="certificate layout 2"
                    class="max-w-full max-h-full object-contain rounded-lg"
                    :class="state == 2 ? 'border-8 border-blue-500' : 'border-8 border-gray-400'">
            </div>
        </div>

        <input x-model="state" hidden />
    </div>
</x-dynamic-component>
