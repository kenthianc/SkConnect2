<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User; // <-- added
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- added
use Illuminate\Support\Facades\Hash; // <-- added
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use App\Models\Attendance;

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
            'first_name'       => 'required|string|max:255',
            'middle_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:members,email|unique:users,email', // <-- also unique in users
            'phone'            => 'required|string|max:20',
            'birthdate'        => 'required|date',
            'age'              => 'required|integer|min:15|max:30',
            'gender'           => 'required|in:Male,Female,Other',
            'purok'            => 'required|string',
            'address'          => 'required|string',
            'guardian_name'    => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
        ]);

        $validated['member_id']      = Member::generateMemberId();
        $validated['date_joined']    = now();
        $validated['registered_via'] = 'Online';

        // 1. Create the member
        $member = Member::create($validated);

        // 2. Generate a random password (plain text)
        // Example: SK-4F8ZQ92A
        $plainPassword = 'SK-' . Str::upper(Str::random(8));

        // 3. Create the corresponding user account
        // If you later add a member_id column to users table,
        // you can include 'member_id' => $member->id here.
        User::create([
            'name'     => $member->first_name . ' ' . $member->last_name,
            'email'    => $member->email,
            'password' => Hash::make($plainPassword),
        ]);

        // 4. Redirect with success + credentials (shown once in the view)
        return redirect()->route('members.index')
            ->with('success', 'Member added successfully and account generated automatically!')
            ->with('generated_email', $member->email)
            ->with('generated_password', $plainPassword);
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
            'first_name'       => 'required|string|max:255',
            'middle_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:members,email,' . $member->id . '|unique:users,email,' . $member->id,
            'phone'            => 'required|string|max:20',
            'birthdate'        => 'required|date',
            'age'              => 'required|integer|min:15|max:30',
            'gender'           => 'required|in:Male,Female,Other',
            'purok'            => 'required|string',
            'address'          => 'required|string',
            'guardian_name'    => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
            'role'             => 'nullable|in:Member,Officer',
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
