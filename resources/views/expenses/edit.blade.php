<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <title>Document</title>
</head>
<body>
<div class="container">
        <h2>Edit Expense</h2>
        <form action="{{ route('expenses.update', ['id' => $expense->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                        id="title" name="title" value="{{ $expense['title'] }}" required>
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control @error('amount') is-invalid @enderror"
                        id="amount" name="amount" value="{{ $expense['amount'] }}" step="0.01" required>
                @error('amount')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror"
                        id="date" name="date" value="{{$expense['date']  }}" required>
                @error('date')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
           
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" required >
              <option value="">please select user</option> 
                @foreach($user as  $users)
                
                    <option value="{{ $users['id']}}" @if($expense["user"]["id"] == $users["id"]) selected @endif>{{ $users['name'] }}</option>
                @endforeach
            </select>

            <!-- Your edit form fields go here -->
            <button type="submit" class="btn btn-primary">Update Expense</button>
            <a href="{{route('dashboard')}}" class="btn btn-warning">back</a>

        </form>
    </div>
</body>
</html>