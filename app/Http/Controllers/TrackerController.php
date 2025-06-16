<?php

namespace App\Http\Controllers;

use App\Models\Tracker;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $userId = auth()->id();

    $transactions = Tracker::where('user_id', $userId)->get();
    $latestTransactions = Tracker::where('user_id', $userId)->orderBy('id', 'desc')->take(4)->get();

    $totalIncome = $transactions->where('type', 'Income')->sum('amount');
    $totalExpense = $transactions->where('type', 'Expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;

    $expensesByCategory = $transactions
        ->where('type', 'Expense')
        ->groupBy('category')
        ->map(fn($group) => $group->sum('amount'));

    $categories = \App\Models\Category::where('user_id', $userId)->get();

    return view('dashboard', [
        'transactions' => $latestTransactions,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
        'balance' => $balance,
        'expensesByCategory' => $expensesByCategory,
        'categoryLabels' => $expensesByCategory->keys(),
        'categoryValues' => $expensesByCategory->values(),
        'categories' => $categories, 
    ]);
}


    public function monthlySummary(Request $request){
    $month = $request->input('month');
    $userId = auth()->id();

    if ($month) {
        $transactions = Tracker::where('user_id', $userId)
    ->when($month, function ($query) use ($month) {
        return $query->where('date', 'like', $month . '%');
    })
    ->get();
        
    } else {
            $transactions = Tracker::where('user_id', $userId)->get();
        
    }
    $expensesByCategory = $transactions
        ->where('type', 'Expense')
        ->groupBy('category')
        ->map(function ($group) {
            return $group->sum('amount');
        });

    $monthlyExpense = $transactions->where('type', 'Expense')->groupBy('category')->map(function ($items) {
        $totalAmount = $items->sum('amount');
        return (object)[
            'category' => $items->first()->category,
            'amount' => $totalAmount
        ];
    })->values();

    $monthlyIncome = $transactions->where('type', 'Income')->groupBy('category')->map(function ($items) {
        $totalAmount = $items->sum('amount');
        return (object)[
            'category' => $items->first()->category,
            'amount' => $totalAmount
        ];
    })->values();


    $totalIncome = $transactions->where('type', 'Income')->sum('amount');
    $totalExpense = $transactions->where('type', 'Expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;

    return view('monthlySummary', [
        'transactions' => $transactions,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
        'balance' => $balance,
        'expensesByCategory' => $expensesByCategory,
        'categoryLabels' => $expensesByCategory->keys(),
        'categoryValues' => $expensesByCategory->values(),
        'monthlyIncome' => $monthlyIncome,
        'monthlyExpense' => $monthlyExpense,
    ]);
}




    /**
     * Show the form for creating a new resource.
     */
    public function showforall()
{
    $userId = auth()->id();
    $transactions = Tracker::where('user_id', $userId)->latest()->paginate(10);

    $totalIncome = $transactions->where('type', 'Income')->sum('amount');
    $totalExpense = $transactions->where('type', 'Expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;

    $expensesByCategory = $transactions
        ->where('type', 'Expense')
        ->groupBy('category')
        ->map(fn($group) => $group->sum('amount'));

    return view('allTransaction', [
        'transactions' => $transactions,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
        'balance' => $balance,
        'expensesByCategory' => $expensesByCategory,
        'categoryLabels' => $expensesByCategory->keys(),
        'categoryValues' => $expensesByCategory->values(),
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'type' => 'required|string|max:64',
        'amount' => 'required',
        'category' => 'nullable|string|max:255',
        'date' => 'required|date',
        'note' => 'nullable|string|max:255', 
    ]);

    $data = $request->all();
    $data['note'] = $data['note'] ?? '';
    $data['user_id'] = auth()->id();

    Tracker::create($data);

    return redirect('/dashboard')->with('success', 'Transaction saved successfully!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Tracker::findOrFail($id);
        return view('editTransaction', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $request->validate([
            'type' => 'required|string|max:64',
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $request->merge(['note' => $request->note ?: '']);

    $transaction = Tracker::findOrFail($id);

    $transaction->type = $request->type;
    $transaction->amount = $request->amount;
    $transaction->category = $request->category;
    $transaction->date = $request->date;
    $transaction->note = $request->note;

    $transaction->save();
        return redirect('/dashboard')->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Tracker::findOrFail($id);
        $transaction->delete();

        return redirect('/dashboard')->with('success', 'Transaction deleted successfully!');
    }
}
