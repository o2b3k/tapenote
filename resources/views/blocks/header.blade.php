<header class="header">
    <div class="header-block header-block-collapse hidden-lg-up">
        <button class="collapse-btn" id="sidebar-collapse-btn">
            <i class="fa fa-bars"></i>
        </button>
    </div>
    <div class="header-block header-block-nav">
        <?php $user = Auth::user() ?>
        @isset($user)
            <ul class="nav-profile">
                <li class="profile dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true" aria-expanded="false">
                        <span class="name">{{ $user->name . ' ' . $user->surname }}</span>
                    </a>
                    <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                        <a class="dropdown-item" href="{{ route('users.editForm', ['id' => $user->id]) }}">
                            <i class="fa fa-user icon"></i>
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="fa fa-power-off icon"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        @endisset
    </div>
</header>