<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
// use ZipArchive;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('report/{id}', function ($id) {

    // 1. Generate fake data
    $faker = \Faker\Factory::create();

    $fakeUsers = collect(range(1, 1000))->map(function () use ($faker) {
        return fluent([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'address' => $faker->address,
        ]);
    });

// 2. Chunk the collection (e.g., 50 per PDF)
    $chunks = $fakeUsers->chunk(100);

// 3. Folder to store temporary PDFs
    $directory = storage_path('app/temp_pdfs_' . Str::random(6));
    File::makeDirectory($directory);

// 4. Generate each PDF and store
    foreach ($chunks as $index => $chunk) {
        $data = [
            'items' => \App\Models\CalendarOfTraining::with('participants.employee', 'training')->findOrFail($id),
            'users' => $chunk,
        ];

        $pdf = Pdf::loadView('report', $data)->setPaper('A4', 'landscape');
        $pdf->save("$directory/report_page_" . ($index + 1) . ".pdf");
    }


// 5. Zip the files
    $zipFile = storage_path("app/reports_" . now()->timestamp . ".zip");
    $zip = new ZipArchive;

    if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
        foreach (File::files($directory) as $file) {
            $zip->addFile($file->getRealPath(), $file->getFilename());
        }
        $zip->close();
    }


// 6. Clean up temp folder
    File::deleteDirectory($directory);

// 7. Return the zipped file for download
    return response()->download($zipFile)->deleteFileAfterSend(true);

//
//    $faker = \Faker\Factory::create();
//
//    $fakeUsers = collect(range(1, 400))->map(function () use ($faker) {
//        return fluent([
//            'name' => $faker->name,
//            'email' => $faker->unique()->safeEmail,
//            'address' => $faker->address,
//        ]);
//    });
//
//    $data = ['items' => \App\Models\CalendarOfTraining::with('participants.employee', 'training')->findOrFail($id), 'users' => $fakeUsers];
//
//    $pdf = Pdf::loadView('report', $data)->setPaper('A4', 'landscape');
//
//
////    dd($pdf);
//    return $pdf->download('certs.pdf');
})->name('report');


Route::get('test', function (){
    return view('test');
});

