<?php

namespace App\Http\Controllers;

use App\Page;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    protected
        $pages,
        $page,
        $from,
        $to,
        $videoStats,
        $linkStats,
        $iAStats,
        $iaNonIa,
        $videoLabelTotals,
        $videoYesterdayLabelTotals,
        $conversion,
        $timeUnit,
        $videoMetric,
        $metric,
        $videoLabelCompareDateFrom,
        $videoLabelCompareDateTo,
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
        $this->conversion = 'sum';
        $this->timeUnit = 'hour';
        $this->metric = 'reach';
        $this->videoMetric = 'reach';

        $this->from = request()->get('from') ? Carbon::parse(request()->get('from'))->startOfDay() :    Carbon::now()->startOfDay();
        $this->to = request()->get('to') ?     Carbon::parse(request()->get('to'))->endOfDay() :        Carbon::now()->endOfDay();
        $this->pages = $this->page->find($id);

        $this->getSearchParameters();
        $this->getVideoStats($id);
        $this->getLinkStats($id);
        $this->getIAStats($id);
        $this->iaNonIaComparison($id);
        $this->getGraphStats($id);
        $this->getVideoLabels($id);
        $this->getYesterdayVideoLabels($id);

        return view('overview.show')
            ->with('id', $id)
            ->with('from', $this->from)
            ->with('to', $this->to)
            ->with('page', $this->pages)
            ->with('videoStats', $this->videoStats)
            ->with('linkStats', $this->linkStats)
            ->with('iAStats', $this->iAStats)
            ->with('iaNonIaComparison', $this->iaNonIa)
            ->with('videoLabelTotals', $this->videoLabelTotals)
            ->with('videoYesterdayLabelTotals', $this->videoYesterdayLabelTotals)
            ->with('metric', $this->metric)
            ->with('videoMetric', $this->videoMetric)
            ->with('videoLabelCompareDateFrom', $this->videoLabelCompareDateFrom )
            ->with('videoLabelCompareDateTo', $this->videoLabelCompareDateTo )
            ->with('conversion', $this->conversion)
            ->with('unit', $this->timeUnit);
    }

    /**
     * Set all get requests
     */
    public function getSearchParameters()
    {
        if(request()->has('unit')) {
            $this->timeUnit = request()->get('unit');
        }

        if(request()->has('metric')) {
            $this->metric = request()->get('metric');
        }

        if(request()->has('video_metric')) {
            $this->videoMetric = request()->get('video_metric');
        }

        if(request()->has('conversion')) {
            $this->conversion = request()->get('conversion');
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
        $this->videoStats[0] = $this->pages->posts()->where('type', 'video')->whereBetween('posted', [$this->from, $this->to])->count();
        foreach($this->metrics as $metric) {
            $this->videoStats[$metric][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'('.$metric.') as total'))->where('type', 'video')->whereBetween('posted', [$this->from, $this->to])->groupBy('page_id')->pluck('total');
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
        $this->linkStats[0] = $this->pages->posts()->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->count();
        foreach($this->metrics as $metric) {
            $this->linkStats[$metric][0] = DB::table('posts')->where('instant_article', 0)->where('page_id', $id)->select(DB::raw($this->conversion.'('.$metric.') as total'))->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy('page_id')->pluck('total');
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
        $this->iAStats[0] = $this->pages->posts()->where('type', 'link')->where('instant_article', 1)->whereBetween('posted', [$this->from, $this->to])->count();
        foreach($this->metrics as $metric) {
            $this->iAStats[$metric][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'('.$metric.') as total'))->where('instant_article', 1)->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy('page_id')->pluck('total');
        }
    }

    public function iaNonIaComparison($id)
    {
        $this->iaNonIa = [];
        $this->iaNonIa['ia'] = $this->pages->posts()->select(DB::raw('count(id) as total, posted'))->where('instant_article', 1)->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw('date(posted)'))->orderBy('posted')->get();
        $this->iaNonIa['non_ia'] = $this->pages->posts()->select(DB::raw('count(id) as total, posted'))->where('instant_article', 0)->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw('date(posted)'))->orderBy('posted')->get();
    }

    /**
     * Get graph specific data for each metric
     * @param $id
     */
    public function getGraphStats($id)
    {
        $this->videoStats['graph']['reach'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(reach) as total'), 'posted')->where('type', 'video')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['reach'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(reach) as total'), 'posted')->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['reach'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(reach) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->pluck('total', 'posted');

        $this->videoStats['graph']['shares'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(shares) as total'), 'posted')->where('type', 'video')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['shares'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(shares) as total'), 'posted')->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['shares'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(shares) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');

        $this->videoStats['graph']['reactions'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(reactions) as total'), 'posted')->where('type', 'video')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['reactions'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(reactions) as total'), 'posted')->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['reactions'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(reactions) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');

        $this->videoStats['graph']['comments'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(comments) as total'), 'posted')->where('type', 'video')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->linkStats['graph']['comments'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(comments) as total'), 'posted')->where('type', 'link')->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
        $this->iAStats['graph']['comments'][0] = DB::table('posts')->where('page_id', $id)->select(DB::raw($this->conversion.'(comments) as total'), 'posted')->where('type', 'link')->where('instant_article', 1)->whereBetween('posted', [$this->from, $this->to])->groupBy(DB::raw($this->timeUnit.'(posted)'))->orderBy('posted', 'ASC')->pluck('total', 'posted');
    }

    /**
     * @param $id
     */
    public function getVideoLabels($id)
    {
        $query = __('metrics.video_label_posts_compare', ['id' => $id, 'from' => $this->from, 'to' => $this->to, 'conversion' => $this->conversion, 'metric' => $this->videoMetric]);
        $result = \DB::select($query);
        $this->videoLabelTotals = $result;
    }

    /**
     * @param $id
     */
    public function getYesterdayVideoLabels($id)
    {
        $from = request()->get('from') ? Carbon::parse(request()->get('from'))->startOfDay() :    Carbon::now()->startOfDay();
        $to = request()->get('to') ?     Carbon::parse(request()->get('to'))->endOfDay() :        Carbon::now()->endOfDay();
        $diff = ($to->diffInDays($from) !== 0 ? $to->diffInDays($from)+1 : $to->diffInDays($from)+1);
        $this->videoLabelCompareDateFrom = $from->subDays($diff);
        $this->videoLabelCompareDateTo = $to->subDays($diff);


        $query = __('metrics.video_label_posts_compare', ['id' => $id, 'from' => $this->videoLabelCompareDateFrom, 'to' => $this->videoLabelCompareDateTo, 'conversion' => $this->conversion, 'metric' => $this->videoMetric]);
        $result = \DB::select($query);
        $this->videoYesterdayLabelTotals = $result;
    }
}

