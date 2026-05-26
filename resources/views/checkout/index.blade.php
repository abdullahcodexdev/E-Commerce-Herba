@extends('layouts.store')
@section('title', 'Checkout — Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>Checkout</h1>
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('cart.index') }}">Cart</a> / Checkout</div></div>
</div>

<section class="section">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-error"><ul style="margin:0;padding-left:1.1rem">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-grid">
                <div class="reveal">
                    <div class="form-card" style="margin-bottom:1.4rem">
                        <h3>Shipping Details</h3>
                        <div class="grid-2">
                            <div class="field"><label>Full Name *</label><input type="text" name="name" maxlength="60" value="{{ old('name', auth()->user()->name ?? '') }}" required></div>
                            <div class="field"><label>Email *</label><input type="email" name="email" maxlength="255" value="{{ old('email', auth()->user()->email ?? '') }}" required></div>
                        </div>
                        <div class="grid-2">
                            <div class="field"><label>Phone *</label><input type="text" name="phone" maxlength="20" value="{{ old('phone', auth()->user()->phone ?? '') }}" required></div>
                            <div class="field"><label>City *</label><input type="text" name="city" maxlength="60" value="{{ old('city', auth()->user()->city ?? '') }}" required></div>
                        </div>
                        <div class="field"><label>Address *</label><textarea name="address" rows="3" maxlength="255" required>{{ old('address', auth()->user()->address ?? '') }}</textarea></div>
                        <div class="field"><label>Order Notes (optional)</label><textarea name="notes" rows="2" maxlength="500" placeholder="Any special instructions…">{{ old('notes') }}</textarea></div>
                    </div>

                    <div class="form-card">
                        <h3>Payment Method</h3>
                        <label class="pay-opt selected" data-pay="cod">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div><b style="color:var(--green-800)">💵 Cash on Delivery</b><div class="muted" style="font-size:.85rem">Pay with cash when your order arrives.</div></div>
                        </label>
                        <label class="pay-opt" data-pay="card">
                            <input type="radio" name="payment_method" value="card">
                            <div><b style="color:var(--green-800)">💳 Credit / Debit Card</b><div class="muted" style="font-size:.85rem">Pay securely with Visa, Mastercard or any major card.</div></div>
                        </label>

                        <!-- Card details (revealed when Card is selected) -->
                        <div class="cc-panel" id="ccPanel">
                            <div class="cc-inner">
                                <div class="credit-card" id="creditCard">
                                    <div class="cc-flip">
                                        <div class="cc-face cc-front">
                                            <div class="cc-top">
                                                <div class="cc-chip"></div>
                                                <div class="cc-brand" id="ccBrand">CARD</div>
                                            </div>
                                            <div class="cc-number" id="ccNumber">•••• •••• •••• ••••</div>
                                            <div class="cc-bottom">
                                                <div><span class="lbl">Card Holder</span><span class="val" id="ccName">FULL NAME</span></div>
                                                <div><span class="lbl">Expires</span><span class="val" id="ccExp">MM/YY</span></div>
                                            </div>
                                        </div>
                                        <div class="cc-face cc-back">
                                            <div class="cc-magstripe"></div>
                                            <div class="cc-cvv-row">
                                                <span class="lbl">CVV</span>
                                                <div class="cc-cvv-band" id="ccCvv">•••</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label>Name on Card <span class="cc-count" id="ccNameCount">0/26</span></label>
                                    <input type="text" id="ccNameInput" maxlength="26" placeholder="e.g. AHMED ALI" autocomplete="cc-name">
                                </div>
                                {{-- Card fields below are Stripe Elements (secure iframes). The raw card
                                     number/expiry/CVC are entered inside Stripe and never reach our server. --}}
                                <div class="field">
                                    <label>Card Number <span class="cc-brand-tag" id="ccBrandTag"></span></label>
                                    <div id="cardNumber-el" class="stripe-el"></div>
                                </div>
                                <div class="grid-2">
                                    <div class="field">
                                        <label>Expiry (MM/YY)</label>
                                        <div id="cardExpiry-el" class="stripe-el"></div>
                                    </div>
                                    <div class="field">
                                        <label>CVV</label>
                                        <div id="cardCvc-el" class="stripe-el"></div>
                                    </div>
                                </div>
                                <div class="cc-msg err" id="card-errors" role="alert"></div>
                                <p class="cc-secure">🔒 <b>Secured by Stripe</b> — card details go directly to Stripe (PCI compliant) and are never stored on our server.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reveal d2">
                    <div class="summary">
                        <h3>Your Order</h3>
                        @foreach($lines as $line)
                            <div class="sum-row"><span>{{ $line->product->name }} <span class="muted">× {{ $line->quantity }}</span></span><b>Rs. {{ number_format($line->line_total) }}</b></div>
                        @endforeach
                        <div class="sum-row" style="border-top:1px solid var(--beige);margin-top:.4rem;padding-top:.8rem"><span>Subtotal</span><b>Rs. {{ number_format($subtotal) }}</b></div>
                        <div class="sum-row"><span>Shipping</span><b>{{ $shipping == 0 ? 'Free' : 'Rs. '.number_format($shipping) }}</b></div>
                        <div class="sum-row total"><span>Total</span><span>Rs. {{ number_format($total) }}</span></div>
                        <button type="submit" id="placeOrderBtn" class="btn btn-primary btn-block" style="margin-top:1rem">Place Order →</button>
                        <p class="muted" style="font-size:.8rem;text-align:center;margin-top:.6rem">🔒 Your information is safe with us.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    const form      = document.getElementById('checkoutForm');
    const panel     = document.getElementById('ccPanel');
    const card      = document.getElementById('creditCard');
    const opts       = document.querySelectorAll('.pay-opt');
    const radios     = document.querySelectorAll('input[name="payment_method"]');
    const submitBtn  = document.getElementById('placeOrderBtn');
    const errorBox   = document.getElementById('card-errors');
    const brandTag   = document.getElementById('ccBrandTag');
    const nameInput  = document.getElementById('ccNameInput');
    const elName     = document.getElementById('ccName');   // cardholder on the visual
    const elBrand    = document.getElementById('ccBrand');  // brand on the visual

    const STRIPE_KEY = @json($stripeKey);
    const URL_INTENT  = @json(route('checkout.intent'));
    const URL_CONFIRM = @json(route('checkout.confirm'));

    let stripe = null, cardNumber = null, cardExpiry = null, cardCvc = null, mounted = false;

    const currentMethod = () => document.querySelector('input[name="payment_method"]:checked').value;

    // Map Stripe brand id → label + badge color class
    const BRANDS = {
        visa:       { l: 'Visa',        c: 'b-visa' },
        mastercard: { l: 'Mastercard',  c: 'b-mastercard' },
        amex:       { l: 'Amex',        c: 'b-amex' },
        discover:   { l: 'Discover',    c: 'b-discover' },
        diners:     { l: 'Diners Club', c: 'b-diners' },
    };
    function showBrand(brand) {
        brandTag.className = 'cc-brand-tag';
        const b = BRANDS[brand];
        if (b) { brandTag.textContent = b.l; brandTag.classList.add('show', b.c); elBrand.textContent = b.l.toUpperCase(); }
        else   { brandTag.textContent = ''; elBrand.textContent = 'CARD'; }
    }

    // Build & mount the Stripe Elements (only once, only when Card is chosen)
    function setupStripe() {
        if (mounted) return;
        if (!STRIPE_KEY) { errorBox.textContent = 'Stripe publishable key is missing. Add STRIPE_KEY to .env.'; return; }

        stripe = Stripe(STRIPE_KEY);
        const elements = stripe.elements();
        const style = {
            base:    { fontSize: '16px', color: '#14201e', fontFamily: 'Inter, sans-serif', '::placeholder': { color: '#9aa5a1' } },
            invalid: { color: '#d4452e' },
        };
        cardNumber = elements.create('cardNumber', { style, showIcon: true });
        cardExpiry = elements.create('cardExpiry', { style });
        cardCvc    = elements.create('cardCvc',    { style });
        cardNumber.mount('#cardNumber-el');
        cardExpiry.mount('#cardExpiry-el');
        cardCvc.mount('#cardCvc-el');

        // Live brand + Stripe's own field-level validation messages
        cardNumber.on('change', e => { showBrand(e.brand); errorBox.textContent = e.error ? e.error.message : ''; });
        cardExpiry.on('change', e => { errorBox.textContent = e.error ? e.error.message : ''; });
        cardCvc.on('change',    e => { errorBox.textContent = e.error ? e.error.message : ''; });
        cardCvc.on('focus', () => card.classList.add('flipped'));
        cardCvc.on('blur',  () => card.classList.remove('flipped'));
        mounted = true;
    }

    function syncMethod() {
        const isCard = currentMethod() === 'card';
        panel.classList.toggle('show', isCard);
        opts.forEach(o => o.classList.toggle('selected', o.dataset.pay === currentMethod()));
        if (isCard) setupStripe();
    }
    radios.forEach(r => r.addEventListener('change', syncMethod));

    const nameCount = document.getElementById('ccNameCount');
    if (nameInput) nameInput.addEventListener('input', () => {
        // Allow only letters, spaces, hyphens and apostrophes; cap at 26 (card standard)
        nameInput.value = nameInput.value.replace(/[^a-zA-Z .'-]/g, '').slice(0, 26);
        if (nameCount) nameCount.textContent = nameInput.value.length + '/26';
        elName.textContent = nameInput.value.trim() ? nameInput.value.toUpperCase() : 'FULL NAME';
    });

    function setLoading(on) {
        submitBtn.disabled = on;
        submitBtn.textContent = on ? 'Processing…' : 'Place Order →';
    }
    const field = n => form.querySelector(`[name="${n}"]`).value;

    form.addEventListener('submit', async (e) => {
        if (currentMethod() !== 'card') return;   // Cash on Delivery → normal form submit
        e.preventDefault();
        errorBox.textContent = '';
        setLoading(true);

        try {
            // 1) Ask the server to create a PaymentIntent (also validates shipping fields)
            const res = await fetch(URL_INTENT, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF, 'Accept': 'application/json' },
                body: new FormData(form),
            });
            const data = await res.json();
            if (!res.ok) {
                errorBox.textContent = data.error
                    || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Please check your details and try again.');
                setLoading(false);
                return;
            }

            // 2) Confirm the card with Stripe (raw card data goes straight to Stripe, not to us)
            const result = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardNumber,
                    billing_details: {
                        name:  nameInput.value || field('name'),
                        email: field('email'),
                        phone: field('phone'),
                    },
                },
            });

            // Gateway-returned errors: invalid card, expired, incorrect CVC, insufficient funds, declined…
            if (result.error) {
                errorBox.textContent = result.error.message;
                setLoading(false);
                return;
            }

            if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
                // 3) Finalize the order on the server (re-verifies the charge with Stripe)
                const fin = await fetch(URL_CONFIRM, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ payment_intent_id: result.paymentIntent.id }),
                });
                const fd = await fin.json();
                if (fin.ok && fd.redirect) { window.location = fd.redirect; return; }
                errorBox.textContent = fd.error || 'Payment captured but order could not be saved. Please contact support.';
                setLoading(false);
            } else {
                errorBox.textContent = 'Payment could not be completed. Please try another card.';
                setLoading(false);
            }
        } catch (err) {
            errorBox.textContent = 'Network/payment error — please check your connection and try again.';
            setLoading(false);
        }
    });

    syncMethod(); // set initial state
})();
</script>
@endpush
@endsection
