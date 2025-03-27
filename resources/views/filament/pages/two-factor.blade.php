<x-filament-panels::page>
    <x-filament-breezy::grid-section md=1 :title="__('userCabinet.2fa_title')" :description="__('userCabinet.2fa_description')" class="gap-y-6">
        <x-filament::card>
            @if ($this->showRequiresTwoFactorAlert())
                <div style="{{ \Illuminate\Support\Arr::toCssStyles([\Filament\Support\get_color_css_variables('danger', shades: [300, 400, 500, 600])]) }}"
                    class="p-4 rounded bg-custom-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @svg('heroicon-s-shield-exclamation', 'w-5 h-5 text-danger-600')
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-danger-500">
                                {{ __('filament-breezy::default.profile.2fa.must_enable') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @unless ($user->hasEnabledTwoFactor())
                <h3 class="flex items-center gap-2 text-lg font-medium">
                    @svg('heroicon-o-exclamation-circle', 'w-6')
                    {{ __('userCabinet.2fa_not_enabled_title') }}
                </h3>
                <p class="text-sm">{{ __('userCabinet.2fa_not_enabled_description') }}</p>

                <div class="flex justify-between mt-3">
                    {{ $this->enableAction }}
                </div>
            @else
                @if ($user->hasConfirmedTwoFactor())
                    <h3 class="flex items-center gap-2 text-lg font-medium">
                        @svg('heroicon-o-shield-check', 'w-6')
                        {{ __('userCabinet.2fa_enabled') }}
                    </h3>
                    <p class="text-sm">{{ __('userCabinet.2fa_enabled_description') }}</p>
                    @if ($showRecoveryCodes)
                        <div class="px-4 space-y-3">
                            <p class="text-xs">{{ __('filament-breezy::default.profile.2fa.enabled.store_codes') }}</p>
                            <div>
                                @foreach ($this->recoveryCodes->toArray() as $code)
                                    <span
                                        class="inline-flex items-center p-1 text-xs font-medium text-gray-800 dark:text-gray-400 bg-gray-100 rounded-full dark:bg-gray-900">{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="flex justify-between mt-3">
                        {{ $this->regenerateCodesAction }}
                        {{ $this->disableAction()->color('danger') }}
                    </div>
                @else
                    <h3 class="flex items-center gap-2 text-lg font-medium">
                        <div class="border border-gray-700 rounded-full">
                            @svg('tabler-question-mark', 'w-6')
                        </div>
                        {{ __('userCabinet.2fa_enabling_title') }}
                    </h3>
                    <p class="text-sm">{{ __('userCabinet.2fa_enabling_description') }}</p>
                    <div class="flex mt-3 space-x-4 divide-x">
                        <div>
                            {!! $this->getTwoFactorQrCode() !!}
                            <p class="pt-2 text-sm">{{ __('userCabinet.setup_key') }}
                                {{ decrypt($this->user->two_factor_secret) }}</p>
                        </div>
                        <div class="px-4 space-y-3">
                            <p class="text-xs">{{ __('userCabinet.recovery_codes') }}</p>
                            <div>
                                @foreach ($this->recoveryCodes->toArray() as $code)
                                    <span
                                        class="inline-flex items-center p-1 text-xs font-medium text-gray-800 dark:text-gray-400 bg-gray-100 rounded-full dark:bg-gray-900">{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-3">
                        {{ $this->confirmAction }}
                        {{ $this->disableAction }}
                    </div>
                @endif
            @endunless
        </x-filament::card>
        <x-filament-actions::modals />
    </x-filament-breezy::grid-section>
</x-filament-panels::page>
