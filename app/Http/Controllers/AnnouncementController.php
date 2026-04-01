<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'type'       => 'required|in:general,meeting,urgent',
            'meeting_date' => 'nullable|date',
            'expired_at' => 'nullable|date|after:now',
        ]);

        Announcement::create($request->only('title', 'content', 'type', 'meeting_date', 'expired_at', 'is_active'));

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'type'       => 'required|in:general,meeting,urgent',
            'meeting_date' => 'nullable|date',
            'expired_at' => 'nullable|date',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->only('title', 'content', 'type', 'meeting_date', 'expired_at', 'is_active'));

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete(); // soft delete → ke trash
        return redirect()->route('announcements.index')->with('success', 'Pengumuman dipindahkan ke sampah.');
    }

    public function trash()
    {
        $trashed = Announcement::onlyTrashed()->latest('deleted_at')->get();
        return view('admin.announcements.trash', compact('trashed'));
    }

    public function restore($id)
    {
        Announcement::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('announcements.trash')->with('success', 'Pengumuman berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Announcement::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('announcements.trash')->with('success', 'Pengumuman dihapus permanen.');
    }
}
