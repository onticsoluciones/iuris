<?php

namespace Ontic\Iuris\Service\Renderer;

use Ontic\Iuris\Model\Analysis;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class PdfRenderer
{
    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        Settings::setPdfRendererName('DomPDF');
        Settings::setPdfRendererPath("$rootDir/vendor/dompdf/dompdf");
    }

    /**
     * @param Analysis $analysis
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getPdf(Analysis $analysis)
    {
        $document = new PhpWord();
        $titleStyle = new \PhpOffice\PhpWord\Style\Font();
        $titleStyle->setName('DejaVu Sans, sans-serif');
        $titleStyle->setBold(true);
        $messageStyle = new \PhpOffice\PhpWord\Style\Font();
        $messageStyle->setName('DejaVu Sans, sans-serif');
        
        foreach($analysis->getDetails() as $detail)
        {
            $section = $document->addSection();
            
            // Title
            $titleText = sprintf('%s - %s', $detail->getAnalyzer(), $detail->getScore());
            $titleElement = $section->addText($titleText);
            $titleElement->setFontStyle($titleStyle);
            $section->addLine();
            
            // Message
            foreach(explode("\n", $detail->getMessage()) as $line)
            {
                $textElement = $section->addText($line);
                $textElement->setFontStyle($messageStyle);
            }
        }
        
        $tmpFile = tempnam(sys_get_temp_dir(), 'iuris');
        $writer = IOFactory::createWriter($document, 'PDF');
        $writer->save($tmpFile);
        $output = file_get_contents($tmpFile);
        unlink($tmpFile);
        
        return $output;
    }
}