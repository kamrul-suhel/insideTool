<?php

namespace App\Http\Controllers;

use App\Classes\Export;
use App\Post;
use Carbon\Carbon;
use Illuminate\View\View;

class ExportController extends Controller
{
    protected $export, $post;

    public function __construct(Export $export, Post $post)
    {
        $this->export = $export;

        $this->post = $post;
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
     */
    public function export()
    {
		$from = request()->get('from') ? Carbon::parse(request()->get('from')) : Carbon::now()->startOfDay();
		$to = request()->get('to') ?     Carbon::parse(request()->get('to'))  :  Carbon::now()->endOfDay();

        if($this->post->where('posted', '<',  Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT'))->endOfDay())->count() < 1)
        {
            return redirect()->to('posts');
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Insights_Unilad_" . date('d-m-Y_h:m:s') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        //run export class and get filename
        $filename = $this->export->export($from, $to);

        //download csv
        return response()->download(storage_path() . "/app/exports/" . $filename, $filename, $headers);
    }
}
