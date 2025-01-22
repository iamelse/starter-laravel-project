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
                        <div class="card-body">
                            <form method="GET" action="{{ route('user.index') }}">
                                <div class="row g-3 align-items-end">
                                    <!-- Limit Dropdown -->
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
                                        <label for="sort_by" class="fw-bold">Sort By:</label>
                                        <select name="sort_by" class="form-select">
                                            @foreach($visibleColumns as $column)
                                                <option value="{{ $column }}" {{ request('sort_by') == $column ? 'selected' : '' }}>
                                                    {{ ucfirst($column) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                
                                    <!-- Order Dropdown -->
                                    <div class="col-md-3">
                                        <label for="sort_order" class="fw-bold">Order:</label>
                                        <select name="sort_order" class="form-select">
                                            <option value="ASC" {{ request('sort_order') == 'ASC' ? 'selected' : '' }}>Ascending</option>
                                            <option value="DESC" {{ request('sort_order') == 'DESC' ? 'selected' : '' }}>Descending</option>
                                        </select>
                                    </div>
                
                                    <!-- Search Input -->
                                    <div class="col-md-3">
                                        <label for="search" class="fw-bold">Search:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Search"
                                            name="q"
                                            value="{{ request('q') }}"
                                        />
                                    </div>
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
                                               

                <div class="card">
                    <div class="card-content">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Users List</h5>
                            
                            <div class="d-flex align-items-center">
                                <!-- Cog Icon as Modal Button -->
                                <button type="button" class="btn border-0 p-0 me-3" data-bs-toggle="modal" data-bs-target="#tableSettingsModal">
                                    <i class='bx bx-sm mt-2 bx-cog'></i>
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
                                
                                <!-- New User Button -->
                                @can('create_users', $users)
                                <a href="{{ route('user.create') }}" class="btn btn-primary">
                                    New User
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
                                            @foreach ($visibleColumns as $visibleColumn)
                                                <td>{{ $user->$visibleColumn }}</td>
                                            @endforeach
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @can('view_users', $user)
                                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    @endcan
                                                    @can('view_users', $user)
                                                    <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" id="delete-btn">Delete</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>                                                
                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center" colspan="5">No Data</td>
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

        @if($errors->any())
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Oops, something went wrong.',
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