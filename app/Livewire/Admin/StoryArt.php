<?php

namespace App\Livewire\Admin;

use App\Models\StoryHistory;
use Livewire\Component;
use Livewire\WithPagination;

class StoryArt extends Component
{
    use WithPagination;

    public function render()
    {
        $stories = StoryHistory::with('product')->latest()->paginate(12);

        return view('livewire.admin.story-art', compact('stories'))
            ->layout('components.admin-layout', ['title' => 'Historias']);
    }
}
