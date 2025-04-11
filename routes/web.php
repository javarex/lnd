<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('report', function () {
    $data = ['items' => \App\Models\CalendarOfTraining::all()];
    $pdf = Pdf::loadView('report', $data);
    return $pdf->stream();
//    return $pdf->download('multi-page-report.pdf');
});
