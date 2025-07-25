<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Offer Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .offer-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .details {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            margin-right: 10px;
        }

        .products-list {
            list-style: none;
            padding: 0;
        }

        .products-list li {
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="offer-title">{{ $offer->name }}</div>
        <div>Host: {{ $host->name }}</div>
    </div>

    <div class="section">
        <div class="section-title">Offer Details</div>
        <div class="details">
            <span class="label">Type:</span> {{ $offer->offerType->name }}
        </div>
        <div class="details">
            <span class="label">Value:</span>
            @if($offer->is_discount)
            {{ $offer->value }}% discount
            @else
            ${{ number_format($offer->value, 2) }}
            @endif
        </div>
        <div class="details">
            <span class="label">Target Audience:</span>
            @if($offer->target_audience == 1)
            All Attendees
            @elseif($offer->target_audience == 2)
            Lead Broker
            @elseif($offer->target_audience == 3)
            New Clients
            @endif
        </div>
        <div class="details">
            <span class="label">Products:</span>
            @if($offer->apply_to_all_products)
            All Products
            @else
            <ul class="products-list">
                @foreach($offer->products as $product)
                <li>{{ $product->name }}</li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

    @if($offer->description)
    <div class="section">
        <div class="section-title">Description</div>
        <div class="details">{{ $offer->description }}</div>
    </div>
    @endif

    @if($offer->terms_conditions)
    <div class="section">
        <div class="section-title">Terms & Conditions</div>
        <div class="details">{{ $offer->terms_conditions }}</div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Validity</div>
        <div class="details">
            <span class="label">Start Date:</span> {{ $offer->starts_at ? $offer->starts_at->format('Y-m-d') : 'Immediate' }}
        </div>
        <div class="details">
            <span class="label">Expiry Date:</span> {{ $offer->expires_at ? $offer->expires_at->format('Y-m-d') : 'No expiry' }}
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>This is a computer-generated document and does not require a signature.</p>
    </div>
</body>

</html>