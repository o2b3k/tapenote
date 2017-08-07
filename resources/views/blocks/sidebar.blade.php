<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="brand">
                <div class="logo">
                    <span class="l l1"></span>
                    <span class="l l2"></span>
                    <span class="l l3"></span>
                    <span class="l l4"></span>
                    <span class="l l5"></span>
                </div>
                {{ env('APP_NAME') }}
            </div>
        </div>
        <nav class="menu">
            <ul class="nav metismenu" id="sidebar-menu">
                <?php $user = Auth::user(); ?>
                @foreach (config('sidebar.left_menu') as $menu)
                    @continue (isset($menu['role']) && (!$user || $user->role !== $menu['role']))
                    <li{!!  Route::currentRouteName() == $menu['route'] ? ' class="active"' : '' !!}>
                        <a href="{{ route($menu['route'])  }}"><i class="{{ $menu['icon'] }}"></i>{{ $menu['title']  }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>