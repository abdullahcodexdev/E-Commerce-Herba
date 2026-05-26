@extends('layouts.store')
@section('title', 'Contact Us — Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>Get in Touch</h1>
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Contact</div></div>
</div>

<section class="section">
    <div class="container checkout-grid">
        <div class="reveal">
            <div class="form-card">
                <h3>Send us a Message</h3>
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="grid-2">
                        <div class="field"><label>Name</label><input type="text" name="name" required></div>
                        <div class="field"><label>Email</label><input type="email" name="email" required></div>
                    </div>
                    <div class="field"><label>Subject</label><input type="text" name="subject"></div>
                    <div class="field"><label>Message</label><textarea name="message" rows="5" required></textarea></div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
        <div class="reveal d2">
            <div class="form-card">
                <h3>Contact Information</h3>
                <ul class="feature-list">
                    <li><span class="fi">📍</span><div><h4>Address</h4><span class="muted">123 Herbal Street, Lahore, Pakistan</span></div></li>
                    <li><span class="fi">📞</span><div><h4>Phone</h4><span class="muted">+92 300 1234567</span></div></li>
                    <li><span class="fi">✉️</span><div><h4>Email</h4><span class="muted">care@herbalroots.pk</span></div></li>
                    <li><span class="fi">🕑</span><div><h4>Hours</h4><span class="muted">Mon–Sat, 9:00am – 7:00pm</span></div></li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
