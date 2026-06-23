<?php

namespace App\Livewire\Admin;

use App\Models\MarketingTask;
use Livewire\Component;

class Marketing extends Component
{
    public $activeTab = 'dashboard';
    public $taskTitle, $taskDescription, $taskType;

    public function createTask()
    {
        $this->validate([
            'taskTitle' => 'required|string|max:255',
        ]);

        MarketingTask::create([
            'title' => $this->taskTitle,
            'description' => $this->taskDescription,
            'type' => $this->taskType ?: 'general',
            'status' => 'pending',
        ]);

        $this->reset(['taskTitle', 'taskDescription', 'taskType']);
        session()->flash('message', 'Tarea creada.');
    }

    public function completeTask($taskId)
    {
        MarketingTask::findOrFail($taskId)->update(['status' => 'completed']);
    }

    public function deleteTask($taskId)
    {
        MarketingTask::findOrFail($taskId)->delete();
    }

    public function render()
    {
        $tasks = MarketingTask::latest()->get();
        $pendingTasks = $tasks->where('status', 'pending');
        $completedTasks = $tasks->where('status', 'completed');

        return view('livewire.admin.marketing', compact('tasks', 'pendingTasks', 'completedTasks'))
            ->layout('components.admin-layout');
    }
}
