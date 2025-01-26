@php
    function isActive($activeRoute) {
        return request()->routeIs($activeRoute);
    }

    $sidebarMenuLists = [
        ['active_route' => 'dashboard.*', 'route' => 'dashboard.index', 'icon' => 'bx-sm bx bxs-dashboard', 'label' => 'Dashboard', 'permission' => 'view_dashboard', 'new_tab' => false],
        'Menu' => [
            ['active_route' => 'users.*', 'route' => 'users.index', 'icon' => 'bx bx-sm bx-user', 'label' => 'User', 'permission' => 'view_users', 'new_tab' => false],
            ['active_route' => 'roles.and.permissions.*', 'route' => 'roles.and.permissions.index', 'icon' => 'bx bx-sm bx-key', 'label' => 'Role and Permission', 'permission' => 'view_roles_and_permissions', 'new_tab' => false],
        ],
    ];
@endphp

<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard.index') }}">
                        <img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo">
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- Theme Toggle Buttons -->
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                @foreach($sidebarMenuLists as $key => $items)
                    @if(is_int($key))
                        @can($items['permission'] ?? '')
                            <li class="sidebar-item{{ isActive($items['active_route']) ? ' active' : '' }}">
                                <a href="{{ route($items['route']) }}" class='sidebar-link' target="{{ $items['new_tab'] ? '_blank' : '_self' }}">
                                    <i class="{{ $items['icon'] }}"></i>
                                    <span>{{ $items['label'] }}</span>
                                </a>
                            </li>
                        @endcan
                    @else
                        @if(collect($items)->contains(fn($item) => auth()->user()->can($item['permission'] ?? '')))
                            <li class="sidebar-title">{{ $key }}</li>
                            @foreach($items as $item)
                                @if(isset($item['submenu']))
                                    @if(collect($item['submenu'])->contains(fn($submenuItem) => auth()->user()->can($submenuItem['permission'] ?? '')))
                                        <li class="sidebar-item has-sub {{ collect($item['submenu'])->contains(fn($submenuItem) => isActive($submenuItem['active_route'])) ? ' active' : '' }}">
                                            <a href="#" class='sidebar-link'>
                                                <i class="{{ $item['icon'] }}"></i>
                                                <span>{{ $item['label'] }}</span>
                                            </a>
                                            <ul class="submenu {{ collect($item['submenu'])->contains(fn($submenuItem) => isActive($submenuItem['active_route'])) ? 'active' : '' }}">
                                                @foreach($item['submenu'] as $submenuItem)
                                                    @can($submenuItem['permission'] ?? '')
                                                        <li class="submenu-item {{ isActive($submenuItem['active_route']) ? 'active' : '' }}">
                                                            <a href="{{ route($submenuItem['route']) }}" class="submenu-link" target="{{ $submenuItem['new_tab'] ? '_blank' : '_self' }}">{{ $submenuItem['label'] }}</a>
                                                        </li>
                                                    @endcan
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @else
                                    @can($item['permission'] ?? '')
                                        <li class="sidebar-item{{ isActive($item['active_route']) ? ' active' : '' }}">
                                            <a href="{{ route($item['route']) }}" class='sidebar-link' target="{{ $item['new_tab'] ? '_blank' : '_self' }}">
                                                <i class="{{ $item['icon'] }}"></i>
                                                <span>{{ $item['label'] }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>