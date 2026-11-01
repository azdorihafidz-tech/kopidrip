<?php

namespace App\Enums;

enum ShippingStatus: string
{
    case QuotePending = 'quote_pending';
    case Quoted = 'quoted';
    case Preparing = 'preparing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
