<?php

namespace App\Classes;

use Illuminate\Support\Collection;
use Spatie\Analytics\Period;
use Spatie\Analytics\Analytics as GoogleAnalytics;

class Analytics
{
    /**
     * @var GoogleAnalytics
     */
    protected $ga;

    /**
     * Analytics constructor.
     * @param GoogleAnalytics $ga
     */
    public function __construct(GoogleAnalytics $ga)
    {
        $this->ga = $ga;
    }

    /**
     * @param int $period
     * @return Collection
     */
    public function fetchUserTypes($period = 7): Collection
    {
        return $this->ga->fetchUserTypes(Period::days($period));
    }

    /**
     * @param int $period
     * @return Collection
     */
    public function fetchTotalVisitorsAndPageViews($period = 7): Collection
    {
        return $this->ga->fetchTotalVisitorsAndPageViews(Period::days($period));
    }

    /**
     * @param int $period
     * @return Collection
     */
    public function fetchVisitorsAndPageViews($period = 7) : Collection
    {
        //retrieve visitors and page view data for the current day and the last seven days
        return $this->ga->fetchVisitorsAndPageViews(Period::days($period));

    }

    /**
     * @param int $period
     * @return Collection
     */
    public function fetchTopReferrers($period = 7): Collection
    {
        $this->ga->fetchTopReferrers(Period::days($period));
    }

    public function fetchPostGAData($link, $from, $to)
    {

        $response = $this->ga->performQuery(
            Period::create($from, $to),
            'ga:pageviews, ga:avgTimeOnPage, ga:bounceRate, ga:avgPageLoadTime',
            [
                'dimensions' => 'ga:pagePath',
                'filters' => 'ga:pagePath=@' . $link,
            ]
        );

        return $response->getTotalsForAllResults();
    }

}