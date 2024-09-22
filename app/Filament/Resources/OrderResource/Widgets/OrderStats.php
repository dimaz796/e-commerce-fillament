<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Faker\Core\Number;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status','new')->count()),
            Stat::make('Order Processing', Order::query()->where('status','processing')->count()),
            Stat::make('Order Shipped', Order::query()->where('status','shipped')->count()),
            Stat::make('Average Price', 'Rp ' . number_format(Order::query()->avg('grand_total'), 0, ',', '.'), 'IDR')
        ];
    }
}
