@if(count($expenses) > 0)
 
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Username</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense['title'] }}</td>
                    <td>{{ $expense['user']['name'] }}</td>
                    <td>{{ $expense['amount'] }}</td>
                    <td>{{ $expense['date'] }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', ['id' => $expense['id']]) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('expenses.destroy', ['id' => $expense['id']]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@else
    <p>No expenses found for the selected year.</p>
@endif