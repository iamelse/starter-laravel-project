<?php

namespace Modules\User\Http\Controllers\Web;

use App\Constants\TableConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTableSettingsRequest;
use App\Models\TableSettings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\UserStoreRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::all();
        $user = Auth::user();
        $savedSettings = $user->tableSettings;

        $limits = [5, 10, 20, 50, 100];
        $limit = $request->get('limit', $savedSettings->limit ?? 10);
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'ASC');

        // Retrieve all columns and visible columns
        [$allColumns, $visibleColumns] = $this->_getColumnsForTable();

        // Exclude 'roles' from the database query
        $excludedColumns = ['roles'];
        $queryColumns = array_diff($visibleColumns, $excludedColumns);

        $users = User::search(
            keyword: $request->q,
            columns: $queryColumns
        )
        ->sort(
            sort_by: $sortBy,
            sort_order: $sortOrder
        );
        
        // Apply role filter if present
        if ($role = $request->role) {
            $users->whereHas('roles', fn($query) => $query->where('name', $role));
        }
        
        // Paginate and transform the collection
        $users = $users->paginate($limit)->through(fn($user) => $user->setAttribute('roles', $user->roles->pluck('name')->toArray()));
        
        return view('user::index', [
            'title' => 'User List',
            'users' => $users,
            'columns' => $allColumns,
            'visibleColumns' => $visibleColumns,
            'limits' => $limits,
            'roles' => $roles
        ]);        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('user::create', [
            'title' => 'New User',
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $userData = $request->all();
        $userData['password'] = Hash::make($request->input('password'));

        $user = User::create($userData);
        $role = Role::findById($request->input('role_id'));
        $user->assignRole($role->name);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user::edit', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user = User::findOrFail($user->id);

        try {
            $userData = $request->all();
            if ($request->has('password')) {
                $userData['password'] = Hash::make($request->input('password'));
            }

            $user->update($userData);
            $role = Role::findById($request->input('role_id'));
            $user->syncRoles([$role->name]);

            return redirect()->route('user.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->route('user.index')->with('error', 'Failed to update the user.');
        }
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
    public function saveTableSettings(StoreTableSettingsRequest $request)
    {
        try {
            $modelClass = User::class;
            $modelInstance = app($modelClass)->newInstance();
    
            $tableName = $modelInstance->getTable();
            $modelName = get_class($modelInstance);
    
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
        } catch (ValidationException $e) {
            throw $e;
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
        $allColumns = TableConstants::USER_TABLE_COLUMNS;

        $visibleColumns = Auth::user()->tableSettings->visible_columns ?? $allColumns;
        $visibleColumns = is_array($visibleColumns) ? $visibleColumns : json_decode($visibleColumns);

        return [$allColumns, $visibleColumns];
    }
}
