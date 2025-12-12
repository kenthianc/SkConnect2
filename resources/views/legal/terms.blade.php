@extends('layouts.app')

@section('title', 'Terms and Conditions - SK Portal')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-gray-900">Terms and Conditions</h1>
                <p class="text-sm text-gray-600 mt-2">Last updated: {{ now()->toFormattedDateString() }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:text-blue-700">Go back</a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8 space-y-6">
            <p class="text-gray-700">
                These Terms and Conditions govern your access to and use of the SK Portal (the “Service”),
                including the admin dashboard and related features such as member management, events,
                attendance tracking, announcements, and profile access.
            </p>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">1) Eligibility and Accounts</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>You must provide accurate information when creating or using an account.</li>
                    <li>Accounts generated for members may use temporary passwords which should be changed after first login.</li>
                    <li>You are responsible for maintaining the confidentiality of your credentials.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">2) Acceptable Use</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Do not misuse the Service, attempt unauthorized access, or interfere with normal operation.</li>
                    <li>Do not upload or transmit malicious code, or attempt to exploit vulnerabilities.</li>
                    <li>Use member data only for legitimate SK organizational purposes.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">3) Member and Attendance Records</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>The Service stores member profiles and attendance records to support SK operations.</li>
                    <li>Attendance rates and related analytics are computed from recorded event and attendance data.</li>
                    <li>Administrators are responsible for ensuring data entered is accurate and authorized.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">4) Email and Notifications</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>The Service may send emails (e.g., account credentials, certificates) using automated workflows.</li>
                    <li>Third-party tools may be used to deliver emails (for example, automation/webhooks).</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">5) Availability and Changes</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>The Service may be updated, modified, or temporarily unavailable for maintenance.</li>
                    <li>Features may change over time as the system evolves.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">6) Disclaimer</h2>
                <p class="text-gray-700">
                    The Service is provided “as is” without warranties of any kind. Administrators should validate outputs
                    and ensure compliance with applicable policies and regulations.
                </p>
            </div>

            <div class="space-y-2">
                <h2 class="text-gray-900 text-lg">7) Contact</h2>
                <p class="text-gray-700">
                    For support or questions about these Terms, please contact your SK Portal administrator.
                </p>
            </div>
        </div>

        <div class="mt-6 text-sm text-gray-500">
            By using the Service, you agree to these Terms.
        </div>
    </div>
</div>
@endsection
