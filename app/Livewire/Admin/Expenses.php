<?php

namespace App\Livewire\Admin;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class Expenses extends Component
{
    use WithPagination;

    public $description, $amount, $category, $expense_date, $notes;
    public $editingExpenseId = null;
    public $showForm = false;

    public function create()
    {
        $this->reset(['description', 'amount', 'category', 'expense_date', 'notes', 'editingExpenseId']);
        $this->expense_date = now()->format('Y-m-d');
        $this->showForm = true;
    }

    public function edit($expenseId)
    {
        $expense = Expense::findOrFail($expenseId);
        $this->editingExpenseId = $expense->id;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->category = $expense->category;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->notes = $expense->notes;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $data = [
            'description' => $this->description,
            'amount' => $this->amount,
            'category' => $this->category,
            'expense_date' => $this->expense_date,
            'notes' => $this->notes,
        ];

        if ($this->editingExpenseId) {
            Expense::findOrFail($this->editingExpenseId)->update($data);
        } else {
            Expense::create($data);
        }

        $this->showForm = false;
        $this->reset(['description', 'amount', 'category', 'expense_date', 'notes', 'editingExpenseId']);
    }

    public function delete($expenseId)
    {
        Expense::findOrFail($expenseId)->delete();
    }

    public function render()
    {
        $expenses = Expense::latest('expense_date')->paginate(20);
        $total = Expense::sum('amount');
        $categories = Expense::distinct()->pluck('category')->filter();

        return view('livewire.admin.expenses', compact('expenses', 'total', 'categories'))
            ->layout('components.admin-layout');
    }
}
