<?php

namespace Ontic\Iuris\Service\Renderer;

use Ontic\Iuris\Model\Analysis;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class OdtRenderer
{
    /**
     * @param Analysis $analysis
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getOdt(Analysis $analysis)
    {
        $document = new PhpWord();
        
        foreach($analysis->getDetails() as $detail)
        {
            $section = $document->addSection();
            
            // Title
            $titleStyle = new \PhpOffice\PhpWord\Style\Font();
            $titleStyle->setName('DejaVu Sans, sans-serif');
            $titleStyle->setBold(true);
            $titleText = sprintf('%s - %s', $detail->getAnalyzer(), $detail->getScore());
            $titleElement = $section->addText($titleText);
            $titleElement->setFontStyle($titleStyle);
            
            // Message
            foreach(explode("\n", $detail->getMessage()) as $line)
            {
                $messageStyle = new \PhpOffice\PhpWord\Style\Font();
                $messageStyle->setName('DejaVu Sans, sans-serif');
                $textElement = $section->addText(htmlspecialchars($line, ENT_XML1));
                $textElement->setFontStyle($messageStyle);
            }
        }
        
        $tmpFile = tempnam(sys_get_temp_dir(), 'iuris');
        $writer = IOFactory::createWriter($document, 'ODText');
        $writer->save($tmpFile);
        $output = file_get_contents($tmpFile);
        unlink($tmpFile);
        
        return $output;
    }
    
}