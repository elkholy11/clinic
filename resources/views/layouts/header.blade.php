<header>
    <h1>{{ __('messages.clinic_system') }}</h1>
    <div>
        <form action="{{ route('language.switch') }}" method="POST">
            @csrf
            <select name="lang" onchange="this.form.submit()">
                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
            </select>
        </form>
    </div>
</header>
<nav>
    <ul>
        <li><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
        <li><a href="{{ route('doctors.index') }}">{{ __('messages.doctors') }}</a></li>
        <li><a href="{{ route('patients.index') }}">{{ __('messages.patients') }}</a></li>
        <li><a href="{{ route('appointments.index') }}">{{ __('messages.appointments') }}</a></li>
        <li><a href="{{ route('reports.index') }}">{{ __('messages.reports') }}</a></li>
    </ul>
