<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Number;

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
                                year(ct.end_date) as training_year,
                                day(ct.end_date) as end_day
                            ")
                            ->join('employees', 'employees.id', 'participants.employee_id')
                            ->join('calendar_of_trainings as ct', 'ct.id', 'participants.calendar_of_training_id')
                            ->join('trainings as t', 't.id', 'ct.training_id')
                            ->join('venues as v', 'v.id', 'ct.venue_id')
                            ->where('calendar_of_training_id', $id)
                            ->get()
                            ->each(function($item) {
                                $item->end_day = Number::ordinal($item->end_day);
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
}
