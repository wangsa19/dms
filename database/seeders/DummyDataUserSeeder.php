<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\License;
use App\Models\LicenseVersion;
use App\Models\DocumentOut;
use App\Models\DocumentType;
use App\Models\Category;
use App\Models\Field;
use App\Models\Department;
use App\Models\Section;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;

class DummyDataUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create a minimal valid PDF content
        $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";

        // Write to storage/app/public/documents/testing.pdf and storage/app/public/licenses/testing.pdf
        Storage::disk('public')->makeDirectory('documents');
        Storage::disk('public')->makeDirectory('licenses');
        
        Storage::disk('public')->put('documents/testing.pdf', $pdfContent);
        Storage::disk('public')->put('licenses/testing.pdf', $pdfContent);

        for ($i = 1; $i <= 3; $i++) {
            $docType = DocumentType::inRandomOrder()->first() ?? DocumentType::first();
            $category = Category::inRandomOrder()->first() ?? Category::first();
            $field = Field::inRandomOrder()->first() ?? Field::first();
            $department = Department::inRandomOrder()->first() ?? Department::first();
            $section = Section::inRandomOrder()->first() ?? Section::first();
            $employee = Employee::inRandomOrder()->first() ?? Employee::first();
            $user = User::inRandomOrder()->first() ?? User::first();
            $actionUnit = \App\Models\ActionFrequencyUnit::inRandomOrder()->first();
            $rack = \App\Models\Rack::inRandomOrder()->first();

            // Check if required dependencies exist
            if (!$docType || !$category || !$field || !$department || !$section || !$employee || !$user) {
                $this->command->error("Dependencies missing. Please make sure DocumentType, Category, Field, Department, Section, Employee, and User have at least one record.");
                return;
            }

            // 2. Create Dummy Document
            $document = Document::create([
                'name_id' => 'Dokumen Testing Dummy ' . rand(100, 999),
                'name_jp' => 'テストドキュメント ' . rand(100, 999),
                'document_type_id' => $docType->id,
                'category_id' => $category->id,
                'field_id' => $field->id,
                'department_id' => $department->id,
                'section_id' => $section->id,
                'owner_id' => $employee->id,
                'rack_id' => $rack ? $rack->id : null,
                'status' => $i % 2 == 0 ? 'Inactive' : 'Active',
                'current_version' => 1,
            ]);

            // Create DocumentVersion
            DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => 1,
                'file_name' => 'testing.pdf',
                'file_path' => 'documents/testing.pdf',
                'file_type' => 'application/pdf',
                'file_size' => strlen($pdfContent),
                'revision_notes' => 'Initial version ' . $i,
                'uploader_id' => $user->id,
            ]);

            $occurrenceTypes = ['Yearly', 'Monthly', 'Once', 'Bi-Annual'];
            $issuers = ['Kementerian Kesehatan', 'Badan Pengawas Obat dan Makanan', 'Pemerintah Daerah', 'Dinas Tenaga Kerja', 'Kepolisian RI'];

            // 3. Create Dummy License
            $license = License::create([
                'name_id' => 'Lisensi Testing Dummy ' . rand(100, 999),
                'name_jp' => 'テストライセンス ' . rand(100, 999),
                'document_type_id' => $docType->id,
                'category_id' => $category->id,
                'field_id' => $field->id,
                'department_id' => $department->id,
                'section_id' => $section->id,
                'owner_id' => $employee->id,
                'rack_id' => $rack ? $rack->id : null,
                'status' => $i % 2 == 0 ? 'Inactive' : 'Active',
                'start_date' => Carbon::now()->subMonths(rand(1, 12)),
                'end_date' => Carbon::now()->addYears(rand(1, 5))->addMonths(rand(1, 11)),
                'reminder_date' => Carbon::now()->addMonths(rand(1, 11)),
                'occurrence_type' => $occurrenceTypes[array_rand($occurrenceTypes)],
                'action_frequency_value' => rand(1, 5),
                'action_frequency_unit_id' => $actionUnit ? $actionUnit->id : null,
                'government_issuer' => $issuers[array_rand($issuers)],
                'current_version' => 1,
            ]);

            // Create LicenseVersion (Assume it exists based on relation)
            if (class_exists(LicenseVersion::class)) {
                LicenseVersion::create([
                    'license_id' => $license->id,
                    'version_number' => 1,
                    'file_name' => 'testing.pdf',
                    'file_path' => 'licenses/testing.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => strlen($pdfContent),
                    'revision_notes' => 'Initial version ' . $i,
                    'uploader_id' => $user->id,
                ]);
            }

            // 4. Create Dummy DocumentOut
            DocumentOut::create([
                'document_id' => $document->id,
                'borrower_id' => $employee->id,
                'checkout_time' => Carbon::now()->subDays(rand(1, 30)),
                'return_time' => $i % 2 == 0 ? Carbon::now()->subDays(rand(1, 5)) : null,
                'status' => $i % 2 == 0 ? 'Returned' : 'Borrowed',
                'created_by' => $user->id,
            ]);
        }

        $this->command->info("3 Dummy Documents, Licenses, and DocumentOuts created successfully with dynamic randomized data.");
    }
}
