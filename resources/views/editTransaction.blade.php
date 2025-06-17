<x-app-layout>

  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f0f2f5;
    }

    .edit-form-container {
      max-width: 600px;
      margin: 40px auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    label {
      font-weight: 600;
      color: #333;
    }

    input,
    select {
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: #007bff;
      background-color: #fff;
    }

    .save-button {
      padding: 14px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .save-button:hover {
      background-color: #0056b3;
    }
  </style>

  <div class="edit-form-container">
    <form action="{{ route('tracker.update', $transaction->id) }}" method="POST">
      
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
      {{ __('Edit Transaction') }}
    </h2>
      @csrf
      @method('PUT')

      <label for="type">Type</label>
      <select name="type" required>
        <option value="Expense" {{ $transaction->type === 'Expense' ? 'selected' : '' }}>Expense</option>
        <option value="Income" {{ $transaction->type === 'Income' ? 'selected' : '' }}>Income</option>
      </select>

      <label for="amount">Amount</label>
      <input type="number" name="amount" required value="{{ $transaction->amount }}" />

      <label for="category">Category</label>
      <select name="category" required>
        @php
            $categories = \App\Models\Category::all() ->where('user_id', auth()->id());
        @endphp
        @foreach($categories as $category)
      <option value="{{ $category->name }}" {{ $transaction->category === $category->name ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
  @endforeach
      </select>

      <label for="date">Date</label>
      <input type="date" name="date" required value="{{ $transaction->date }}" />

      <label for="note">Notes</label>
      <input type="text" name="note" value="{{ $transaction->note }}" />

      <button type="submit" class="save-button">Save</button>
    </form>
  </div>
</x-app-layout>
