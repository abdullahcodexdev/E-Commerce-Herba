<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;background:#f4f7f2;font-family:Arial,Helvetica,sans-serif;color:#2c3a2c">
    <div style="max-width:600px;margin:0 auto;padding:24px">
        <div style="background:#2e7d32;color:#fff;padding:24px;border-radius:12px 12px 0 0;text-align:center">
            <h1 style="margin:0;font-size:22px">🌿 Herbal Roots</h1>
            <p style="margin:6px 0 0;opacity:.9">Thank you for your order!</p>
        </div>

        <div style="background:#fff;padding:24px;border-radius:0 0 12px 12px">
            <p>Hi {{ $order->name }},</p>
            <p>We've received your order. Here are the details:</p>

            <table style="width:100%;border-collapse:collapse;margin:16px 0">
                <tr>
                    <td style="padding:6px 0;color:#6b7d6b">Order Number</td>
                    <td style="padding:6px 0;text-align:right;font-weight:bold;color:#2e7d32">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7d6b">Payment</td>
                    <td style="padding:6px 0;text-align:right">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Card' }} ({{ $order->is_paid ? 'Paid' : 'Pending' }})</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7d6b">Status</td>
                    <td style="padding:6px 0;text-align:right;text-transform:capitalize">{{ $order->status }}</td>
                </tr>
            </table>

            <table style="width:100%;border-collapse:collapse;border-top:1px solid #e3e9e0;margin-top:8px">
                <thead>
                    <tr style="color:#6b7d6b;font-size:13px">
                        <th align="left" style="padding:8px 0">Item</th>
                        <th align="center" style="padding:8px 0">Qty</th>
                        <th align="right" style="padding:8px 0">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr style="border-top:1px solid #f0f3ee">
                            <td style="padding:8px 0">{{ $item->product_name }}</td>
                            <td align="center" style="padding:8px 0">{{ $item->quantity }}</td>
                            <td align="right" style="padding:8px 0">Rs. {{ number_format($item->price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table style="width:100%;border-collapse:collapse;border-top:1px solid #e3e9e0;margin-top:8px">
                <tr><td style="padding:4px 0;color:#6b7d6b">Subtotal</td><td align="right" style="padding:4px 0">Rs. {{ number_format($order->subtotal) }}</td></tr>
                <tr><td style="padding:4px 0;color:#6b7d6b">Shipping</td><td align="right" style="padding:4px 0">Rs. {{ number_format($order->shipping) }}</td></tr>
                <tr><td style="padding:8px 0;font-weight:bold;font-size:17px;color:#2e7d32">Total</td><td align="right" style="padding:8px 0;font-weight:bold;font-size:17px;color:#2e7d32">Rs. {{ number_format($order->total) }}</td></tr>
            </table>

            <div style="margin-top:16px;padding:14px;background:#f4f7f2;border-radius:8px">
                <strong>Shipping to:</strong><br>
                {{ $order->name }}<br>
                {{ $order->address }}, {{ $order->city }}<br>
                {{ $order->phone }}
            </div>

            <p style="margin-top:20px;color:#6b7d6b;font-size:13px">If you have any questions, just reply to this email. Thank you for choosing Herbal Roots! 🌿</p>
        </div>
    </div>
</body>
</html>
