<?php

namespace App\Http\Controllers;

use App\Models\Training;
use ZipArchive;
use Carbon\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function participantCerts($id)
    {
        $participants = DB::table('participants')
                            ->selectRaw("
                                employees.full_name,
                                t.training_name,
                                v.venue,
                                ct.end_date,
                                ct.start_date,
                                year(ct.end_date) as training_year,
                                month(ct.end_date) as training_month,
                                day(ct.end_date) as end_day,
                                day(ct.start_date) as start_day,
                                schools.school
                            ")
                            ->join('employees', 'employees.id', 'participants.employee_id')
                            ->join('calendar_of_trainings as ct', 'ct.id', 'participants.calendar_of_training_id')
                            ->join('schools', 'employees.school_id', 'schools.id')
                            ->join('trainings as t', 't.id', 'ct.training_id')
                            ->join('venues as v', 'v.id', 'ct.venue_id')
                            ->where('calendar_of_training_id', $id)
                            ->get()
                            ->each(function($item) {
                                $end_date_parsed = Carbon::parse($item->end_date);
                                $start_date = Carbon::parse($item->start_date)->format('F j, Y');
                                $end_date = $end_date_parsed->format('F j, Y');
                                $item->end_day = Number::ordinal($item->end_day);
                                $item->training_month = Month::fromNumber($end_date_parsed->month)->name;
                                $item->date = $start_date == $end_date ? $start_date : $start_date  .' - '. $end_date;
                                return $item;
                            });

                            // dd($participants);

        $pdf = SnappyPdf::loadView('report', ['participants' => $participants])
                        ->setOption('enable-local-file-access', true)
                        ->setOption('margin-top', 0)
                        ->setOption('margin-right', 0)
                        ->setOption('margin-bottom', 0)
                        ->setOption('margin-left', 0)
                        ->setPaper('a4', 'landscape');

        return $pdf->download('report.pdf');
    }

    public function downloadSchoolCerts(Request $request)
    {
        $training = Training::whereHas('calendarOfTraining', fn($query) => $query->where('id', $request->training))->first();
        // dd($request->all());
        $schools = DB::table('participants')
                            ->selectRaw("
                                employees.full_name,
                                t.training_name,
                                v.venue,
                                ct.end_date,
                                ct.start_date,
                                year(ct.end_date) as training_year,
                                month(ct.end_date) as training_month,
                                day(ct.end_date) as end_day,
                                day(ct.start_date) as start_day,
                                schools.school
                            ")
                            ->join('employees', 'employees.id', 'participants.employee_id')
                            ->join('schools', 'employees.school_id', 'schools.id')
                            ->join('calendar_of_trainings as ct', 'ct.id', 'participants.calendar_of_training_id')
                            ->join('trainings as t', 't.id', 'ct.training_id')
                            ->join('venues as v', 'v.id', 'ct.venue_id')
                            ->where('calendar_of_training_id', $request->training)
                            ->whereIn('employees.school_id', $request->ids)
                            ->get()
                            ->each(function($item) {
                                $start_date = Carbon::parse($item->start_date)->format('F j, Y');
                                $end_date = Carbon::parse($item->end_date)->format('F j, Y');
                                $item->end_day = Number::ordinal($item->end_day);
                                $item->training_month = Month::fromNumber(5)->name;
                                $item->date = $start_date == $end_date ? $start_date : $start_date  .' - '. $end_date;
                                return $item;
                            })
                            ->groupBy('school');
        $tempPath = storage_path('app/temp-pdfs');
                        Storage::makeDirectory('temp-pdfs'); // ensure the dir exists

        $pdfPaths = [];


        foreach ($schools as $trainingId => $records) {
            $fileName = "$training->training_name - {$trainingId}.pdf";

            // Render your view with grouped records
            $pdf = SnappyPdf::loadView('report', ['participants' => $records])
                        ->setOption('enable-local-file-access', true)
                        ->setOption('margin-top', 0)
                        ->setOption('margin-right', 0)
                        ->setOption('margin-bottom', 0)
                        ->setOption('margin-left', 0)
                        ->setPaper('a4', 'landscape');

            $pdfPath = "$tempPath/{$fileName}";
            $pdf->save($pdfPath);
            $pdfPaths[] = $pdfPath;
        }

        $zipFileName = "certificates-$training->training_name.zip";
        $zipPath = storage_path("app/{$zipFileName}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($pdfPaths as $pdfPath) {
                $zip->addFile($pdfPath, basename($pdfPath));
            }
            $zip->close();
        }
        foreach ($pdfPaths as $pdfPath) {
            unlink($pdfPath);
        }

        return response()->download($zipPath)->deleteFileAfterSend();
                            dd($participants);

        $pdf = SnappyPdf::loadView('report', ['participants' => $participants])
                        ->setOption('enable-local-file-access', true)
                        ->setOption('margin-top', 0)
                        ->setOption('margin-right', 0)
                        ->setOption('margin-bottom', 0)
                        ->setOption('margin-left', 0)
                        ->setPaper('a4', 'landscape');

        return $pdf->download('report.pdf');
    }

    public function recognitionCerts($id)
    {

        $participants = DB::table('training_trainer')
                            ->selectRaw("
                                trainers.trainers_name,
                                trainers.affiliation,
                                t.training_name,
                                v.venue,
                                ct.end_date,
                                ct.start_date,
                                year(ct.end_date) as training_year,
                                month(ct.end_date) as training_month,
                                day(ct.end_date) as end_day,
                                day(ct.start_date) as start_day,
                                ct.topic_discuss

                            ")
                            ->join('trainers', 'trainers.id', 'training_trainer.trainer_id')
                            ->join('calendar_of_trainings as ct', 'ct.id', 'training_trainer.calendar_of_training_id')
                            // ->join('schools', 'employees.school_id', 'schools.id')
                            ->join('trainings as t', 't.id', 'ct.training_id')
                            ->join('venues as v', 'v.id', 'ct.venue_id')
                            ->where('calendar_of_training_id', $id)
                            ->get()
                            ->each(function($item) {
                                $end_date_parsed = Carbon::parse($item->end_date);
                                $start_date = Carbon::parse($item->start_date)->format('F j, Y');
                                $end_date = $end_date_parsed->format('F j, Y');
                                $item->end_day = Number::ordinal($item->end_day);
                                $item->training_month = Month::fromNumber($end_date_parsed->month)->name;
                                $item->date = $start_date == $end_date ? $start_date : $start_date  .' - '. $end_date;
                                return $item;
                            });

                            // dd($participants);

        $pdf = SnappyPdf::loadView('reportSpeaker', ['participants' => $participants])
                        ->setOption('enable-local-file-access', true)
                        ->setOption('margin-top', 0)
                        ->setOption('margin-right', 0)
                        ->setOption('margin-bottom', 0)
                        ->setOption('margin-left', 0)
                        ->setPaper('a4', 'landscape');

        return $pdf->download('reportRecognition.pdf');
    }
}
