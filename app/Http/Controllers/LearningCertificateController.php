<?php

namespace App\Http\Controllers;

use Twig\Environment;
use App\Models\PdfTemplate;
use Illuminate\Http\Request;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Barryvdh\DomPDF\Facade\Pdf;
use Twig\Loader\FilesystemLoader;
use App\Models\LearningCertificate;
use Illuminate\Support\Facades\App;

class LearningCertificateController extends Controller
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader([
            resource_path('views'),
        ]);
        $this->twig = new Environment($loader);
    }

    public function index(LearningCertificate $learningResource, Request $request)
    {
        $isDownload = $request->get('isDownload', false);
        $userName = $learningResource->user->name;
        $testName = $learningResource->name;
        $validTo = $learningResource->valid_to;

        // Render the template with Twig resources/views/templates/pages/pdf-template.html.twig
        $templateHtml = $this->twig->render('templates/pages/pdf-template.html.twig', [
            'userName' => $userName,
            'testName' => $testName,
            'validTo' => $validTo,

            'firstLine' => __('twig.certificate'),
            'secondLine' => __('twig.for_successfully_completing_the_test'),
            'thirdLine' => __('twig.this_certificate_Iis_granted_to'),
            'fourthLine' => __('twig.has_completed_the_test'),
            'fifthLine' => __('twig.valid_until'),
        ]);

        if ($learningResource->test->layout) {
            $defaultLayout = $learningResource->test->layout;
            $layoutHtml = $defaultLayout->source_code;

            $width = $learningResource->test->layout->width;
            $height = $learningResource->test->layout->height;
        } else {
            $defaultLayout = PdfTemplate::where('pdf_type', 'certificate')->where('is_default', 1)->first();
            $layoutHtml = $defaultLayout->source_code;

            $width = $defaultLayout->width;
            $height = $defaultLayout->height;
        }

        $jsonImages = $defaultLayout->logo;
        $images = [];
        foreach ($jsonImages as $item) {
            $images[$item['name']] = $item['image_path'];
        }

        // Create an ArrayLoader with the layout template
        $arrayLoader = new ArrayLoader([
            'certificate_layout' => $layoutHtml,
        ]);

        // Add the ArrayLoader to the existing FilesystemLoader
        $this->twig->setLoader(new ChainLoader([
            $this->twig->getLoader(),
            $arrayLoader,
        ]));

        // Embed the rendered template into the layout
        $html = $this->twig->render('certificate_layout', [
            'content' => $templateHtml,
            'width' => $width,
            'height' => $height,
            'images' => $images,
        ]);

        $username = $learningResource->user->name;
        $course = $learningResource?->name;

        $pdf = Pdf::loadHTML($html);
        if ($isDownload) {
            return $pdf->download($username . ' ' . $course . ' ' . __('learning/learningCertificate.label') . '.pdf');
        }

        return $pdf->stream('filament.app.resources.learning-certificates.pdf');
    }
}