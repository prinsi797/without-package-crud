<div class="sidebar">
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        
        <!-- Show User Module only if the user's role is 'admin' -->
        @if(Auth::user() && Auth::user()->role === 'admin')
            <li><a href="{{ route('users.index') }}">User Module</a></li>
        @endif
    </ul>
</div>
