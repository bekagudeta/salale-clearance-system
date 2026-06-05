<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportService
{
    public const MAX_ROWS = 2000;

    public function __construct(
        protected StudentProvisioningService $provisioning
    ) {}

    /**
     * @return array{rows: list<array<string, mixed>>, summary: array{total: int, valid: int, invalid: int}}
     */
    public function parseFile(UploadedFile $file): array
    {
        $sheet = Excel::toArray(null, $file);
        $rawRows = $sheet[0] ?? [];

        if (count($rawRows) < 2) {
            return [
                'rows' => [],
                'summary' => ['total' => 0, 'valid' => 0, 'invalid' => 0],
                'error' => 'The file must include a header row and at least one student row.',
            ];
        }

        $headers = $this->normalizeHeaders(array_shift($rawRows));
        $parsedRows = [];
        $seenStudentIds = [];
        $seenEmails = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach ($rawRows as $index => $row) {
            $rowNumber = $index + 2;
            $assoc = $this->mapRowToFields($headers, $row);

            if ($this->rowIsEmpty($assoc)) {
                continue;
            }

            $normalized = $this->normalizeRow($assoc);
            $errors = $this->validateRow($normalized, $seenStudentIds, $seenEmails);

            if ($errors === []) {
                $seenStudentIds[] = strtoupper($normalized['student_id']);
                $seenEmails[] = strtolower($normalized['email']);
                $validCount++;
                $status = 'valid';
            } else {
                $invalidCount++;
                $status = 'invalid';
            }

            $parsedRows[] = [
                'row_number' => $rowNumber,
                'data' => $normalized,
                'status' => $status,
                'errors' => $errors,
            ];
        }

        if (count($parsedRows) > self::MAX_ROWS) {
            return [
                'rows' => [],
                'summary' => ['total' => 0, 'valid' => 0, 'invalid' => 0],
                'error' => 'Import is limited to ' . self::MAX_ROWS . ' students per file. Split your file and try again.',
            ];
        }

        return [
            'rows' => $parsedRows,
            'summary' => [
                'total' => count($parsedRows),
                'valid' => $validCount,
                'invalid' => $invalidCount,
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array{imported: int, skipped: int, failed: list<array{row_number: int, message: string}>}
     */
    public function importRows(array $rows): array
    {
        $imported = 0;
        $skipped = 0;
        $failed = [];

        foreach ($rows as $entry) {
            if (($entry['status'] ?? '') !== 'valid') {
                $skipped++;
                continue;
            }

            $data = $entry['data'];

            try {
                $this->provisioning->createStudent([
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'student_id' => $data['student_id'],
                    'faculty' => $data['faculty'],
                    'department' => $data['department'],
                    'year' => $data['year'],
                    'semester' => $data['semester'],
                    'phone' => $data['phone'] ?? null,
                    'gender' => $data['gender'] ?? null,
                ]);
                $imported++;
            } catch (\Throwable $e) {
                $failed[] = [
                    'row_number' => $entry['row_number'],
                    'message' => $e->getMessage(),
                ];
            }
        }

        return compact('imported', 'skipped', 'failed');
    }

    public function defaultPasswordForStudentId(string $studentId): string
    {
        $sanitized = preg_replace('/[^A-Za-z0-9]/', '', $studentId) ?: 'student';
        $password = 'Salale@' . $sanitized;

        return strlen($password) >= 8 ? $password : str_pad($password, 8, '0');
    }

    /**
     * @param  list<mixed>  $headers
     * @return list<string>
     */
    protected function normalizeHeaders(array $headers): array
    {
        return array_map(function ($header) {
            $label = trim((string) $header);

            return Str::slug($label, '_');
        }, $headers);
    }

    /**
     * @param  list<string>  $headers
     * @param  list<mixed>  $row
     * @return array<string, mixed>
     */
    protected function mapRowToFields(array $headers, array $row): array
    {
        $mapped = [];

        foreach ($headers as $index => $key) {
            if ($key === '') {
                continue;
            }
            $mapped[$key] = $row[$index] ?? null;
        }

        return $mapped;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    protected function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function normalizeRow(array $row): array
    {
        $studentId = $this->pick($row, ['student_id', 'id']);
        $fullName = $this->pick($row, ['full_name', 'name']);
        $email = $this->pick($row, ['email', 'email_address']);
        $password = $this->pick($row, ['password']);
        $faculty = $this->pick($row, ['faculty']);
        $department = $this->pick($row, ['department']);
        $year = $this->pick($row, ['year', 'year_of_study']);
        $semester = $this->normalizeSemester($this->pick($row, ['semester']));
        $phone = $this->pick($row, ['phone', 'phone_number']);
        $gender = $this->normalizeGender($this->pick($row, ['gender']));

        $studentId = strtoupper(trim((string) $studentId));

        if ($password === null || trim((string) $password) === '') {
            $password = $this->defaultPasswordForStudentId($studentId);
        }

        return [
            'student_id' => $studentId,
            'full_name' => trim((string) $fullName),
            'email' => strtolower(trim((string) $email)),
            'password' => (string) $password,
            'faculty' => trim((string) $faculty),
            'department' => trim((string) $department),
            'year' => is_numeric($year) ? (int) $year : trim((string) $year),
            'semester' => $semester,
            'phone' => $phone !== null && trim((string) $phone) !== '' ? trim((string) $phone) : null,
            'gender' => $gender,
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    protected function pick(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && trim((string) $row[$key]) !== '') {
                return $row[$key];
            }
        }

        return null;
    }

    protected function normalizeSemester(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            '1', 'first', 'semester 1', 'semester_1' => 'First',
            '2', 'second', 'semester 2', 'semester_2' => 'Second',
            '3', 'summer', 'semester 3' => 'Summer',
            default => ucfirst($normalized),
        };
    }

    protected function normalizeGender(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return strtolower(trim((string) $value));
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $seenStudentIds
     * @param  list<string>  $seenEmails
     * @return list<string>
     */
    protected function validateRow(array $data, array $seenStudentIds, array $seenEmails): array
    {
        $validator = Validator::make($data, [
            'student_id' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'faculty' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:6',
            'semester' => 'required|string|in:First,Second,Summer',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
        ], [
            'student_id.required' => 'Student ID is required.',
            'full_name.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be valid.',
            'password.min' => 'Password must be at least 8 characters (leave blank to use default).',
            'faculty.required' => 'Faculty is required.',
            'department.required' => 'Department is required.',
            'year.required' => 'Year is required.',
            'year.integer' => 'Year must be a number between 1 and 6.',
            'semester.in' => 'Semester must be First, Second, or Summer.',
            'gender.in' => 'Gender must be male, female, or other.',
        ]);

        $errors = $validator->fails() ? $validator->errors()->all() : [];

        if ($data['student_id'] && ! preg_match('/^[A-Z0-9\/\-]+$/i', $data['student_id'])) {
            $errors[] = 'Student ID should contain only letters, numbers, slashes, or hyphens.';
        }

        if ($data['student_id'] && in_array(strtoupper($data['student_id']), $seenStudentIds, true)) {
            $errors[] = 'Duplicate Student ID in this file.';
        }

        if ($data['email'] && in_array(strtolower($data['email']), $seenEmails, true)) {
            $errors[] = 'Duplicate email in this file.';
        }

        if ($data['student_id'] && Student::where('student_id', $data['student_id'])->exists()) {
            $errors[] = 'Student ID is already registered in the system.';
        }

        if ($data['email'] && User::where('email', $data['email'])->exists()) {
            $errors[] = 'Email is already registered in the system.';
        }

        return array_values(array_unique($errors));
    }
}
