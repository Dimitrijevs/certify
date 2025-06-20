<div class="flex items-center justify-center h-[90vh]">
    <div class="container mx-auto max-w-5xl px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 flex items-center justify-center">
                <img src="{{ asset('other/piggy-bank.jpg') }}" alt="" class="max-w-full rounded-lg shadow">
            </div>

            <div class="col-span-1 flex items-center">
                <form id="payment-form" action="{{ route('complete.purchase', ['id' => $seller->id]) }}" method="POST"
                    class="w-full">
                    @csrf

                    @if ($price > 0)
                        <h2 class="text-2xl font-bold mb-6 items-center text-center">{{ __('other.total_price') }}:
                            {{ number_format($price, 2) }} @if ($course)
                                {{ $course->currency->symbol }}
                            @else
                                {{ $test->currency->symbol }}
                            @endif
                        </h2>
                    @endif

                    <div class="mb-6 p-3 border rounded bg-white shadow-sm">
                        <div id="card-element"></div>
                        <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                    </div>

                    @if ($course)
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                    @else
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                    @endif

                    <x-cyan-button>
                        {{ $price ? __('other.pay_now') : __('other.enroll_now_for_free') }}
                    </x-cyan-button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe with your publishable key
    const stripe = Stripe('{{ config('stripe.publishable') }}');

    const elements = stripe.elements({
        locale: '{{ app()->getLocale() }}',
    });

    // Create the card Element
    const cardElement = elements.create('card', {
        hidePostalCode: true,
    });

    // Add it to the DOM
    cardElement.mount('#card-element');

    // Handle form submission
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Create the token
        const {
            token,
            error
        } = await stripe.createToken(cardElement);

        if (error) {
            // Show error in the form
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
        } else {
            // Add token to form and submit
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    });
</script>
