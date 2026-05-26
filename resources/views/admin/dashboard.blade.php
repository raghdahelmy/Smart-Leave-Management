@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="text-muted small">Employees</div>
                        <div class="fw-bold fs-5">{{ $stats['total_employees'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="fw-bold fs-5">{{ $stats['pending_leaves'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="text-muted small">Approved</div>
                        <div class="fw-bold fs-5">{{ $stats['approved_leaves'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="text-muted small">Rejected</div>
                        <div class="fw-bold fs-5">{{ $stats['rejected_leaves'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-info bg-opacity-10 text-info"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="text-muted small">Departments</div>
                        <div class="fw-bold fs-5">{{ $stats['total_departments'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-chat-dots-fill"></i></div>
                    <div>
                        <div class="text-muted small">Open Tickets</div>
                        <div class="fw-bold fs-5">{{ $stats['open_complaints'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytics Charts --}}
    <div class="row g-4 mb-4">
        {{-- Leaves by Type - Doughnut --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Leaves by Type</h6></div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="leavesByTypeChart" style="max-height:260px"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Leaves - Bar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Monthly Leaves ({{ now()->year }})</h6></div>
                <div class="card-body">
                    <canvas id="monthlyLeavesChart" style="max-height:260px"></canvas>
                </div>
            </div>
        </div>

        {{-- Leaves by Department - Bar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><h6 class="mb-0 fw-semibold">Leaves by Department</h6></div>
                <div class="card-body">
                    <canvas id="leavesByDeptChart" style="max-height:260px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent Leave Requests --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Leave Requests</h6>
                    <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->user->name }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $leave->leaveType->code }}</span></td>
                                    <td class="small">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</td>
                                    <td><span class="badge badge-{{ $leave->status }}">{{ ucfirst($leave->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No leave requests yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Complaints --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Recent Complaints</h6>
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Subject</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentComplaints as $complaint)
                                <tr>
                                    <td>{{ $complaint->user->name }}</td>
                                    <td class="small">{{ Str::limit($complaint->subject, 25) }}</td>
                                    <td><span class="badge badge-{{ str_replace('_', '-', $complaint->status) }}">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">No complaints yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const colors = ['#4361ee','#f72585','#4cc9f0','#7209b7','#f77f00','#06d6a0','#ef476f','#118ab2','#ffd166','#073b4c','#8338ec','#3a86ff','#fb5607'];

    // Leaves by Type - Doughnut
    const typeData = @json($leavesByType);
    if (typeData.length > 0) {
        new Chart(document.getElementById('leavesByTypeChart'), {
            type: 'doughnut',
            data: {
                labels: typeData.map(d => d.label),
                datasets: [{
                    data: typeData.map(d => d.value),
                    backgroundColor: colors.slice(0, typeData.length),
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
    }

    // Monthly Leaves - Bar
    const monthlyData = @json($monthlyLeaves);
    const monthNames = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    new Chart(document.getElementById('monthlyLeavesChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(d => monthNames[d.month]),
            datasets: [
                { label: 'Approved', data: monthlyData.map(d => d.approved), backgroundColor: '#06d6a0' },
                { label: 'Rejected', data: monthlyData.map(d => d.rejected), backgroundColor: '#ef476f' },
                { label: 'Pending',  data: monthlyData.map(d => d.pending),  backgroundColor: '#ffd166' },
            ]
        },
        options: {
            responsive: true,
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
        }
    });

    // Leaves by Department - Horizontal Bar
    const deptData = @json($leavesByDept);
    new Chart(document.getElementById('leavesByDeptChart'), {
        type: 'bar',
        data: {
            labels: deptData.map(d => d.label),
            datasets: [{
                label: 'Leaves',
                data: deptData.map(d => d.value),
                backgroundColor: '#4361ee',
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush
