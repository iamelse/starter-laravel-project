@extends('admin.layouts.app')

@section('content')
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Role and Permissions</h3>
                    <p class="text-subtitle text-muted">Edit the role and update its permissions.</p>
                </div>
            </div>
        </div>        
    </div>

    <!-- Edit Role Form -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form method="POST" action="{{ route('roles.and.permissions.update', $role->id) }}">
                                @csrf
                                @method('PUT')
                            
                                <div class="form-group mandatory mb-3">
                                    <label class="form-label" for="role-name">Role Name</label>
                                    <input type="text" class="form-control @error('role_name') is-invalid @enderror" id="role-name" name="role_name" value="{{ old('role_name', $role->name) }}">
                                    @error('role_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            
                                <div class="form-group mandatory mb-4">
                                    <label class="form-label">Permissions</label>
                                    <div class="d-flex justify-content-between mb-3 align-items-center">
                                        <!-- Global Action Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="globalActions" data-bs-toggle="dropdown" aria-expanded="false">
                                                Global Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="globalActions">
                                                <li><a class="dropdown-item select-all-global" href="#">Select All</a></li>
                                                <li><a class="dropdown-item deselect-all-global" href="#">Deselect All</a></li>
                                                <li><a class="dropdown-item expand-all" href="#">Expand All</a></li>
                                                <li><a class="dropdown-item collapse-all" href="#">Collapse All</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    @foreach($permissions as $module => $modulePermissions)
                                        <div class="mb-4">
                                            <!-- Module Header with Expand/Collapse -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <button class="btn btn-outline-primary text-start w-100" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#module-{{ Str::slug($module) }}" 
                                                    aria-expanded="{{ collect($modulePermissions)->pluck('id')->intersect($rolePermissions)->isNotEmpty() ? 'true' : 'false' }}"
                                                    aria-controls="module-{{ Str::slug($module) }}">
                                                    {{ ucfirst($module) }}
                                                </button>
                                                <div class="dropdown ms-2">
                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="moduleActions-{{ Str::slug($module) }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="moduleActions-{{ Str::slug($module) }}">
                                                        <li><a class="dropdown-item select-all-module" href="#" data-module="{{ Str::slug($module) }}">Select All</a></li>
                                                        <li><a class="dropdown-item deselect-all-module" href="#" data-module="{{ Str::slug($module) }}">Deselect All</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                            
                                            <!-- Collapsible Permissions -->
                                            <div class="collapse mt-3 {{ collect($modulePermissions)->pluck('id')->intersect($rolePermissions)->isNotEmpty() ? 'show' : '' }}" 
                                                 id="module-{{ Str::slug($module) }}">
                                                <div class="row">
                                                    @foreach($modulePermissions as $permission)
                                                        <div class="col-md-3 mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input permission-checkbox module-{{ Str::slug($module) }}" 
                                                                       type="checkbox" 
                                                                       name="permissions[]" 
                                                                       value="{{ $permission->id }}" 
                                                                       id="permission-{{ $permission->id }}" 
                                                                       @if(in_array($permission->id, $rolePermissions)) checked @endif>
                                                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>                                                                          
                            
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Edit Role Form end -->
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const expandAllBtn = document.querySelector('.expand-all');
        const collapseAllBtn = document.querySelector('.collapse-all');
        const selectAllGlobalBtn = document.querySelector('.select-all-global');
        const deselectAllGlobalBtn = document.querySelector('.deselect-all-global');
        const allCollapsibles = document.querySelectorAll('.collapse');
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');

        // Expand All Modules
        expandAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            allCollapsibles.forEach(collapse => collapse.classList.add('show'));
        });

        // Collapse All Modules
        collapseAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            allCollapsibles.forEach(collapse => collapse.classList.remove('show'));
        });

        // Select All Permissions Globally
        selectAllGlobalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            allCheckboxes.forEach(checkbox => checkbox.checked = true);
        });

        // Deselect All Permissions Globally
        deselectAllGlobalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            allCheckboxes.forEach(checkbox => checkbox.checked = false);
        });

        // Select All Permissions for a Module
        document.querySelectorAll('.select-all-module').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const moduleClass = `.module-${button.getAttribute('data-module')}`;
                document.querySelectorAll(moduleClass).forEach(checkbox => checkbox.checked = true);
            });
        });

        // Deselect All Permissions for a Module
        document.querySelectorAll('.deselect-all-module').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const moduleClass = `.module-${button.getAttribute('data-module')}`;
                document.querySelectorAll(moduleClass).forEach(checkbox => checkbox.checked = false);
            });
        });
    });
</script>
@endpush