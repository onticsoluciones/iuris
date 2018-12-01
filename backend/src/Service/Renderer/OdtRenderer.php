<?php

namespace Ontic\Iuris\Service\Renderer;

use Ontic\Iuris\Model\Analysis;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class OdtRenderer
{
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }


    /**
     * @param Analysis $analysis
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getOdt(Analysis $analysis)
    {
        $document = new PhpWord();
        $document->addFontStyle('titleOK',array('bold'=>true,'size'=>14, 'color'=>'009933','name'=>'Helvetica'));
        $document->addFontStyle('mesOK',array('bold'=>true,'size'=>12, 'color'=>'009933','name'=>'DejaVu Sans, sans-serif'));

        $document->addFontStyle('titleFAIL',array('bold'=>true,'size'=>14, 'color'=>'b30000','name'=>'Helvetica'));
        $document->addFontStyle('mesFAIL',array('bold'=>true,'size'=>12, 'color'=>'b30000','name'=>'DejaVu Sans, sans-serif'));

        $document->addFontStyle('titleWARN',array('bold'=>true,'size'=>14, 'color'=>'ff9900','name'=>'Helvetica'));
        $document->addFontStyle('mesWARN',array('bold'=>true,'size'=>12, 'color'=>'ff9900','name'=>'DejaVu Sans, sans-serif'));

        $document->addFontStyle('info',array('bold'=>false,'size'=>12 , 'color'=>'000000','name'=>'DejaVu Sans, sans-serif'));

        //Title
        $title = $document->addSection();
        $title->addImage($this->rootDir.'/assets/logo_iuris.png');
        $title->addText("Informe Técnico de ".$analysis->getUrl(),array('name'=>'Courier','bold'=>true, 'color'=>'000000', 'size'=>22),array('align'=>'center', 'spaceAfter'=>100));
        //$title->addText('______________________________________________________________________________________________'    );
        $title->addTextBreak('3');

        $section = $document->addSection();


        foreach($analysis->getDetails() as $detail)
        {

            // Title
            $table=$section->addTable();
            $table->addRow();
            if ($detail->getScore() == 100) {
                $table->addCell(2000)->addText("Verificación: ".$detail->getAnalyzer(),'titleOK');
                $table->addCell(2000)->addText("Puntuación: ".$detail->getScore(),'titleOK');
            }
            elseif ($detail->getScore() >= 50 && $detail->getScore() < 100 ) {
                $table->addCell(2000)->addText("Verificación: ".$detail->getAnalyzer(),'titleWARN');
                $table->addCell(2000)->addText("Puntuación: ".$detail->getScore(),'titleWARN');
            }
            else {
                $table->addCell(2000)->addText("Verificación: ".$detail->getAnalyzer(),'titleFAIL');
                $table->addCell(2000)->addText("Puntuación: ".$detail->getScore(),'titleFAIL');            }
            // Message
            foreach(explode("\n", $detail->getMessage()) as $line)
            {
                $table->addRow();
                if ($detail->getScore() == 100){
                    $table->addCell(null,array('gridSpan' => 3, 'vMerge' => 'restart'))->addText(htmlspecialchars($line,ENT_XML1))->setFontStyle('titleOK');
                 }
                elseif(strpos($line,'⚠')!== FALSE){
                    // $section->addText('   * Información del Fallo','info');
                     $table->addCell(null,array('gridSpan' => 3, 'vMerge' => 'restart'))->addText(htmlspecialchars($line,ENT_XML1))->setFontStyle('titleWARN');
                }
                else{
                    //$section->addText('   * Información del Fallo','info');
                    $table->addCell(null,array('gridSpan' => 3, 'vMerge' => 'restart'))->addText(htmlspecialchars($line,ENT_XML1))->setFontStyle('titleFAIL');
                }
            }
            //$section->addText('______________________________________________________________________________________________'    );
            $section->addTextBreak('1');
        }

            //$section->addText('__________________________________________________________________________');
            //$section->addTextBreak('3');
            $section = $document->addSection();

            if ($analysis->getGlobalScore() == 100) {
                $section->addText("Puntuación Total: " . round($analysis->getGlobalScore()), 'titleOK');
                //Añadir SELLO
                $section->addImage($this->rootDir . '/assets/logocompliance.jpg');


            } elseif ($analysis->getGlobalScore() >= 50 && $detail->getScore() < 100) {
                $section->addText("Puntuación Total: " . round($analysis->getGlobalScore()), 'titleWARN');

            } else {
                $section->addText("Puntuación Total: " . round($analysis->getGlobalScore()), 'titleFAIL');
            }

        $tmpFile = tempnam(sys_get_temp_dir(), 'iuris');
        $writer = IOFactory::createWriter($document, 'ODText');
        $writer->save($tmpFile);
        $output = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $output;
    }

}