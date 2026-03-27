<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Livewire\Attributes\Rule;
use Livewire\Form;

class TransactionForm extends Form
{
    #[Rule('required|in:income,expense')]
    public string $type = 'expense';

    #[Rule('required|numeric|min:1')]
    public float|string $amount = '';

    #[Rule('required|string|min:3|max:50')]
    public string $category = '';

    #[Rule('nullable|string|max:255')]
    public string $description = '';

    #[Rule('required|date')]
    public string $date = '';

    /**
     * Store the transaction to SQLite.
     *
     * @return Transaction
     */
    public function store()
    {
        $this->validate();

        return Transaction::create([
            'type'        => $this->type,
            'amount'      => $this->amount,
            'category'    => $this->category,
            'description' => $this->description,
            'date'        => $this->date,
            'synced'      => true, // Server-side store is always synced
        ]);
    }
}
