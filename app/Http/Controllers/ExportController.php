<?php

namespace App\Http\Controllers;

use App\Classes\Export;
use App\Post;
use Carbon\Carbon;
use Illuminate\View\View;

class ExportController extends Controller
{
    protected $export;

    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('exports.index');
    }

    /**
     * Export Data that is over 2 days old to CSV
     * @return mixed
     */
    public function export()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Insights_Unilad_" . date('d-m-Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $email = false; //todo add mailer notification, attached csv to email and send
        

        $filename = $this->export->export($email);

        //download csv
        return response()->download(storage_path() . "/exports/" . $filename, $filename, $headers);
    }
}
