@props(['resource'])

@php
    $user = Auth::user();
    $role = \App\Helpers\UserHelper::getUserRole($user);
    $isAdmin = $role['isAdmin'];
    $isDoctor = $role['isDoctor'];
    $route = ($isAdmin || $isDoctor) ? 'admin.' . $resource : 'user.' . $resource;
@endphp

{{ $route }}

