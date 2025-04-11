<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('report/{id}', function ($id) {
    $data = ['items' => \App\Models\CalendarOfTraining::with('participants.employee', 'training')->findOrFail($id)];
//    return view('report', $data);
    $pdf = Pdf::loadView('report', $data)->setPaper('A4', 'landscape');

//    dd($pdf);
    return $pdf->stream();
})->name('report');


Route::get('test', function (){
    return view('test');
});

