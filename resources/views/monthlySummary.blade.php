<x-app-layout>
  <head>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  </head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }

    .filter {
      text-align: right;
      margin-bottom: 30px;
    }

    .filter label {
      font-size: 16px;
    }

    input[type="month"] {
      padding: 5px 10px;
      margin-left: 10px;
      font-size: 16px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .budget-bar {
      margin-bottom: 40px;
    }

    .bar {
      display: flex;
      height: 36px;
      width: 100%;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 12px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bar div {
      color: #fff;
      font-weight: bold;
      text-align: center;
      line-height: 36px;
      font-size: 16px;
    }

    .income-left {
      background-color: #81c784;
    }

    .monthly-expenses {
      background-color: #fbc02d;
    }

    .semester-expenses {
      background-color: #64b5f6;
    }

    .legend {
      display: flex;
      justify-content: center;
      gap: 30px;
      font-size: 14px;
      margin-top: 10px;
    }

    .legend-box {
      display: inline-block;
      width: 16px;
      height: 16px;
      margin-right: 6px;
      vertical-align: middle;
      border-radius: 4px;
    }

    .green {
      background-color: #81c784;
    }

    .yellow {
      background-color: #fbc02d;
    }

    .blue {
      background-color: #64b5f6;
    }
    .chart-wrapper {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    canvas#expenseChart,
    canvas#lineChart {
      flex: 1 1 45%;
      max-width: 45%;
      height: auto !important;
    }
    canvas#expenseChart {
      width: 350px !important;
      height: 350px !important;
      display: block;
      margin: 0 auto;
      align-items: left;
    }
    canvas#lineChart {
      width: 500px !important;
      height: 200px !important;
      display: block;
      margin: 0 auto;
      align-items: right;
    }
    canvas#myChart {
      width: 500px !important;
      height: 200px !important;
      display: block;
      margin: 0 auto;
      align-items: right;
    }

.columns {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.column {
  width: 45%;
  background-color: #f9f9f9;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);

}

.column h2 {
  margin-bottom: 15px;
  font-size: 20px;
  color: #333;
}

.column ul {
  list-style-type: none;
  padding: 0;
}

.column li {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  font-size: 16px;
}
.bar-container {
  background-color: #eee;
  border-radius: 6px;
  overflow: hidden;
  height: 24px;
}

.bar-fill {
  height: 100%;
  text-align: right;
  padding-right: 8px;
  line-height: 24px;
  font-size: 14px;
  color: white;
  font-weight: bold;
}
@media screen and (max-width: 768px) {
  .chart-wrapper {
    flex-direction: column;
    align-items: center;
  }
  canvas#expenseChart,
  canvas#lineChart {
    max-width: 100%;
    width: 100%;
  }
  .column {
    width: 100%;
    margin-bottom: 20px;
  } 
  
}

  </style>

  <div class="container">
    <h3>Monthly Summary</h3>
    <div class="filter">
        <form id="monthForm" method="GET" action="{{ route('monthlySummary') }}">
          <label>
            Filter by Month:
            <input type="month" name="month" id="filter-month" value="{{ request('month') }}">
          </label>
        </form>
    </div>
    <section class="budget-bar">
      <div class="bar">
        <div class="income-left" style="width: 20%">${{$totalIncome}}</div>
        <div class="monthly-expenses" style="width: 55%">${{$totalExpense}}</div>
        <div class="semester-expenses" style="width: 25%">${{$balance}}</div>
      </div>
      <div class="legend">
        <span class="legend-box green"></span> Monthly Income
        <span class="legend-box yellow"></span> Monthly Expenses
        <span class="legend-box blue"></span> Left Balance (per month)
      </div>
    </section>


     <div class="chart-section">
        <div class="chart-wrapper">
          <canvas id="expenseChart"></canvas>
          <canvas id="lineChart"></canvas>
      </div>
    </div>

    <section class="columns">
  <div class="column">
    <h2>Monthly Income Details</h2>
    @foreach($monthlyIncome as $income)
      @php
        $incomePercent = ($totalIncome > 0) ? ($income->amount / $totalIncome * 100) : 0;
      @endphp
      <div style="margin-bottom: 12px;">
        <strong>{{ $income->category }} (${{ $income->amount }})</strong>
        <div style="background-color: #e0f2f1; border-radius: 6px; overflow: hidden; height: 24px;">
          <div style="width: {{ $incomePercent }}%; background-color: #81c784; height: 100%; text-align: right; padding-right: 8px; color: white;">
            {{ round($incomePercent) }}%
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="column">
    <h2>Monthly Expense Details</h2>
    @foreach($monthlyExpense as $expense)
      @php
        $expensePercent = ($totalExpense > 0) ? ($expense->amount / $totalExpense * 100) : 0;
      @endphp
      <div style="margin-bottom: 12px;">
        <strong>{{ $expense->category }} (${{ $expense->amount }})</strong>
        <div style="background-color: #fff3cd; border-radius: 6px; overflow: hidden; height: 24px;">
          <div style="width: {{ $expensePercent }}%; background-color: #fbc02d; height: 100%; text-align: right; padding-right: 8px; color: black;">
            {{ round($expensePercent) }}%
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>


  </div>
  

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script> 
  const categoryLabels = {!! json_encode($categoryLabels) !!};
  const categoryValues = {!! json_encode($categoryValues) !!};
</script>

<script>
  // Get the data for the doughnut chart
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
        },
        title: {
          display: true,
          text: 'Monthly Expenses by Category',
          font: {
            size: 20,
            weight: 'bold',
          }
        }
      }
    }
  });


// Get the data for the line chart
  const ctxLine = document.getElementById("lineChart").getContext("2d");
  new Chart(ctxLine, {
    type: "line",
    data: {
      labels: categoryLabels,
      datasets: [{
        label: 'Expense Trend',
        data: categoryValues,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#007bff'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
        title: {
          display: true,
          text: 'Monthly Expense Trend',
          font: {
            size: 40,
            weight: 'bold',
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      },
    }
  });
</script>

  <script>
       document.getElementById('filter-month').addEventListener('change', function () {
        document.getElementById('monthForm').submit();});
  </script>
</x-app-layout>
