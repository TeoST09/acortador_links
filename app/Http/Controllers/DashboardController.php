<?php

namespace App\Http\Controllers;

use App\Models\LinkClick;
use App\Models\Links;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $userId = $request->user()->id;

        $totalLinks = Links::query()
            ->where('user_id', $userId)
            ->count();

        $activeLinks = Links::query()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->count();

        $totalClicks = LinkClick::query()
            ->join('links', 'links.id', '=', 'link_clicks.link_id')
            ->where('links.user_id', $userId)
            ->count();

        $clicksByDay = LinkClick::query()
            ->join('links', 'links.id', '=', 'link_clicks.link_id')
            ->where('links.user_id', $userId)
            ->where('link_clicks.clicked_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(link_clicks.clicked_at) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $topLinks = Links::query()
            ->where('user_id', $userId)
            ->withCount('clicks')
            ->orderByDesc('clicks_count')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $recentLinks = Links::query()
            ->where('user_id', $userId)
            ->withCount('clicks')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'appUrl' => rtrim(config('app.url'), '/'),
            'analytics' => [
                'total_links' => $totalLinks,
                'active_links' => $activeLinks,
                'total_clicks' => $totalClicks,
                'clicks_by_day' => $clicksByDay,
                'top_links' => $this->formatLinks($topLinks),
                'recent_links' => $this->formatLinks($recentLinks),
            ],
        ]);
    }

    /**
     * @param  EloquentCollection<int, Links>  $links
     * @return array<int, array{id: int, code: string, title: string|null, clicks: int, is_active: bool}>
     */
    private function formatLinks(EloquentCollection $links): array
    {
        return $links
            ->map(fn (Links $link): array => [
                'id' => $link->id,
                'code' => $link->code,
                'title' => $link->title,
                'clicks' => (int) $link->clicks_count,
                'is_active' => (bool) $link->is_active,
            ])
            ->all();
    }
}
