@extends('layouts.app')

@section('title', 'Privacy Policy - SK Portal')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-gray-900">Privacy Policy</h1>
                <p class="text-sm text-gray-600 mt-2">Last updated: {{ now()->toFormattedDateString() }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:text-blue-700">Go back</a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 space-y-6">
            <p class="text-gray-700">
                This Privacy Policy explains how the SK Portal (the “Service”) collects, uses, and shares information.
                The Service supports SK operations such as member registration, event management, attendance tracking,
                announcements, and related automation.
            </p>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">1) Information We Collect</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li><span class="font-medium">Account data:</span> name, email, password (stored in hashed form).</li>
                    <li><span class="font-medium">Member profile data:</span> identifiers, contact details, demographic info, and related fields entered in the members module.</li>
                    <li><span class="font-medium">Attendance/event data:</span> event participation, timestamps, and attendance status.</li>
                    <li><span class="font-medium">Technical data:</span> basic logs necessary for security and troubleshooting.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">2) How We Use Information</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>To provide and operate the Service (authentication, member records, events, attendance).</li>
                    <li>To generate reports/analytics (e.g., attendance rate).</li>
                    <li>To send operational emails (e.g., account credentials, certificates), when enabled.</li>
                    <li>To maintain security, prevent abuse, and troubleshoot issues.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">3) Sharing and Third Parties</h2>
                <p class="text-gray-700">
                    The Service may integrate with third-party tools for automation and communications. For example,
                    email sending may be triggered via webhook-based automation. Only the data needed to perform the
                    requested action is shared.
                </p>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">4) Data Retention</h2>
                <p class="text-gray-700">
                    We retain information for as long as needed for SK operational purposes and legitimate recordkeeping,
                    unless a longer retention period is required by policy or applicable rules.
                </p>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">5) Security</h2>
                <p class="text-gray-700">
                    We use reasonable safeguards to protect information. Passwords are stored using hashing.
                    No method of transmission or storage is 100% secure.
                </p>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">6) Your Choices</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>You may request correction of inaccurate member data via an administrator.</li>
                    <li>Users should change temporary passwords after first login.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">7) Contact</h2>
                <p class="text-gray-700">
                    For privacy-related questions, please contact your SK Portal administrator.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
