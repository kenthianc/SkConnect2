<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use App\Mail\MemberPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index(Request $request)
    {
        $query = Member::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->byRole($request->role);
        }

        // Filter by purok
        if ($request->filled('purok')) {
            $query->byPurok($request->purok);
        }

        $members = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query()); // <-- keep filters on pagination

        // For the purok dropdown
        $puroks = Member::select('purok')
            ->distinct()
            ->orderBy('purok')
            ->pluck('purok');

        return view('members.index', compact('members', 'puroks'));
    }


    /**
     * Store a newly created member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:15|max:30',
            'gender' => 'required|in:Male,Female,Other',
            'purok' => 'required|string',
            'address' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
        ]);

        // Generate a random password
        $randomPassword = Str::random(8); // Generates an 8-character password

        $member = Member::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        ]);

        // Optionally, send the password to the member via email
        Mail::to($member->email)->send(new MemberPasswordMail($randomPassword));

    return response()->json(['message' => 'Member added successfully', 'member' => $member]);

        $validated['member_id'] = Member::generateMemberId();
        $validated['date_joined'] = now();
        $validated['registered_via'] = 'Online';

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member added successfully!');
    }

    /**
     * Display the specified member
     */
    public function show(Member $member)
    {
        $member->load('attendance.event');
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date',
            'age' => 'required|integer|min:15|max:30',
            'gender' => 'required|in:Male,Female,Other',
            'purok' => 'required|string',
            'address' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
            'role' => 'nullable|in:Member,Officer',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified member
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully!');
    }

    /**
     * Export members to Excel
     */
    public function export()
    {
        return Excel::download(new MembersExport, 'sk-members-' . date('Y-m-d') . '.xlsx');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
