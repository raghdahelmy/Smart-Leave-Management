@extends('layouts.app')
@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
    <div class="mb-3">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="action" class="form-control form-control-sm" placeholder="Search action..." value="{{ request('action') }}" style="width:250px">
            <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('admin.activity-logs') }}" class="btn btn-sm btn-light">Reset</a>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td class="fw-semibold">{{ $log->user->name ?? 'System' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>
                                @if($log->model)
                                    <span class="badge bg-light text-dark">{{ $log->model }}{{ $log->model_id ? ' #'.$log->model_id : '' }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $log->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No activity logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $logs->links() }}</div>
@endsection
