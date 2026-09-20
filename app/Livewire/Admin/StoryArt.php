<?php

namespace App\Livewire\Admin;

use App\Models\StoryHistory;
use Livewire\Component;
use Livewire\WithPagination;

class StoryArt extends Component
{
    use WithPagination;

    public $channelFilter = '';
    public $sortBy = 'latest';

    public function render()
    {
        $query = StoryHistory::with('product');

        if ($this->channelFilter) {
            $query->where('channel', $this->channelFilter);
        }

        $query->orderBy($this->sortBy === 'views' ? 'views' : 'created_at', 'desc');

        $totals = (object) [
            'total' => StoryHistory::count(),
            'facebook' => StoryHistory::where('channel', 'facebook')->count(),
            'instagram' => StoryHistory::where('channel', 'instagram')->count(),
            'views' => StoryHistory::sum('views'),
            'impressions' => StoryHistory::sum('impressions'),
        ];

        $stories = $query->paginate(12);

        return view('livewire.admin.story-art', compact('stories', 'totals'))
            ->layout('components.admin-layout', ['title' => 'Historias']);
    }
}
