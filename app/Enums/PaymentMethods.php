<?php

namespace App\Enums;

enum PaymentMethods: string
{
    case Online = 'online';
    case CashOnDelivery = 'cash_on_delivery';
}