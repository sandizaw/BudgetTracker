<x-app-layout>
  <head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      * {
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
      }

      .transactions {
        max-width: auto;
        margin: 20px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      }

      h3 {
        text-align: left;
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 15px;
      }

      .filter {
        align-items: right;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
      }

      thead {
        background-color:#fff;
        color: rgba(0, 0, 0, 0.08);
      }

      th, td {
        color: #333;
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
      }

      tr:hover {
        background-color:rgb(235, 233, 233);
      }

      td.expense {
        color: #dc3545;
        font-weight: 600;
      }

      td.income {
        color: #28a745;
        font-weight: 600;
      }

      .action-buttons {
        display: flex;
        gap: 8px;
      }

      .action-buttons button {
        border: none;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
      }

      .btn-warning {
        background-color: #ffc107;
        color: #000;
      }

      .btn-warning:hover {
        background-color: #e0a800;
      }

      .btn-danger {
        background-color: #dc3545;
        color: #fff;
      }

      .btn-danger:hover {
        background-color: #bd2130;
      }

  @media (max-width: 768px) {
  .filter {
    flex-direction: column;
    gap: 10px;
  }

  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
    width: 100%;
  }

  thead {
    display: none;
  }

  tr {
    margin-bottom: 15px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 10px;
  }

  td {
    display: flex;
    justify-content: space-between;
    padding: 10px 15px;
    border: none;
    border-bottom: 1px solid #eee;
    font-size: 15px;
  }

  td::before {
    content: attr(data-label);
    font-weight: bold;
    color: #555;
    flex-shrink: 0;
    margin-right: 10px;
  }

  .action-buttons {
    gap: 6px;
    margin-top: 10px;
    justify-content: flex-end;
  }
}

    </style>
  </head>
<section class="transactions">
    <h3>All Transactions</h3>
    
    <div class="filter">
      <label>
        Filter by Month:
        <input type="month" id="filter-month">
      </label>

      <label>
        Filter by Category:
        <select id="filter-category">
          <option value="">All</option>
          @foreach(array_unique($transactions->pluck('category')->toArray()) as $category)
            <option value="{{ $category }}">{{ $category }}</option>
          @endforeach
        </select>
      </label>

      <label>
        Search Notes:
        <input type="text" id="search-description" placeholder="Enter keywords">
      </label>
    </div>

    <table>
      <thead>
        <tr>
          <th>Category</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Notes</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="transaction-body">
       
        @foreach($transactions as $transaction)
          <tr 
            data-category="{{ $transaction->category }}" 
            data-date="{{ $transaction->date }}" 
            data-note="{{ strtolower($transaction->note) }}"
          >
            <td data-label="Category">{{ $transaction->category }}</td>
            <td data-label="Amount" class="{{ strtolower($transaction->type) }}">
              {{ $transaction->type === 'Expense' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
            </td>
            <td data-label="Date">{{ $transaction->date }}</td>
            <td data-label="Notes">{{ $transaction->note }}</td>
            <td data-label="Action" class="action-buttons">
              <form action="{{ route('tracker.edit', $transaction->id) }}" method="get">
                @csrf
                <button class="btn-warning" type="submit"><i class="fas fa-edit"></i></button>
              </form>
              <form action="{{ route('tracker.destroy', $transaction->id) }}" method="post" class="delete-form">
                @csrf
                @method('DELETE')
                <button class="btn-danger fake-delete-btn" type="button"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @endforeach

      </tbody>
    </table>
  </section>
       {{ $transactions->links() }}

  <script>
    document.querySelectorAll('.fake-delete-btn').forEach(button => {
      button.addEventListener('click', function () {
        const row = this.closest('tr');
        Swal.fire({
          title: "Are you sure?",
          text: "This will remove it from the view only.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, remove it!"
        }).then((result) => {
          if (result.isConfirmed) {
            row.remove();
            Swal.fire("Removed!", "The transaction was removed from the view.", "success");
          }
        });
      });
    });

    // Filtering
    const monthInput = document.getElementById('filter-month');
    const categoryInput = document.getElementById('filter-category');
    const searchInput = document.getElementById('search-description');
    const rows = document.querySelectorAll('#transaction-body tr');

    function filterTransactions() {
      const selectedMonth = monthInput.value;
      const selectedCategory = categoryInput.value.toLowerCase();
      const searchTerm = searchInput.value.toLowerCase();

      rows.forEach(row => {
        const rowDate = row.getAttribute('data-date'); 
        const rowCategory = row.getAttribute('data-category').toLowerCase();
        const rowNote = row.getAttribute('data-note');
        const rowMonth = rowDate ? rowDate.slice(0, 7) : '';

        const matchMonth = !selectedMonth || selectedMonth === rowMonth;
        const matchCategory = !selectedCategory || rowCategory === selectedCategory;
        const matchNote = !searchTerm || rowNote.includes(searchTerm);

        if (matchMonth && matchCategory && matchNote) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    monthInput.addEventListener('input', filterTransactions);
    categoryInput.addEventListener('change', filterTransactions);
    searchInput.addEventListener('input', filterTransactions);
  </script>
</x-app-layout>
