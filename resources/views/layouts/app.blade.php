<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AgroVision AI')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="nav">
    <div class="container nav-inner">
        <a class="brand" href="{{ route('home') }}"><span class="leaf">◉</span> AgroVision AI</a>

        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('predictions.create') }}">Scan Leaf</a>
                <a href="{{ route('predictions.index') }}">History</a>

                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif

                <div class="notification-wrap" id="notificationWrap">
                    <button type="button" class="notification-bell" id="notificationBell" aria-label="Notifications" aria-expanded="false">
                        <span aria-hidden="true">🔔</span>
                        <span class="notification-count {{ $unreadNotificationCount > 0 ? '' : 'hidden' }}" id="notificationCount">
                            {{ $unreadNotificationCount }}
                        </span>
                    </button>

                    <div class="notification-menu hidden" id="notificationMenu">
                        <div class="notification-menu-head">
                            <strong>Notifications</strong>
                            @if($unreadNotificationCount > 0)
                                <form method="POST" action="{{ route('notifications.read-all') }}">
                                    @csrf
                                    <button class="link-button small-link">Mark all read</button>
                                </form>
                            @endif
                        </div>

                        <div id="notificationList">
                            @forelse($navNotifications as $notification)
                                @php($data = $notification->data)
                                <a
                                    class="notification-item {{ $notification->read_at ? '' : 'unread' }}"
                                    href="{{ $data['url'] ?? route('dashboard') }}"
                                    data-notification-id="{{ $notification->id }}"
                                >
                                    <strong>{{ $data['title'] ?? 'AgroVision update' }}</strong>
                                    <span>{{ $data['message'] ?? '' }}</span>
                                    <small>{{ $notification->created_at?->diffForHumans() }}</small>
                                </a>
                            @empty
                                <div class="notification-empty" id="notificationEmpty">No notifications yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="link-button">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a class="btn btn-sm" href="{{ route('register') }}">Create account</a>
            @endauth
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="container"><div class="alert success">{{ session('success') }}</div></div>
    @endif

    @if($errors->any())
        <div class="container">
            <div class="alert error">
                <strong>Please fix the following:</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="container">AgroVision AI · SE-331 Project · AI results are preliminary screening only.</div>
</footer>

@auth
<script src="{{ rtrim(config('services.realtime.client_url'), '/') }}/socket.io/socket.io.js"></script>
<script>
(function () {
    const wrap = document.getElementById('notificationWrap');
    const bell = document.getElementById('notificationBell');
    const menu = document.getElementById('notificationMenu');
    const count = document.getElementById('notificationCount');
    const list = document.getElementById('notificationList');
    const userId = @json(auth()->id());
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    function setUnreadCount(value) {
        const number = Math.max(0, Number(value || 0));
        count.textContent = number;
        count.classList.toggle('hidden', number === 0);
    }

    function closeMenu() {
        menu?.classList.add('hidden');
        bell?.setAttribute('aria-expanded', 'false');
    }

    bell?.addEventListener('click', function (event) {
        event.stopPropagation();
        menu?.classList.toggle('hidden');
        bell.setAttribute('aria-expanded', menu?.classList.contains('hidden') ? 'false' : 'true');
    });

    document.addEventListener('click', function (event) {
        if (wrap && !wrap.contains(event.target)) closeMenu();
    });

    list?.addEventListener('click', async function (event) {
        const item = event.target.closest('[data-notification-id]');
        if (!item) return;

        const id = item.dataset.notificationId;
        if (!id || !item.classList.contains('unread')) return;

        event.preventDefault();
        const destination = item.href;

        try {
            const response = await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
            });

            if (response.ok) {
                const data = await response.json();
                item.classList.remove('unread');
                setUnreadCount(data.unread_count);
            }
        } catch (error) {
            console.warn('Could not mark notification as read.', error);
        } finally {
            window.location.href = destination;
        }
    });

    function prependNotification(notification, unreadCount) {
        if (!notification || !list) return;

        document.getElementById('notificationEmpty')?.remove();

        const item = document.createElement('a');
        item.className = 'notification-item unread';
        item.href = notification.url || '/dashboard';
        item.dataset.notificationId = notification.id || '';

        const title = document.createElement('strong');
        title.textContent = notification.title || 'AgroVision update';

        const message = document.createElement('span');
        message.textContent = notification.message || '';

        const time = document.createElement('small');
        time.textContent = 'just now';

        item.append(title, message, time);
        list.prepend(item);
        setUnreadCount(unreadCount ?? (Number(count.textContent || 0) + 1));
    }

    if (typeof io === 'function') {
        const socket = io(@json(config('services.realtime.client_url')));
        window.agrovisionSocket = socket;

        socket.on('connect', function () {
            socket.emit('join-user', userId);
        });

        socket.on('notification-received', function (payload) {
            prependNotification(payload?.notification, payload?.unread_count);
        });

        socket.on('connect_error', function (error) {
            console.warn('Realtime notification service unavailable.', error?.message || error);
        });
    }
})();
</script>
@endauth

@stack('scripts')
</body>
</html>
