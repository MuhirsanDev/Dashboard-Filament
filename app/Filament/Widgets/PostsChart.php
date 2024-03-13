<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class PostsChart extends LineChartWidget
{
    protected static ?string $heading = 'Posts Chart';

    protected function getData(): array
    {
        $data = Trend::model(Post::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) {
                $date = Carbon::createFromFormat('Y-m', $value->date);
                $formattedDate = $date->format('M');

                return $formattedDate;
            }),
        ];
    }
}
