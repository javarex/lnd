<?php

use App\Filament\Widgets\calendarTrainingWidget;
use Guava\Calendar\Enums\Context;

it('fills create action dates from clicked calendar date arguments', function () {
    $widget = app(calendarTrainingWidget::class);
    $method = new ReflectionMethod($widget, 'getCalendarDateDefaults');

    $dates = $method->invoke($widget, [
        'context' => Context::DateClick,
        'data' => [
            'dateStr' => '2026-04-14',
        ],
    ]);

    expect($dates)->toBe([
        'start_date' => '2026-04-14',
        'end_date' => '2026-04-14',
    ]);
});

it('passes the clicked calendar date into create actions', function () {
    $widget = app(calendarTrainingWidget::class);
    $widget->bootedHasContextMenu();

    $actions = $widget->getContextMenuActionsUsing(Context::DateClick, [
        'date' => '2026-04-14T00:00:00.000Z',
        'dateStr' => '2026-04-14',
        'allDay' => true,
        'view' => [],
        'tzOffset' => 480,
    ]);

    expect($actions->implode(''))
        ->toContain('mountAction')
        ->toContain('dateClick')
        ->toContain('2026-04-14');
});
