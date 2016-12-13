<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $atividades = [];

    public function index()
    {
        if (isset($_GET['update'])) {
            Cache::flush();
            return redirect()->route('home');
        }

        $last_sync = Cache::remember('last_sync', 30, function () {
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
                $cache = Cache::remember($key.$page, 30, function () use ($url, $page) {
                    return file_get_contents($url.'?page='.$page);
                });

                $contents = explode('profile-activities-panel', $cache)[2];
                $list = explode('events-list', $contents)[1];
                $xablau1 = explode('row collapse table-header light-theme', $list)[1];
                $all = collect(explode('row collapse table-body light-theme', $xablau1));

                foreach ($all as $k => $single) {
                    if ($k != 0) {

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
