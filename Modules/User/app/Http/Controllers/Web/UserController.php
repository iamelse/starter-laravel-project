<?php

namespace Modules\User\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TableSettings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\User\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $savedSettings = $user->tableSettings;

        $limits = [5, 10, 20, 50, 100];
        $limit = $request->get('limit', $savedSettings->limit ?? 10);
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'ASC');

        // Retrieve all columns and visible columns
        [$allColumns, $visibleColumns] = $this->_getColumnsForTable();

        $users = User::search(
            keyword: $request->q,
            columns: $visibleColumns
        )->sort(
            sort_by: $sortBy,
            sort_order: $sortOrder
        )->paginate($limit);

        return view('user::index', [
            'title' => 'User List',
            'users' => $users,
            'columns' => $allColumns,
            'visibleColumns' => $visibleColumns,
            'limits' => $limits
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            User::findOrFail($user->id)->delete();
            return redirect()->route('user.index')->with('success', 'User deleted successfully.');
        } catch (ModelNotFoundException $e) {
            Log::error('User not found: ' . $e->getMessage());
            return redirect()->route('user.index')->with('error', 'User not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('user.index')->with('error', 'Failed to delete the user.');
        }
    }

    /**
     * Save table settings for the user.
     */
    public function saveTableSettings(Request $request)
    {
        try {
            $modelClass = User::class;
            $modelInstance = app($modelClass)->newInstance();
    
            $tableName = $modelInstance->getTable();
            $modelName = get_class($modelInstance);
    
            $request->validate([
                'columns' => 'array|min:1',
                'columns.*' => 'string|in:' . implode(',', Schema::getColumnListing($modelInstance->getTable())),
                'limit' => 'nullable|in:5,10,20,50,100',
            ]);
    
            $columns = $request->input('columns', []);
            $showNumbering = $request->has('show_numbering') ? true : false;
    
            TableSettings::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'table_name' => $tableName,
                    'model_name' => $modelName,
                ],
                [
                    'visible_columns' => json_encode($columns),
                    'limit' => $request->input('limit', 10),
                    'show_numbering' => $showNumbering,
                ]
            );
    
            return redirect()->back()->with('success', 'Table settings saved successfully!');
        } catch (\Exception $e) {
            // Log the error and return a failure message
            Log::error('Error saving table settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save table settings.');
        }
    }

    /**
     * Get all columns and visible columns for the table.
     */
    private function _getColumnsForTable(): array
    {
        $table = (new User)->getTable();
        $allColumns = Schema::getColumnListing($table);

        $visibleColumns = Auth::user()->tableSettings->visible_columns ?? $allColumns;
        $visibleColumns = is_array($visibleColumns) ? $visibleColumns : json_decode($visibleColumns);

        return [$allColumns, $visibleColumns];
    }
}
