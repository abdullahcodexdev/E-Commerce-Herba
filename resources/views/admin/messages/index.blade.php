@extends('layouts.store')
@section('title', 'Admin — Messages | Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>✉️ Contact Messages</h1>
        <div class="crumbs">Messages submitted through the contact form</div></div>
</div>

<section class="section">
    <div class="container">
        @include('admin.partials.nav')

        @if($messages->count())
            <div class="reveal" style="display:flex;flex-direction:column;gap:1rem">
                @foreach($messages as $msg)
                    <div class="form-card" style="padding:1.25rem">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap">
                            <div>
                                <b style="color:var(--green-800)">{{ $msg->name }}</b>
                                <a href="mailto:{{ $msg->email }}" class="muted" style="font-size:.85rem">&lt;{{ $msg->email }}&gt;</a>
                                @if($msg->subject)<div style="color:var(--green-700);font-weight:600;margin-top:.25rem">{{ $msg->subject }}</div>@endif
                            </div>
                            <div class="muted" style="font-size:.8rem;text-align:right">
                                {{ $msg->created_at->format('d M Y, g:i A') }}
                                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" style="margin-top:.5rem" onsubmit="return confirm('Delete this message?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#c0392b;color:#fff">Delete</button>
                                </form>
                            </div>
                        </div>
                        <p style="margin-top:.75rem;white-space:pre-line">{{ $msg->message }}</p>
                    </div>
                @endforeach
            </div>
            <div style="margin-top:1rem">{{ $messages->links() }}</div>
        @else
            <div class="empty-cart" style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:3rem">
                <div class="e">📭</div><h3 style="color:var(--green-800)">No messages yet</h3>
                <p>Messages from the contact form will appear here.</p>
            </div>
        @endif
    </div>
</section>
@endsection
