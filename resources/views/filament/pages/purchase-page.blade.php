<div class="flex items-center justify-center h-[90vh]">
    <div class="container mx-auto max-w-5xl px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 flex items-center justify-center">
                <img src="{{ asset('other/duffy-duck.jpeg') }}" alt="" class="max-w-full rounded-lg shadow">
            </div>

            <div class="col-span-1 flex items-center">
                <form id="payment-form" action="{{ route('complete.purchase', ['id' => $seller->id]) }}" method="POST" class="w-full">
                    @csrf
            
                    <h1 class="mb-4 items-center">{{ $price ? $price : 'Enroll Now For Free' }}</h1>
                    <div class="mb-4 p-3 border rounded bg-white shadow-sm">
                        <div id="card-element"></div>
                        <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                    </div>
            
                    <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 duration-300 text-white px-4 py-2 rounded-lg shadow-sm">
                        Pay Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe with your publishable key
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();

    // Create the card Element
    const cardElement = elements.create('card');

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
