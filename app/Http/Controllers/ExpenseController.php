<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Expense;
use App\models\User;

use Illuminate\Support\Facades\DB as FacadesDB;

class ExpenseController extends Controller
{
    public function __construct()
    {
        // Apply the 'auth' middleware to all methods except 'index' and 'importForm'
        $this->middleware('auth.redirect');

    }

    public function index(Request $request)
    {
        if(empty($request['year'])){
            $years = Expense::select(FacadesDB::raw('YEAR(date) as year'))
            ->groupBy('year')
            ->pluck('year');

            $expenses = Expense::with("user")->get();
            
            // Calculate monthly expenses
            $monthlyExpenses = $expenses->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->date)->format('m');
            })->map(function ($monthExpenses) {
                return $monthExpenses->sum('amount');
            });    
            return view('expenses.index', compact('expenses','monthlyExpenses','years'));
        }else{
            
            $years = Expense::select(FacadesDB::raw('YEAR(date) as year'))
            ->groupBy('year')
            ->pluck('year');
            $expenses = Expense::with("user")->whereYear('date', $request['year'])->get();
            
            // Calculate monthly expenses
            $monthlyExpenses = $expenses->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->date)->format('m');
            })->map(function ($monthExpenses) {
                return $monthExpenses->sum('amount');
            });
            $selectedYear=$request['year'];    
            return view('expenses.index', compact('expenses','selectedYear','monthlyExpenses','years'));
        }
        
       
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $expense = new Expense([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'amount' => $request->input('amount'),
            'date' => $request->input('date'),
        ]);

        $expense->save();

        return redirect()->route('dashboard')->with('success', 'Expense added successfully.');
    }
    public function edit($id)
    {
        $expense = Expense::with('user')->where('id', $id)->first();
        $user= User::all();
        if(!empty($expense) && !empty($user)){
            return view('expenses.edit', compact('expense', 'user'));
        }else{
            return redirect()->route('dashboard')->with('success', 'no expenses found');
        }
        
    }
    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $expense = [
            'user_id' => $request->user_id,
            'title' => $request->input('title'),
            'amount' => $request->input('amount'),
            'date' => $request->input('date'),
        ];
        Expense::where('id', $id)->update($expense);
        return redirect()->route('dashboard')->with('success', "Expenses updated successfully");
    }
    public function importForm()
    {
        return view('import');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

       
        try {
            $file= $request->file('file');

            $filePath = $file->getRealPath();

        // Your Excel processing logic here
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $headers = $sheetData[0]; // Assuming the headers are in the first row
                
            // Check if the headers contain "amount" and "date"
            if (in_array('title', $headers) && in_array('amount', $headers) && in_array('date', $headers)) {
                // Headers are present
                foreach ($sheetData as $index => $row) {
                    // Skip the first row as it contains headers
                    if ($index === 0) {
                        continue;
                    }
            
                    Expense::create([
                        'title' => $row[array_search('title', $headers)],
                        'amount' => $row[array_search('amount', $headers)],
                        "date" =>$row[array_search('date',$headers)],
                        'user_id'=>auth()->user()->id,
                    ]);
            
                
                }
            
               
                return redirect()->route('dashboard')->with('success',"Data has been stored in the 'expense' table.");
            } else {
                // Headers are not present
                
                return redirect()->route('dashboard')->with('success',"Headers are missing in the spreadsheet.");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing expenses. ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return redirect()->route('dashboard')->with('error', 'Expense not found');
        }

        $expense->delete();
        
        return redirect()->route('dashboard')->with('success', 'Expense deleted successfully');
    }
}
