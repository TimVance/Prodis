<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PhpWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use Dompdf\Dompdf as DompdfLib;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * DomPDF writer
 *
 * @see  https://github.com/dompdf/dompdf
 * @since 0.10.0
 */
class DomPDF extends AbstractRenderer implements WriterInterface
{
    /**
     * Name of renderer include file
     *
     * @var string
     */
    protected $includeFile = null;

    /**
     * Save PhpWord to file.
     *
     * @param string $filename Name of the file to save as
     */
    public function save($filename = null)
    {
        $fileHandle = parent::prepareForSave($filename);

        //  PDF settings
        $paperSize = 'A4';
        $orientation = 'portrait';

        //  Create PDF
        $pdf = new DompdfLib();
        $pdf->setPaper(strtolower($paperSize), $orientation);
        $css = "
                <style>
                body * {  
                    font-family: DejaVu Serif, serif !important;
                    font-size: 14px;
                    font-weight: normal;
                }
                td {
                border: 1px solid #ccc !important; 
                }
                </style>
        ";
        echo $this->getContent().$css;
        exit();
        $pdf->loadHtml(str_replace(PHP_EOL, '', $this->getContent()).$css);
        $pdf->render();

        //  Write to file
        fwrite($fileHandle, $pdf->output());

        parent::restoreStateAfterSave($fileHandle);
    }
}
