<?php

namespace App\Http\Controllers;

use App\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    protected
        $pages,
        $page,
        $videoStats,
        $linkStats,
        $iAStats,
        $timeline,
        $created_at,
        $timeUnit,
        $metric,
        $metrics = ['reach', 'reactions', 'comments', 'shares'];

    /**
     * OverviewController constructor.
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->pages = $this->page->all();
        return view('overview.index', ['pages' => $this->pages]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->timeline = 'month';
        $this->timeUnit = 'hour';
        $this->metric = 'reach';
        $this->created_at = Carbon::today()->startOfMonth();

        if(request()->has('timeline')) {
            $this->timeline = request()->get('timeline');
            $this->getTimeLineDate();
        }

        if(request()->has('unit')) {
            $this->timeUnit = request()->get('unit');
        }

        if(request()->has('metric')) {
            $this->metric = request()->get('metric');
        }

        $this->pages = $this->page->find($id);

        $this->getVideoStats($id);
        $this->getLinkStats($id);
        $this->getIAStats($id);
        $this->getGraphStats($id);

        return view('overview.show')
            ->with('page', $this->pages)
            ->with('videoStats', $this->videoStats)
            ->with('linkStats', $this->linkStats)
            ->with('timeline', $this->timeline)
            ->with('iAStats', $this->iAStats)
            ->with('metric', $this->metric)
            ->with('unit', $this->timeUnit);
    }

    /**
     *  Set created_at and unit of time for sql calls
     */
    public function getTimeLineDate()
    {
        if($this->timeline == 'today') {
            $this->created_at = Carbon::today()->startOfDay();
        }
        if($this->timeline == 'week') {
            $this->created_at = Carbon::today()->startOfWeek();
        }
        if($this->timeline == 'month') {
            $this->created_at = Carbon::today()->startOfMonth();
        }
    }

    /**
     * Get video stats for each metric
     * @param $id
     */
    public function getVideoStats($id)
    {
        //Video
        $this->videoStats = [];
        $this->videoStats[$this->timeline] = $this->pages->posts()->where('type', 'video')->where('created_at', '>', $this->created_at)->count();
        foreach($this->metrics as $metric) {
            $this->videoStats[$metric][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum('.$metric.') as total'))->where('type', 'video')->where('created_at', '>', $this->created_at)->groupBy('page_id')->pluck('total');
        }
    }

    /**
     * Get link stats for each metric
     * @param $id
     */
    public function getLinkStats($id)
    {
        // LINKS
        $this->linkStats = [];
        $this->linkStats[$this->timeline] = $this->pages->posts()->where('type', 'link')->where('created_at', '>', $this->created_at)->count();
        foreach($this->metrics as $metric) {
            $this->linkStats[$metric][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum('.$metric.') as total'))->where('type', 'link')->where('created_at', '>', $this->created_at)->groupBy('page_id')->pluck('total');
        }
    }

    /**
     * Get IA stats for each metric
     * @param $id
     */
    public function getIAStats($id)
    {
        // INSTANT ARTICLE
        $this->iAStats = [];
        $this->iAStats[$this->timeline] = $this->pages->posts()->where('type', 'link')->where('instant_article', 1)->where('created_at', '>', $this->created_at)->count();
        foreach($this->metrics as $metric) {
            $this->iAStats[$metric][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum('.$metric.') as total'))->where('instant_article', 1)->where('type', 'link')->where('created_at', '>', $this->created_at)->groupBy('page_id')->pluck('total');
        }
    }

    /**
     * Get graph specific data for each metric
     * @param $id
     */
    public function getGraphStats($id)
    {

        $this->videoStats['graph']['reach'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(reach) as total'), 'posted')->where('type', 'video')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['reach'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(reach) as total'), 'posted')->where('type', 'link')->where('created_at', '>',$this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['reach'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(reach) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->pluck('total', 'posted');

        $this->videoStats['graph']['shares'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(shares) as total'), 'posted')->where('type', 'video')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['shares'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(shares) as total'), 'posted')->where('type', 'link')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['shares'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(shares) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');

        $this->videoStats['graph']['reactions'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(reactions) as total'), 'posted')->where('type', 'video')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['reactions'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(reactions) as total'), 'posted')->where('type', 'link')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['reactions'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(reactions) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');

        $this->videoStats['graph']['comments'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(comments) as total'), 'posted')->where('type', 'video')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['comments'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(comments) as total'), 'posted')->where('type', 'link')->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['comments'][$this->timeline] = DB::table('posts')->where('page_id', $id)->select(DB::raw('sum(comments) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->where('created_at', '>', $this->created_at)->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');


    }


}
