<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $atividades = [];

    /**
     * Time to store info in cache
     * 2 hours
     *
     * @var int
     */
    protected $cache_time = 60 * 2;

    public function index()
    {
        if (isset($_GET['update'])) {
            Cache::flush();
            return redirect()->route('home');
        }

        $last_sync = Cache::remember('last_sync', $this->cache_time, function () {
            return Carbon::now();
        });

        $this->atividades = collect($this->atividades);

        $urls = [
            'workshop' => 'http://campuse.ro/events/vire-um-curador-na-cpbr10-votos/workshop',
            'talk' => 'http://campuse.ro/events/vire-um-curador-na-cpbr10-votos/talk',
        ];

        foreach ($urls as $key => $url) {
            $times = ($key == 'workshop') ? 2 : 9;

            for ($page = 1; $page <= $times; $page++) {
                $cache = Cache::remember($key.$page, $this->cache_time, function () use ($url, $page) {
                    return file_get_contents($url.'?page='.$page);
                });

                $contents = explode('profile-activities-panel', $cache)[2];
                $list = explode('events-list', $contents)[1];
                $xablau1 = explode('row collapse table-header light-theme', $list)[1];
                $all = collect(explode('row collapse table-body light-theme', $xablau1));

                foreach ($all as $k => $single) {
                    if ($k != 0) {

                        $content_tags = $this->between('<div class="large-2 text-right columns">', '</div></div>', $single);
                        $content_tags_div = '<div class="text-right activity-tag">';
                        $content_tags_dirty = explode($content_tags_div, $content_tags);
                        $tags_num = collect($content_tags_dirty)->count() - 1;
                        $tags = collect([]);
                        for ($i = 1; $i <= $tags_num; $i++) {
                            $tag = str_replace('</div>', '', $content_tags_dirty[$i]);
                            $tag = str_replace("\n", '', $tag);
                            $tags->push($tag);
                        }

                        $link = 'http://campuse.ro' . $this->between('<strong><a href="', '">', $single);

                        $title = explode('">', $single)[3];
                        $title = explode('</a>', $title)[0];

                        $subscribers = $this->between('<span class="attendees right">', '</span>', $single);

                        // $slug = str_slug($title);
                        // $cache_atividade = Cache::remember($slug, 30, function () use ($link) {
                        //     return file_get_contents($link);
                        // });

                        // $author = $this->between('<meta name="author" content="', '">', $cache_atividade);

                        $atividade = [
                            'link' => $link,
                            'title' => $title,
                            'subscribers' => $subscribers,
                            'type' => $key,
                            'tags' => $tags,
                            // 'author' => $author,
                        ];

                        $this->atividades->push($atividade);
                    }
                }
            }
        }

        $data['atividades'] = $this->atividades->sortByDesc('subscribers');
        $data['sum_subscribers'] = $this->atividades->sum('subscribers');
        $data['sum_activities'] = $this->atividades->count();
        $data['last_sync'] = $last_sync;

        return view('home', $data);
    }

    protected function between($start, $end, $content)
    {
        return explode($end, explode($start, $content)[1])[0];
    }
}
