@extends('admin.layouts.app')

@section('content')
<div id="main-content">
    <div class="page-heading">  
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Users</h3>
                    <p class="text-subtitle text-muted">View and manage all users.</p>
                </div>
            </div>
        </div>        
    </div>

    <!-- Basic Tables start -->
    <section class="section">
        <div class="row">
            <div class="col-12">
                
                <div class="card">
                    <div class="card-content">
                        <div class="card-header d-flex justify-content-end align-items-center">
                            
                            <div class="d-flex align-items-center">
                                <!-- Cog Icon as Modal Button -->
                                <button type="button" class="btn border-0 p-0 me-3" data-bs-toggle="modal" data-bs-target="#tableSettingsModal">
                                    <i class='bx bx-sm bx-cog'></i>
                                </button>

                                <!-- Modal for Table Settings -->
                                <div class="modal fade" id="tableSettingsModal" tabindex="-1" aria-labelledby="tableSettingsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tableSettingsModalLabel">Table Settings</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('user.save.table.settings') }}">
                                                    @csrf
                                                    <!-- Columns Visibility -->
                                                    <h6 class="fw-bold">Columns Visibility</h6>
                                                    <p class="text-muted">Select the columns you want to display in the table.</p>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="d-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                                                                @foreach($columns as $column)
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $column }}" id="column-{{ $column }}" 
                                                                            @if(in_array($column, $visibleColumns)) checked @endif>
                                                                        <label class="form-check-label" for="column-{{ $column }}">{{ ucfirst($column) }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Show Numbering Option -->
                                                    <h6 class="fw-bold mt-4">Show Row Numbering</h6>
                                                    <p class="text-muted">Enable this option to display numbering alongside rows.</p>
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" name="show_numbering" id="show_numbering" 
                                                            @if(old('show_numbering') || (Auth::user()->tableSettings->show_numbering ?? false)) checked @endif>
                                                        <label class="form-check-label" for="show_numbering">Show Row Numbering</label>
                                                    </div>

                                                    <!-- Limit Dropdown -->
                                                    <div class="form-group my-4">
                                                        <h6 for="limit" class="fw-bold mt-4">Items per Page (Limit)</h6>
                                                        <p class="text-muted">Choose how many items should be displayed per page in the table.</p>
                                                        <select name="limit" class="form-select">
                                                            @foreach([5, 10, 20, 50, 100] as $limit)
                                                                <option value="{{ $limit }}" {{ (old('limit') ?: (Auth::user()->tableSettings->limit ?? 10)) == $limit ? 'selected' : '' }}>
                                                                    {{ $limit }} items
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Save Button -->
                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary">Save Settings</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn border-0 p-0 me-3" data-bs-toggle="modal" data-bs-target="#tableFiltersModal">
                                    <i class='bx bx-sm bx-filter-alt'></i>
                                </button>

                                <!-- Modal for Filters -->
                                <div class="modal fade" id="tableFiltersModal" tabindex="-1" aria-labelledby="tableFiltersModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tableFiltersModalLabel">Table Filters</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="GET" action="{{ route('user.index') }}">
                                                    <!-- Search Input -->
                                                    <div class="mb-3">
                                                        <label for="search" class="fw-bold">Search:</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            placeholder="Search"
                                                            name="q"
                                                            value="{{ request('q') }}"
                                                        />
                                                    </div>
                                                    
                                                    <!-- Limit Dropdown -->
                                                    <div class="mb-3">
                                                        <label for="limit" class="fw-bold">Limit:</label>
                                                        <select name="limit" class="form-select">
                                                            @foreach($limits as $limit)
                                                                <option value="{{ $limit }}" 
                                                                    {{ (request('limit') ?: Auth::user()->tableSettings->limit ?? 10) == $limit ? 'selected' : '' }}>
                                                                    {{ $limit }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Sort By Dropdown -->
                                                    <div class="mb-3">
                                                        <label for="sort_by" class="fw-bold">Sort By:</label>
                                                        <select name="sort_by" class="form-select">
                                                            @foreach($visibleColumns as $column)
                                                                @if(!in_array($column, ['roles']))
                                                                    <option value="{{ $column }}" {{ request('sort_by') == $column ? 'selected' : '' }}>
                                                                        {{ ucfirst($column) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Order Dropdown -->
                                                    <div class="mb-3">
                                                        <label for="sort_order" class="fw-bold">Order:</label>
                                                        <select name="sort_order" class="form-select">
                                                            <option value="ASC" {{ request('sort_order') == 'ASC' ? 'selected' : '' }}>Ascending</option>
                                                            <option value="DESC" {{ request('sort_order') == 'DESC' ? 'selected' : '' }}>Descending</option>
                                                        </select>
                                                    </div>

                                                    <!-- Role Filter Dropdown -->
                                                    <div class="mb-3">
                                                        <label for="role" class="fw-bold">Role:</label>
                                                        <select name="role" class="form-select">
                                                            <option value="">All Roles</option> <!-- Option for no role filter -->
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                                                    {{ ucfirst($role->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Buttons for Apply Filters and Clear Filters -->
                                                    <div class="row mt-3 g-2 justify-content-end">
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary w-100">
                                                                Apply Filters
                                                            </button>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="{{ route('user.index') }}" class="btn btn-secondary w-100">
                                                                Clear Filters
                                                            </a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- New User Button -->
                                @can('create_users', $users)
                                <a href="{{ route('user.create') }}" type="button" class="btn border-0 p-0 me-3">
                                    <i class='bx bx-sm bx-plus-circle' ></i>
                                </a>
                                @endcan

                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table with outer spacing -->
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            @if(Auth::user()->tableSettings->show_numbering ?? false)
                                                <th>No.</th>
                                            @endif
                                            @foreach ($visibleColumns as $visibleColumn)
                                                <th>{{ ucfirst(str_replace('_', ' ', $visibleColumn)) }}</th>
                                            @endforeach
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                        <tr>
                                            @if(Auth::user()->tableSettings->show_numbering ?? false)
                                                <td class="text-bold-500">{{ $loop->iteration }}</td>
                                            @endif
                                            @foreach ($visibleColumns as $column)
                                                <td>
                                                    {{ is_array($user->{$column}) ? implode(', ', $user->{$column}) : $user->{$column} }}
                                                </td>
                                            @endforeach
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('view_users', $user)
                                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-outline-warning d-flex justify-content-center align-items-center p-0" style="width: 36px; height: 36px;">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    @endcan
                                                    @can('view_users', $user)
                                                    <form method="POST" action="{{ route('user.destroy', $user->id) }}" style="margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center p-0" style="width: 36px; height: 36px;" id="delete-btn">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>                                                                                                                                        
                                        </tr>
                                        @empty
                                        <tr>
                                            <!-- Calculate the colspan dynamically -->
                                            <td class="text-center" colspan="{{ count($visibleColumns) + (Auth::user()->tableSettings->show_numbering ?? false ? 2 : 1) }}">
                                                No Data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination links -->
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    {{ $users->withQueryString()->links() }}
                                </div>
                            </div>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>        
    <!-- Basic Tables end -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        const deleteButtons = document.querySelectorAll('#delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: 'btn btn-primary mx-1',
                        cancelButton: 'btn btn-danger mx-1'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        let errorMessages = @json($errors->all());
        console.log(errorMessages);

        @if ($errors->any())
            errorMessages.forEach((error) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: error,
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Oops, something went wrong...',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
</script>
@endpush