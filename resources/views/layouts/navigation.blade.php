@php
    $user = auth()->user();
    $currentLocale = app()->getLocale();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
    <div class="container">
        <a class="navbar-brand brand-mark d-flex align-items-center gap-2" href="{{ url('/') }}">
            <i class="bi bi-check2-square"></i>
            <span>{{ __('messages.app.name') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false"
                aria-label="{{ __('messages.nav.toggle') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if ($user->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/polls*') ? 'active' : '' }}"
                               href="{{ url('/admin/polls') }}">
                                <i class="bi bi-bar-chart-line me-1"></i>{{ __('messages.nav.polls') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                               href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>{{ __('messages.nav.dashboard') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('polls*') ? 'active' : '' }}"
                               href="{{ url('/polls') }}">
                                <i class="bi bi-bar-chart-line me-1"></i>{{ __('messages.nav.polls') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('my-votes*') ? 'active' : '' }}"
                               href="{{ url('/my-votes') }}">
                                <i class="bi bi-check2-circle me-1"></i>{{ __('messages.nav.my_votes') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                               href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>{{ __('messages.nav.dashboard') }}
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item dropdown me-lg-2">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-translate me-1"></i>{{ strtoupper($currentLocale) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ $currentLocale === 'uk' ? 'active' : '' }}"
                               href="{{ route('locale.switch', 'uk') }}">Українська</a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $currentLocale === 'en' ? 'active' : '' }}"
                               href="{{ route('locale.switch', 'en') }}">English</a>
                        </li>
                    </ul>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ $user->name }}
                            <span class="badge bg-{{ $user->isAdmin() ? 'warning text-dark' : 'info text-dark' }} ms-2">
                                {{ $user->isAdmin() ? __('messages.dashboard.role_admin') : __('messages.dashboard.role_voter') }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear me-1"></i>{{ __('messages.nav.profile') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-1"></i>{{ __('messages.nav.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('messages.nav.login') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-lg-2" href="{{ route('register') }}">
                            {{ __('messages.nav.register') }}
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
