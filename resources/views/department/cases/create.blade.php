@extends('layouts.officer')

@section('title', 'Record Student Case - Department')
@section('page-title', 'Record Student Case')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="surface-card p-6 shadow-xl">
        <p class="text-xs uppercase tracking-[0.28em] text-[#6BCFCB]">New case record</p>
        <h2 class="mt-3 text-3xl font-bold text-[#001722]">Record a student case</h2>
        <p class="mt-2 text-sm text-[#627f7c]">
            Enter the student's ID to look them up, then describe the issue (e.g. borrowed book, unpaid fee).
        </p>
    </div>

    <div class="surface-card p-6 shadow-lg">
        <form action="{{ route('department.cases.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="student_id" class="block text-sm font-medium text-[#001722] mb-2">Student ID <span class="text-[#FE580B]">*</span></label>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}" required placeholder="e.g. SAL/2024/001" class="form-input flex-1">
                    <button type="button" id="lookupBtn" class="btn-secondary px-5 py-3 text-sm whitespace-nowrap">Look up student</button>
                </div>
                @error('student_id')
                    <p class="mt-2 text-sm text-[#B52B2B]">{{ $message }}</p>
                @enderror
                <div id="studentPreview" class="mt-4 hidden rounded-[20px] border border-[#084A48]/10 bg-[#F5FFFE] p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#084A48]">Student found</p>
                    <p id="previewName" class="mt-2 font-semibold text-[#001722]"></p>
                    <p id="previewMeta" class="mt-1 text-sm text-[#627f7c]"></p>
                    <div id="previewCases" class="mt-4 hidden">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#FE580B]">Existing open cases</p>
                        <ul id="previewCasesList" class="mt-2 space-y-2 text-sm text-[#7f3f08]"></ul>
                    </div>
                </div>
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-[#001722] mb-2">Case title <span class="text-[#FE580B]">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Borrowed: Database Systems textbook" class="form-input w-full">
                @error('title')
                    <p class="mt-2 text-sm text-[#B52B2B]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#001722] mb-2">Details (optional)</label>
                <textarea id="description" name="description" rows="4" placeholder="Additional details about the case..." class="form-input w-full">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-[#B52B2B]">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                <a href="{{ route('department.cases.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#084A48]/10 px-5 py-3 text-sm font-semibold text-[#084A48] hover:bg-[#F5FFFE] transition">Cancel</a>
                <button type="submit" class="btn-primary px-6 py-3 text-sm">Save case record</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('lookupBtn').addEventListener('click', function () {
        const studentId = document.getElementById('student_id').value.trim();
        const preview = document.getElementById('studentPreview');
        const btn = this;

        if (!studentId) {
            preview.classList.add('hidden');
            alert('Please enter a student ID first.');
            return;
        }

        // Disable button and show loading state
        btn.disabled = true;
        btn.textContent = 'Looking up...';

        fetch(`{{ route('department.cases.lookup') }}?student_id=${encodeURIComponent(studentId)}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.found) {
                preview.classList.remove('hidden');
                document.getElementById('previewName').textContent = data.message || 'No student found with that ID.';
                document.getElementById('previewMeta').textContent = '';
                document.getElementById('previewCases').classList.add('hidden');
                return;
            }

            preview.classList.remove('hidden');
            document.getElementById('previewName').textContent = data.student.full_name;
            document.getElementById('previewMeta').textContent = `${data.student.student_id} · ${data.student.faculty} · ${data.student.department}`;

            const casesBox = document.getElementById('previewCases');
            const casesList = document.getElementById('previewCasesList');

            if (data.has_open_cases) {
                casesBox.classList.remove('hidden');
                casesList.innerHTML = data.open_cases.map(c => `<li class="rounded-xl bg-[#FFF5EA] px-3 py-2">${c.title}</li>`).join('');
            } else {
                casesBox.classList.add('hidden');
                casesList.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Lookup error:', error);
            preview.classList.remove('hidden');
            document.getElementById('previewName').textContent = 'Error looking up student.';
            document.getElementById('previewMeta').textContent = 'Please try again or contact support.';
            document.getElementById('previewCases').classList.add('hidden');
        })
        .finally(() => {
            // Re-enable button
            btn.disabled = false;
            btn.textContent = 'Look up student';
        });
    });
</script>
@endpush
@endsection
