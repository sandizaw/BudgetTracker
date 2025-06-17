<x-app-layout>
  <head>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
<style>
    * {
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
  margin: 0;
  padding: 0;
  background-color: #f8f9fb;
  color: #333;
}

.container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

header h1 {
  font-size: 32px;
  margin: 0;
}

.profile-icon {
  width: 32px;
  height: 32px;
  background: #e0e0e0;
  border-radius: 50%;
}

.summary {
  display: flex;
  justify-content: space-between;
  margin-top: 30px;
  gap: 20px;
}

.card {
  flex: 1;
  background-color: #f0f4ff;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.expense {
  background-color: #ffecec;
}

.balance {
  background-color: #e6ffec;
}

.card p {
  margin: 0;
  font-size: 14px;
  color: #555;
}

.card h2 {
  margin-top: 10px;
  font-size: 28px;
  font-weight: 700;
}

.main {
  display: flex;
  margin-top: 30px;
  gap: 20px;
}

.form-section,
.chart-section {
  flex: 1;
  background-color: #ffffff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

form select,
form input {
  padding: 10px;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  font-size: 14px;
  background-color: #f9f9f9;
}


.transactions {
  margin-top: 40px;
}

.transactions h3 {
  font-size: 20px;
  margin-bottom: 10px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

th, td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #f1f1f1;
}

td.expense {
  color: #dc3545;
  font-weight: bold;
}

td.income {
  color: #28a745;
  font-weight: bold;
}
.save-button{
  background-color: #007bff;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
#create-button,
#all-transactions {
  background-color: #007bff;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  text-decoration: none;
  font-size: 14px;
  display: inline-block;
  float: right;
  margin-left: 10px;
  margin-top: 20px;
}
.card.balance.positive {
  background-color: #e6ffec;
  color: #28a745;
}

.card.balance.negative {
  background-color: #ffecec;
  color: #dc3545;
}

@media (max-width: 768px) {
  .container {
    margin: 20px 10px;
    padding: 15px;
  }

  header h1 {
    font-size: 24px;
    text-align: center;
  }

  .summary {
    flex-direction: column;
    gap: 15px;
  }

  .card {
    padding: 15px;
    font-size: 14px;
  }

  .card h2 {
    font-size: 22px;
  }

  .main {
    flex-direction: column;
  }

  .form-section,
  .chart-section {
    padding: 15px;
    margin-bottom: 20px;
  }

  form select,
  form input {
    font-size: 16px;
    padding: 12px;
  }

  .transactions h3 {
    font-size: 18px;
  }

  table {
    font-size: 12px;
  }

  th, td {
    padding: 8px 10px;
  }

  #create-button,
  #all-transactions {
    float: none;
    display: block;
    width: 100%;
    margin: 10px 0 20px;
    text-align: center;
  }

  .action-buttons form {
    display: inline-block;
    margin-right: 10px;
  }

  /* Make the transaction action buttons smaller */
  .btn-warning, .btn-danger {
    padding: 6px 8px;
    font-size: 14px;
  }
}


</style>

  <div class="container">
    <header>
  <h1>MyBudget</h1>
</header>

    <div class="summary">
      <div class="card income">
        <p>Total Income</p>
        <h2>${{$totalIncome}}</h2>
      </div>
      <div class="card expense">
        <p>Total Expenses</p>
        <h2>${{$totalExpense}}</h2>
      </div>
      <div class="card balance {{ $balance < 0 ? 'negative' : 'positive' }}">
          <p>Balance Left</p>
          <h2>${{ $balance }}</h2>
      </div>
    </div>
    <button class="btn btn-primary create-category" id="create-button">Create Category</button>
    <div class="main">
      <div class="form-section">
        <h3>Add Transaction</h3>
        <form action="{{ route('tracker.store') }}" method="POST">
          @csrf
          <label>Type</label>
          <select name="type" required>
            <option value="Expense">Expense</option>
            <option value="Income">Income</option>
          </select>

          <label>Amount</label>
          <input type="number" name="amount" required />

          <label>Category</label>
          <select name="category">
            @foreach($categories as $category)
              <option>{{ $category->name }}</option>
            @endforeach
          </select>
          <label>Date</label>
          <input type="date" name="date" required />

          <label>Notes</label>
          <input type="text" name="note" />

          <button type="submit" class="save-button">Save</button>
        </form>
      </div>

      <div class="chart-section">
        <h3>Expenses by Category</h3>
        <canvas id="expenseChart"></canvas>
      </div>
    </div>
    
    <section class="transactions">
      <h3>Latest Transactions</h3>
          <button class="btn btn-primary" id="all-transactions"><a href="{{'/allTransactions'}}">All Transactions</a></button>
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
          <tr>
            <td>{{ $transaction->category }}</td>
            <td class="{{ strtolower($transaction->type) }}">
              {{ $transaction->type === 'Expense' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
            </td>
            <td>{{ $transaction->date }}</td>
            <td>{{ $transaction->note }}</td>
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
  </div>

  <script>
    const categoryLabels = @json($categoryLabels);
    const categoryValues = @json($categoryValues);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById("expenseChart").getContext("2d");

    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: categoryLabels,
        datasets: [{
          data: categoryValues,
          backgroundColor: [
            "#007bff", "#28a745", "#ffc107", "#dc3545", "#6f42c1", "#17a2b8"
          ],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right',
          }
        }
      }
    });
  </script>
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

document.querySelectorAll('.create-category').forEach(button => {
  button.addEventListener('click', async function () {
    const { value: categoryName } = await Swal.fire({
      title: "Enter Category Name",
      input: "text",
      inputLabel: "Category Name",
      showCancelButton: true,
      inputValidator: (value) => {
        if (!value) {
          return "You need to write something!";
        }
      }
    });

    if (categoryName) {
      try {
        const response = await fetch("{{ route('category.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ name: categoryName })
        });

        const result = await response.json();
        if (response.ok) {
          Swal.fire("Success", result.message || "Category created successfully", "success")
            .then(() => location.reload());
        } else {
          Swal.fire("Error", result.message || "Failed to create category", "error");
        }
      } catch (err) {
        Swal.fire("Error", "Something went wrong!", "error");
      }
    }
  });
});

</script>
  
</x-app-layout>
