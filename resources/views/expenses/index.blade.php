<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container ">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<h1>Expense Tracker</h1>
<form class="ml-2" action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    @method('POST')
    <button type="submit" class="btn btn-danger btn-sm" >Logout</button>
</form>
<div class="row mb-2 mt-2">
<a href="{{ route('expenses.create') }}" class="btn btn-primary float-left">Add Expense</a>
<a href="{{ url('/importview') }}" class="btn btn-warning ml-5">import</a>



</div>


<form method="get" action="/dashboard">
    
    <label for="year">Select Year:</label>
    <select name="year" id="year" required>
    
        @if(isset($selectedYear))
        <option value="0"> please select year</option>

        @foreach($years as $year)
            <option value="{{ $year }}"  @if($year == $selectedYear) selected @endif>{{ $year }}</option>
        @endforeach
        @else
        <option value="0"> please select year</option>
        @foreach($years as $year)
            <option value="{{ $year }}">{{ $year }}</option>
        @endforeach
        @endif

    </select>
    <button type="submit">Filter</button>
</form>

@if(isset($selectedYear))
    <h2>Expense List for {{ $selectedYear }}</h2>
@endif

{{-- Display the chart here --}}
<canvas id="expenseChart" width="400" height="200"></canvas>

{{-- Display the list of expenses --}}
@include('expenses.expenses')
</div>
<script>
    // Assuming you have an array of monthly expenses in your controller like $monthlyExpenses
    var monthlyExpenses = <?php echo json_encode($monthlyExpenses); ?>;

    var ctx = document.getElementById('expenseChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            
            datasets: [{
                label: 'Monthly Expenses',
                data: monthlyExpenses,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>