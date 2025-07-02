@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
@endsection

@section('content')
    <div class="alert alert-info">
        Bem-vindo de volta, <strong>{{ Auth::user()->name }}</strong>! Aqui está um resumo das suas atividades.
    </div>

    {{-- GRÁFICO DE TAREFAS --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header fs-5"><i class="bi bi-pie-chart-fill me-2"></i>Resumo de Tarefas por Status</div>
                <div class="card-body">
                    {{-- O gráfico será renderizado neste canvas --}}
                    <canvas id="tasksChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fs-5"><i class="bi bi-kanban me-2"></i>Meus Projetos</div>
                <div class="list-group list-group-flush">
                    @forelse ($projects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $project->title }}</h5>
                                <small class="text-muted">Criado por: {{ $project->creator->name }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $project->tasks()->count() }} {{ Str::plural('tarefa', $project->tasks()->count()) }}</span>
                        </a>
                    @empty
                        <div class="list-group-item text-muted">Você ainda não faz parte de nenhum projeto.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fs-5"><i class="bi bi-check2-square me-2"></i>Minhas Tarefas Recentes</div>
                <ul class="list-group list-group-flush">
                    @forelse ($tasks as $task)
                        <li class="list-group-item">
                            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Projeto: {{ $task->project->title }}</small>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'in_progress' => 'info',
                                        'completed' => 'success',
                                        'expired' => 'danger'
                                    ][$task->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{$statusClass}}">{{ str_replace('_', ' ', $task->status) }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Nenhuma tarefa atribuída a você.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

{{-- SCRIPT PARA O GRÁFICO --}}
{{-- Adicione esta seção no final do arquivo, ou junto com outros scripts se preferir --}}
@push('scripts')
{{-- Inclui a biblioteca Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('tasksChart');
    
    // Converte os dados do PHP para JavaScript
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    // Mapeia os status para cores para deixar o gráfico mais bonito
    const backgroundColors = chartLabels.map(label => {
        switch(label) {
            case 'pending': return 'rgba(255, 193, 7, 0.7)'; // Amarelo
            case 'in_progress': return 'rgba(13, 202, 240, 0.7)'; // Azul claro
            case 'completed': return 'rgba(25, 135, 84, 0.7)'; // Verde
            case 'expired': return 'rgba(220, 53, 69, 0.7)'; // Vermelho
            default: return 'rgba(108, 117, 125, 0.7)'; // Cinza
        }
    });

    new Chart(ctx, {
      type: 'doughnut', // Tipo do gráfico (pode ser 'bar', 'pie', 'line', etc.)
      data: {
        labels: chartLabels.map(label => label.replace('_', ' ')), // Formata os nomes
        datasets: [{
          label: '# de Tarefas',
          data: chartData,
          backgroundColor: backgroundColors,
          borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Distribuição de Tarefas por Status'
            }
        }
      }
    });
  });
</script>
@endpush
@endsection