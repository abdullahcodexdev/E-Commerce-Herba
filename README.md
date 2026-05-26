# 🌿 Herbal Roots — Herbal Products E-Commerce

A professional, fully-functional e-commerce website for herbal products, built with **Laravel 12 + MySQL**.
Earthy green design, smooth animations, hover effects, a slide-out cart, login system and complete checkout.

## 🧰 Tech Stack

| Layer | Technologies |
|-------|--------------|
| **Backend** | Laravel 12 (PHP 8.2), MySQL |
| **Frontend** | Blade templates, Tailwind CSS, Vanilla JavaScript |
| **Build tools** | Vite, PostCSS, npm |
| **Auth** | Laravel Breeze |
| **Payments** | Stripe (Elements + PaymentIntents, test/sandbox mode) |

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-Frontend-38B2AC?logo=tailwindcss&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?logo=javascript&logoColor=black)
![Stripe](https://img.shields.io/badge/Stripe-Payments-635BFF?logo=stripe&logoColor=white)

> 🔒 **Security & privacy:** No real secrets live in this repository. API keys, database
> credentials and Stripe keys belong **only** in your local `.env` file (git-ignored). Copy
> `.env.example` → `.env` and fill in your own values. Never commit `.env`, and use only
> Stripe **test** keys here — production keys must stay private.

## ✨ Features
- Attractive animated frontend (scroll reveal, hover effects, morphing hero, floating badges)
- Product catalog with categories, search, sorting & pagination
- Product detail pages with quantity stepper & benefits
- **Slide-out AJAX cart** + full cart page (add / update / remove)
- Checkout with Cash on Delivery / Bank Transfer → order confirmation
- **Login / Register** (Laravel Breeze), themed to match the brand
- "My Orders" account area
- Custom SVG logo, backgrounds & product/category placeholder images
  (replace product images later in `public/images/products/`)

## 🚀 How to Run

> Requirements: PHP 8.2 (XAMPP), MySQL running, Composer, Node.js.

```bash
cd herbsite

# (first time only) install + build assets
composer install
npm install && npm run build

# migrations + seed sample data
php artisan migrate:fresh --seed

# Start the server (port 8000 was busy on this machine → using 8090)
php artisan serve --port=8090
```

Then open: **http://127.0.0.1:8090**

## 🔑 Demo Logins
| Role     | Email                | Password |
|----------|----------------------|----------|
| Admin    | admin@herbalroots.pk | password |
| Customer | demo@herbalroots.pk  | password |

## 🖼️ Adding Your Real Product Images
Drop images into `public/images/products/` and set each product's `image`
field to `images/products/yourfile.jpg` (via the seeder or DB). If a file is
missing, the placeholder SVG is shown automatically.

## 💳 Stripe Payment Gateway (test/sandbox mode)

Card payments use **Stripe Elements + PaymentIntents** — PCI compliant: the raw card
number/expiry/CVC are entered inside Stripe's iframes and **never touch our server**.
We only store the **brand + last 4 digits** and the Stripe `payment_intent_id`.

### Setup
1. Create a free Stripe account → https://dashboard.stripe.com
2. Copy your **test** keys from https://dashboard.stripe.com/test/apikeys
3. Put them in `.env`:
   ```
   STRIPE_KEY=pk_test_xxxxxxxx        # publishable (frontend)
   STRIPE_SECRET=sk_test_xxxxxxxx     # secret (backend only)
   STRIPE_CURRENCY=usd
   ```
4. `php artisan config:clear` then reload checkout.

### Payment flow
1. `POST /checkout/payment-intent` → validates shipping, creates a Stripe PaymentIntent, returns `clientSecret`.
2. Browser runs `stripe.confirmCardPayment(clientSecret, …)` — card data → Stripe directly.
3. `POST /checkout/confirm` → server **re-verifies** the PaymentIntent status with Stripe, then creates the order (`is_paid=true`, status `processing`). Success page shown **only** when the gateway confirms.

### Test cards (Stripe sandbox)
| Scenario | Card number | Result |
|---|---|---|
| ✅ Success | `4242 4242 4242 4242` | Payment succeeds |
| ❌ Generic decline | `4000 0000 0000 0002` | "Your card was declined." |
| ❌ Insufficient funds | `4000 0000 0000 9995` | "insufficient funds" |
| ❌ Lost/blocked card | `4000 0000 0000 9987` | "card was declined" |
| ❌ Expired card | `4000 0000 0000 0069` | "card has expired" |
| ❌ Incorrect CVC | `4000 0000 0000 0127` | "security code is incorrect" |
| ❌ Processing error | `4000 0000 0000 0119` | "processing error" |

Use any future expiry (e.g. `12/34`), any 3-digit CVC, any postal code.
Invalid number / wrong length / bad CVC are caught **live** by Stripe Elements before submit.

## 🗂️ Key Files
- `routes/web.php` — all routes
- `app/Http/Controllers/` — Home, Shop, Cart, Checkout, Order controllers
- `app/Services/CartService.php` — session-based cart logic
- `resources/views/` — Blade views (`layouts/store.blade.php` is the main layout)
- `public/css/site.css` — full custom storefront styling
- `public/js/site.js` — cart drawer, scroll reveal, AJAX add-to-cart, toast
- `database/seeders/DatabaseSeeder.php` — categories, products, users
