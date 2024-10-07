<?php

namespace Database\Seeders;

use Faker\Factory;

use App\Models\PdfTemplate;
use Illuminate\Database\Seeder;

class PdfTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Certificate layout 1
        $twigCertificateFilePath = resource_path('views/filament/resources/learning-certificate-resource/pages/pdf-layout.html.twig');
        file_exists($twigCertificateFilePath) ? $sorceCertificate = file_get_contents($twigCertificateFilePath) : $sorceCertificate = 'Default content or error message';

        $logos = [];
        $logos[] = [
            'name' => 'bg1',
            'image_path' => 'images/logos/bg-certificate.png'
        ];

        $pdf = new PdfTemplate();
        $pdf->pdf_type = 'certificate';
        $pdf->name = 'Certificate template 1';
        $pdf->lang = 'EN';
        $pdf->is_default = true;
        $pdf->is_active = true;
        $pdf->width = 210;
        $pdf->height = 297;
        $pdf->logo = $logos;
        $pdf->source_code = $sorceCertificate;
        $pdf->save();

        // Certificate layout 2
        $logos = [];
        $logos[] = [
            'name' => 'bg1',
            'image_path' => 'images/logos/bg-certificate-2.png'
        ];

        $pdf = new PdfTemplate();
        $pdf->pdf_type = 'certificate';
        $pdf->name = 'Certificate template 2';
        $pdf->lang = 'EN';
        $pdf->is_default = false;
        $pdf->is_active = true;
        $pdf->width = 297;
        $pdf->height = 420;
        $pdf->logo = $logos;
        $pdf->source_code = $sorceCertificate;
        $pdf->save();
    }
}
