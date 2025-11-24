<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
                <h1 class="text-2xl font-bold text-gray-800">¡Bienvenido al Sistema de Control Académico SENA!</h1>
                <p class="mt-2 text-gray-600">Aquí podrá gestionar la asistencia, calificaciones y procesos disciplinarios de los aprendices.</p>
            </div>

            <!-- Alerts Section -->
            @if($atRiskStudents->count() > 0 || $consecutiveAbsencesStudents->count() > 0)
            <div class="mb-6">
                <h2 class="text-xl font-bold text-red-600 mb-4"><i class="bi bi-exclamation-octagon-fill"></i> Alertas Tempranas</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- 3+ Total Absences -->
                    @if($atRiskStudents->count() > 0)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 shadow">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-x-circle-fill text-red-500 text-2xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-red-800">Estudiantes con 3+ Fallas Totales</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($atRiskStudents as $student)
                                            <li>
                                                <strong>{{ $student->nombre }}</strong> ({{ $student->attendance_lists_count }} fallas)
                                                <a href="{{ route('students.disciplinary_actions.index', $student) }}" class="text-red-900 underline hover:text-red-600 ml-2">Ver Historial</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Consecutive Absences -->
                    @if($consecutiveAbsencesStudents->count() > 0)
                    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 shadow">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-triangle-fill text-orange-500 text-2xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-orange-800">Riesgo de Deserción (3 Fallas Consecutivas)</h3>
                                <div class="mt-2 text-sm text-orange-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($consecutiveAbsencesStudents as $student)
                                            <li>
                                                <strong>{{ $student->nombre }}</strong>
                                                <a href="{{ route('students.disciplinary_actions.index', $student) }}" class="text-orange-900 underline hover:text-orange-600 ml-2">Gestionar</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <!-- Attendance Chart -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 text-center">Asistencias (Mes Actual)</h3>
                    <canvas id="attendanceChart"></canvas>
                </div>

                <!-- Faults Chart -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 text-center">Faltas por Tipo</h3>
                    <canvas id="faultsChart"></canvas>
                </div>

                <!-- Plans Chart -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 text-center">Planes de Mejoramiento</h3>
                    <canvas id="plansChart"></canvas>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('attendance.index') }}" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out text-center group">
                    <div class="text-4xl text-blue-500 mb-2 group-hover:scale-110 transform transition"><i class="bi bi-calendar-check"></i></div>
                    <h3 class="font-bold text-gray-800">Tomar Asistencia</h3>
                </a>
                
                <a href="{{ route('students.index') }}" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out text-center group">
                    <div class="text-4xl text-green-500 mb-2 group-hover:scale-110 transform transition"><i class="bi bi-people"></i></div>
                    <h3 class="font-bold text-gray-800">Gestionar Aprendices</h3>
                </a>

                <a href="{{ route('disciplinary_actions.global_index') }}" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out text-center group">
                    <div class="text-4xl text-red-500 mb-2 group-hover:scale-110 transform transition"><i class="bi bi-exclamation-triangle"></i></div>
                    <h3 class="font-bold text-gray-800">Faltas Disciplinarias</h3>
                </a>

                <a href="{{ route('improvement_plans.index') }}" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out text-center group">
                    <div class="text-4xl text-orange-500 mb-2 group-hover:scale-110 transform transition"><i class="bi bi-clipboard-check"></i></div>
                    <h3 class="font-bold text-gray-800">Planes de Mejoramiento</h3>
                </a>
            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Attendance Chart (Pie)
            const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctxAttendance, {
                type: 'pie',
                data: {
                    labels: ['Asistió', 'Falla', 'Excusa', 'Retardo'],
                    datasets: [{
                        data: [
                            {{ $attendanceData['Asistió'] }}, 
                            {{ $attendanceData['Falla'] }}, 
                            {{ $attendanceData['Excusa'] }}, 
                            {{ $attendanceData['Retardo'] }}
                        ],
                        backgroundColor: ['#4ade80', '#ef4444', '#facc15', '#f97316'],
                    }]
                }
            });

            // 2. Faults Chart (Bar)
            const ctxFaults = document.getElementById('faultsChart').getContext('2d');
            new Chart(ctxFaults, {
                type: 'bar',
                data: {
                    labels: ['Académica', 'Disciplinaria'],
                    datasets: [{
                        label: 'Cantidad',
                        data: [
                            {{ $faultData['Académica'] }}, 
                            {{ $faultData['Disciplinaria'] }}
                        ],
                        backgroundColor: ['#3b82f6', '#ef4444'],
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });

            // 3. Plans Chart (Doughnut)
            const ctxPlans = document.getElementById('plansChart').getContext('2d');
            new Chart(ctxPlans, {
                type: 'doughnut',
                data: {
                    labels: ['Abiertos', 'Cerrados'],
                    datasets: [{
                        data: [
                            {{ $planData['Abiertos'] }}, 
                            {{ $planData['Cerrados'] }}
                        ],
                        backgroundColor: ['#facc15', '#22c55e'],
                    }]
                }
            });

        });
    </script>
</x-app-layout>
