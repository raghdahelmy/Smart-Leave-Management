<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // ─── Admin: List all complaints ──────────────────────────
    public function adminIndex(Request $request)
    {
        $query = Complaint::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();
        $categories = Complaint::CATEGORIES;

        return view('admin.complaints.index', compact('complaints', 'categories'));
    }

    public function adminShow(Complaint $complaint)
    {
        $complaint->load('user');
        return view('admin.complaints.show', compact('complaint'));
    }

    public function adminUpdate(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status'      => 'required|in:open,in_progress,resolved',
            'admin_reply' => 'nullable|string|max:2000',
        ]);

        $complaint->update([
            'status'      => $request->status,
            'admin_reply' => $request->admin_reply,
        ]);

        Notification::create([
            'user_id' => $complaint->user_id,
            'title'   => 'Complaint Updated',
            'message' => 'Your complaint "' . $complaint->subject . '" status has been updated to ' . str_replace('_', ' ', $request->status) . '.',
        ]);

        return redirect()->route('admin.complaints.index')->with('success', 'Complaint updated successfully.');
    }

    // ─── Employee: My complaints ─────────────────────────────
    public function employeeIndex()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        $categories = Complaint::CATEGORIES;

        return view('employee.complaints.index', compact('complaints', 'categories'));
    }

    public function employeeCreate()
    {
        $categories = Complaint::CATEGORIES;
        return view('employee.complaints.create', compact('categories'));
    }

    public function employeeStore(Request $request)
    {
        $request->validate([
            'category'    => 'required|string|in:' . implode(',', array_keys(Complaint::CATEGORIES)),
            'subject'     => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        Complaint::create([
            'user_id'     => Auth::id(),
            'category'    => $request->category,
            'subject'     => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->route('employee.complaints.index')->with('success', 'Complaint submitted successfully.');
    }

    public function employeeShow(Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.complaints.show', compact('complaint'));
    }
}
