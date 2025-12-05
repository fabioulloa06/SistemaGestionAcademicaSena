@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Header con saludo -->
<div class="mb-8 fade-in-up">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">
        Bienvenido, {{ Auth::user()->name }}! 👋
    </h1>
    <p class="text-gray-600">
        Sistema de Gestión Académica SENA - Panel de control
    </p>
</div>

<!-- Cards de Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in-up" style="animation-delay: 0.1s;">
    <!-- Card 1: Total Aprendices -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Aprendices</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalStudents ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Activos en el sistema</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 2: Fichas Activas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Fichas Activas</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(count($groups ?? [])) }}</p>
                <p class="text-xs text-gray-500 mt-1">En proceso de formación</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 3: Planes Abiertos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Planes Abiertos</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($planesAbiertos ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">Requieren seguimiento</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Faltas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Faltas</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(($faltasAcademicas ?? 0) + ($faltasDisciplinarias ?? 0)) }}</p>
                <p class="text-xs text-gray-500 mt-1">Este mes</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Acciones Rápidas -->
<div class="rounded-xl shadow-lg p-6 mb-6 text-white" style="background: linear-gradient(135deg, #4d8e37 0%, #3d7230 100%);">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold mb-2">Acciones Rápidas</h3>
            <p class="text-white/90 text-sm">Gestiona el sistema de forma rápida</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('attendance-lists.create') }}" class="px-6 py-3 bg-white rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105" style="color: #4d8e37;">
                📝 Registrar Inasistencias
            </a>
            <a href="{{ route('students.index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/20 transition-all duration-200 border border-white/20">
                👥 Ver Aprendices
            </a>
            <a href="{{ route('disciplinary-actions.global-index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/20 transition-all duration-200 border border-white/20">
                ⚠️ Ver Faltas
            </a>
            <a href="{{ route('improvement-plans.index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-white/20 transition-all duration-200 border border-white/20">
                📋 Planes de Mejoramiento
            </a>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Gráfico de Inasistencias -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Inasistencias del Mes</h3>
            <div class="w-10 h-10 bg-sena-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-sena-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div class="h-64">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <!-- Gráfico de Faltas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Tipos de Faltas</h3>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
        <div class="h-64">
            <canvas id="faltasChart"></canvas>
        </div>
    </div>
</div>

<!-- Gráfico de Planes -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Estado de Planes de Mejoramiento</h3>
        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    </div>
    <div class="h-64">
        <canvas id="planesChart"></canvas>
    </div>
</div>

<!-- Información del Usuario -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Card de Perfil -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Información del Perfil
        </h3>
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg" style="background: linear-gradient(135deg, #fc7323 0%, #e8651f 100%);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-medium text-white" style="background-color: #4d8e37;">
                        {{ ucfirst(str_replace('_', ' ', Auth::user()->rol ?? 'Usuario')) }}
                    </span>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Estado</p>
                        <p class="font-medium text-gray-900 mt-1">{{ Auth::user()->estado ?? 'Activo' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Último acceso</p>
                        <p class="font-medium text-gray-900 mt-1">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Resumen -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Resumen del Mes
        </h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Inasistencias</p>
                        <p class="text-xs text-gray-500">Este mes</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ number_format($totalInasistencias ?? 0) }}</p>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Fallas</p>
                        <p class="text-xs text-gray-500">Este mes</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ number_format($totalFallas ?? 0) }}</p>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Planes Abiertos</p>
                        <p class="text-xs text-gray-500">Requieren atención</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ number_format($planesAbiertos ?? 0) }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Colores SENA
    const senaColors = {
        sena: ['#4d8e37', '#3d7230', '#325b28'],
        orange: ['#fc7323', '#e8651f', '#d45a1b'],
        blue: ['#3b82f6', '#2563eb', '#1d4ed8'],
        green: ['#10b981', '#059669', '#047857'],
        red: ['#ef4444', '#dc2626', '#b91c1c'],
        yellow: ['#f59e0b', '#d97706', '#b45309'],
    };

    // Gráfico de Inasistencias
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx && typeof Chart !== 'undefined') {
        new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($attendanceChartData['labels'] ?? []) !!},
                datasets: [{
                    label: 'Cantidad',
                    data: {!! json_encode($attendanceChartData['data'] ?? []) !!},
                    backgroundColor: [
                        'rgba(77, 142, 55, 0.8)',
                        'rgba(252, 115, 35, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    borderColor: [
                        'rgba(77, 142, 55, 1)',
                        'rgba(252, 115, 35, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Faltas
    const faltasCtx = document.getElementById('faltasChart');
    if (faltasCtx && typeof Chart !== 'undefined') {
        new Chart(faltasCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($faltasChartData['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($faltasChartData['data'] ?? []) !!},
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(252, 115, 35, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                    ],
                    borderColor: [
                        'rgba(239, 68, 68, 1)',
                        'rgba(252, 115, 35, 1)',
                        'rgba(245, 158, 11, 1)',
                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                    }
                }
            }
        });
    }

    // Gráfico de Planes
    const planesCtx = document.getElementById('planesChart');
    if (planesCtx && typeof Chart !== 'undefined') {
        new Chart(planesCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($planesChartData['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($planesChartData['data'] ?? []) !!},
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                    ],
                    borderColor: [
                        'rgba(245, 158, 11, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(156, 163, 175, 1)',
                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
