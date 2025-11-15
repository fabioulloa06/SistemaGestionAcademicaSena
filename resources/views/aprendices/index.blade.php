@extends('layouts.app')

@section('title', 'Gestión de Aprendices')
@section('page-title', 'Gestión de Aprendices')

@section('content')
<!-- Header con acciones -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
            Gestión de Aprendices
        </h1>
        <p class="text-gray-600">
            Administra y gestiona los aprendices del sistema
        </p>
    </div>
    <a href="{{ route('aprendices.create') }}" 
       class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-md transition-all transform hover:scale-105"
       style="background-color: #fc7323;"
       onmouseover="this.style.backgroundColor='#e8651f'"
       onmouseout="this.style.backgroundColor='#fc7323'">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Registrar Nuevo Aprendiz
    </a>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if ($aprendices->count() > 0)
    <!-- Tabla de aprendices -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Nombre Completo
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Documento
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Fecha de Registro
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($aprendices as $aprendiz)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold mr-3" style="background: linear-gradient(135deg, #fc7323 0%, #e8651f 100%);">
                                        {{ substr($aprendiz->nombres ?? $aprendiz->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $aprendiz->nombre_completo ?? $aprendiz->name ?? ($aprendiz->nombres . ' ' . $aprendiz->apellidos) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $aprendiz->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    {{ ($aprendiz->tipo_documento ?? 'CC') . ' ' . ($aprendiz->numero_documento ?? 'N/A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estado = $aprendiz->estado ?? 'activo';
                                    $estadoClases = match($estado) {
                                        'activo' => 'bg-green-100 text-green-800',
                                        'inactivo' => 'bg-gray-100 text-gray-800',
                                        'suspendido' => 'bg-yellow-100 text-yellow-800',
                                        'cancelado' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoClases }}">
                                    {{ ucfirst($estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $aprendiz->fecha_creacion ? \Carbon\Carbon::parse($aprendiz->fecha_creacion)->format('d/m/Y') : ($aprendiz->created_at->format('d/m/Y') ?? 'N/A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="mr-4 hover:opacity-80 transition-opacity" style="color: #238276;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($aprendices->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $aprendices->links() }}
            </div>
        @endif
    </div>
@else
    <!-- Estado vacío -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay aprendices registrados</h3>
            <p class="text-gray-600 mb-6">Comienza registrando el primer aprendiz en el sistema.</p>
            <a href="{{ route('aprendices.create') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-md transition-all"
               style="background-color: #fc7323;"
               onmouseover="this.style.backgroundColor='#e8651f'"
               onmouseout="this.style.backgroundColor='#fc7323'">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Registrar Primer Aprendiz
            </a>
        </div>
    </div>
@endif
@endsection
