@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Header con saludo -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Bienvenido, {{ Auth::user()->name }}! 👋
    </h1>
    <p class="text-gray-600">
        Sistema de Gestión Académica SENA - Panel de control
    </p>
</div>

<!-- Cards de Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Aprendices</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">Activos en el sistema</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Fichas Activas</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">En proceso de formación</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pendientes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">Actividades por revisar</p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Alertas</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">Requieren atención</p>
            </div>
            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Acciones Rápidas -->
@if(Auth::user()->rol === 'instructor_lider' || Auth::user()->rol === 'coordinador')
<div class="rounded-xl shadow-lg p-6 mb-6 text-white" style="background: linear-gradient(135deg, #238276 0%, #1a6b60 100%);">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold mb-2">Acciones Rápidas</h3>
            <p class="text-white/90 text-sm">Gestiona aprendices y fichas de forma rápida</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('aprendices.create') }}" class="px-6 py-3 bg-white rounded-lg font-semibold transition-colors shadow-md" style="color: #238276;" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='white'">
                + Registrar Aprendiz
            </a>
            <a href="{{ route('aprendices.index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/20 transition-colors border border-white/20">
                Ver Aprendices
            </a>
        </div>
    </div>
</div>
@endif

<!-- Información del Usuario -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Card de Perfil -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Información del Perfil
        </h3>
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold" style="background: linear-gradient(135deg, #fc7323 0%, #e8651f 100%);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-medium text-white" style="background-color: #238276;">
                        {{ ucfirst(str_replace('_', ' ', Auth::user()->rol)) }}
                    </span>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Estado</p>
                        <p class="font-medium text-gray-900 mt-1">Activo</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Último acceso</p>
                        <p class="font-medium text-gray-900 mt-1">Hoy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Actividad Reciente -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Actividad Reciente
        </h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Sesión iniciada</p>
                    <p class="text-xs text-gray-500">Hace unos momentos</p>
                </div>
            </div>
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">No hay actividad reciente</p>
            </div>
        </div>
    </div>
</div>
@endsection
