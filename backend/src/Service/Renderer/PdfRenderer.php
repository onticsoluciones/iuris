<?php

namespace Ontic\Iuris\Service\Renderer;

use Ontic\Iuris\Model\Analysis;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class PdfRenderer
{
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        Settings::setPdfRendererName('DomPDF');
        Settings::setPdfRendererPath("$rootDir/vendor/dompdf/dompdf");
        $this->rootDir = $rootDir;
    }

    /**
     * @param Analysis $analysis
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getPdf(Analysis $analysis)
    {
        $document = new PhpWord();

        $document->addFontStyle('titleOK',array('bold'=>true,'size'=>14, 'color'=>'009933','name'=>'Helvetica'));
        $document->addFontStyle('mesOK',array('bold'=>true,'size'=>12, 'color'=>'009933','name'=>'DejaVu Sans'));

        $document->addFontStyle('titleFAIL',array('bold'=>true,'size'=>14, 'color'=>'b30000','name'=>'Helvetica'));
        $document->addFontStyle('mesFAIL',array('bold'=>true,'size'=>12, 'color'=>'b30000','name'=>'DejaVu Sans'));

        $document->addFontStyle('titleWARN',array('bold'=>true,'size'=>14, 'color'=>'ff9900','name'=>'Helvetica'));
        $document->addFontStyle('mesWARN',array('bold'=>true,'size'=>12, 'color'=>'ff9900','name'=>'DejaVu Sans'));

        $document->addFontStyle('info',array('bold'=>true,'size'=>12 , 'color'=>'000000','name'=>'DejaVu Sans'));



        //Title
        $title = $document->addSection();
        $title->addImage($this->rootDir.'/assets/logo_iuris.png',array('width'=> 250,'height'=> 100,'wrappingStyle' => 'behind'));
        $title->addText("Informe Técnico de ".$analysis->getUrl(),array('name'=>'Courier','bold'=>true, 'color'=>'000000', 'size'=>30),array('align'=>'center', 'spaceAfter'=>100));
        //$title->addText('______________________________________________________________________________________________'    );
        $title->addTextBreak('1');

        foreach($analysis->getDetails() as $detail)
        {
            $section = $document->addSection();
            
            // Title
            $table=$section->addTable();
            $table->addRow();
            if ($detail->getScore() == 100) {
                $table->addCell()->addText("Verificación: ".$detail->getAnalyzer(),'titleOK');
                $table->addCell()->addText("Puntuación: ".$detail->getScore(),'titleOK');
            }
            elseif ($detail->getScore() >= 50 && $detail->getScore() < 100 ) {
                $table->addCell()->addText("Verificación: ".$detail->getAnalyzer(),'titleWARN');
                $table->addCell()->addText("Puntuación: ".$detail->getScore(),'titleWARN');
                }
            else {
                $table->addCell()->addText("Verificación: ".$detail->getAnalyzer(),'titleFAIL');
                $table->addCell()->addText("Puntuación: ".$detail->getScore(),'titleFAIL');            }
            // Message
            foreach(explode("\n", $detail->getMessage()) as $line)
            {
                if ($detail->getScore() == 100) $section->addText($line,'mesOK');
                elseif(strpos($line,'⚠')!== FALSE){
                    $section->addText('   * Información del Fallo','info');
                    $section->addText($line,'mesWARN');
                }
                else{
                    $section->addText('   * Información del Fallo','info');
                    $section->addText($line,'mesFAIL');
                }
            }
            //$section->addText('______________________________________________________________________________________________'    );
            $section->addTextBreak('1');
        }

        //Footer
        
        $tmpFile = tempnam(sys_get_temp_dir(), 'iuris');
        $writer = IOFactory::createWriter($document, 'PDF');
        $writer->save($tmpFile);
        $output = file_get_contents($tmpFile);
        unlink($tmpFile);
        
        return $output;
    }
}