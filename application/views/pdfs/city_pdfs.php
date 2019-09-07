<?php

class MYPDF extends TCPDF {

    public $reports;
    public function setECM($data)
    {
        //$this->reports = $data;
    }
    /*public function Header() {
        
        $site = site_url();
        $date = date('M Y');
        $this->SetFont('Calibri', '', 10);

        $head = '<table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td color="#AEACAC" align="left">'.$date.'</td>';
                $head .= '<td color="#AEACAC" align="right"><a target="_blank" style="text-decoration:none;" color="#AEACAC" href="'.$site.'">'.$site.'</a></td>';
                $head .= '</tr>
        </table>';
        //$head = '';


        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '10', $head, $border = 0, $ln = '', $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
    }*/
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->SetFont('helvetica', 'B', 10);
        //$this->writeHTML($headerData['string']);
         $img_file = site_url().'uploads/pdf_requirements/watermark.png';
         $this->StartTransform();
         //$this->Rotate(20, 110, 110);
         $this->SetAlpha(0.2);
         $this->Image($img_file, $x='45', $y='90', $height='', $width='', $format='png', $link='', $align='center', $resize=true, $dpi=300, $palign='C', $is_mask=false, $imgmask=false, $boredr=0,$fitbox=true,$hidden=false,$fitonpage=true,$alt='',$altimgs=array());
         $this->StopTransform();

         $this->setPageMark();
         // $this->Rotate(0);
        
         //echo $img_file;die;
        $site = 'https://www.taxidio.com/';
        //$site = site_url();
        $img_file_header = site_url().'uploads/pdf_requirements/Final_Logo_Signature.jpeg';
        /* $head = '<table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td color="#AEACAC" align="left">'.$headerData['string'].'</td>';
                $head .= '<td color="#AEACAC" align="right"><a target="_blank" style="text-decoration:none;" color="#AEACAC" href="'.$site.'">'.$site.'</a></td>';
                $head .= '</tr>
        </table>';*/
         $head = '<table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td color="#AEACAC" align="left">'.$headerData['string'].'</td>';
                $head .= '<td color="#AEACAC" align="right"><a target="_blank" href="'.$site .'"><img style="width:100px;" src="'.$img_file_header.'"></a></td>';
                $head .= '</tr>
        </table>';


        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '10', $head, $border = 0, $ln = '', $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
    }

    public function Footer() {
        //$data = $this->reports;
        // Position at 15 mm from bottom
        $this->SetFont('Calibri', '', 10);
        $this->SetY(-15);
        /*if($data['reseller_id'] == '1') {
            $foot = '<p color="#AEACAC" align="center">P: <a target="_blank" style="text-decoration:none;" color="#AEACAC" href="tel:'.$data["phone_no"].'">'.$data["phone_no"].'</a> | W: <a target="_blank" style="text-decoration:none;" color="#AEACAC" href="'.$data["reseller_url"].'">'.$data["reseller_url"].'</a></p>';
        } else {
            $foot = '<p color="#AEACAC" align="center">P: <a target="_blank" style="text-decoration:none;" color="#AEACAC" href="tel:+91'.$data["user_phone"].'">+91 '.$data["user_phone"].'</a> | E: <a target="_blank" style="text-decoration:none;" color="#AEACAC" href="mailto:'.$data["user_email"].'">'.$data["user_email"].'</a> | W: <a target="_blank" style="text-decoration:none;" color="#AEACAC" href="'.$data["reseller_url"].'">'.$data["reseller_url"].'</a></p>';
        }*/
        /* $foot = '<p color="#AEACAC" align="center">Disclaimer - 
All rights reserved. This Travel Guide (inclusive of all content – text, graphics, images, illustrations, designs, etc.) is an Intellectual Property of Taxidio Travel India Pvt. Ltd. Copying, republishing, uploading, reproducing, downloading, distributing, posting, transmitting, or duplicating any of the material is prohibited.
All the information contained in this Travel Guide is purely a work of research. The information may have undergone changes from the time it has been compiled. Taxidio Travel India Pvt. Ltd. Cannot be held liable for any alteration, inaccuracies or omissions.</p>';*/
        //$this->Cell(0, 10, 'Refer Disclaimer     Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    


          $head = '<table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td color="#AEACAC" align="left">Refer Disclaimer</td>';
                $head .= '<td color="#AEACAC" align="right"> Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().'</td>';
                $head .= '</tr>
        </table>';


        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '285', $head, $border = 0, $ln = '', $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
       
        //$this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '285', $foot, $border = 0, $ln = '', $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
    }

}

/*$implementation_activities = $reports['page_implementation_activities'] != '' ? unserialize($reports['page_implementation_activities']) : '';
$implementation_activity_comment = $reports['page_implementation_activities_comments'] != '' ? unserialize($reports['page_implementation_activities_comments']) : '';
$implementation_activity_content = $reports['page_implementation_content'];

$promotion_activities1 = $promotion_activities = $reports['promotion_activities'] != '' ? unserialize($reports['promotion_activities']) : '';
$promotion_comments_no = $reports['promotion_comments_no'] != '' ? unserialize($reports['promotion_comments_no']) : '';
$promotion_url = $reports['promotion_url'] != '' ? unserialize($reports['promotion_url']) : '';

$strategy_activity = $reports['strategy_activity'] != '' ? unserialize($reports['strategy_activity']) : '';*/

//$name = $reports["client_name"]."_".$reports["report_month"]."_".$reports["report_year"];
//$name="Monthly\nSEO\nReport\n-\n".$reports["client_name"]."\n(".$reports["report_month"]."\n".$reports["report_year"].")";
/*$name='Monthly_SEO_Report_-_'.$reports["client_name"].'_('.$reports["report_month"].'_'.$reports["report_year"].')';*/

// create new PDF document


include "city_main_pdf.php";

//$flag = $this->uri->segment(3) != '' ? 'D' : 'I';

//$name = 'Monthly SEO Report - '.$reports["client_name"].' ('.$reports["report_month"].' '.$reports["report_year"].')'.'.pdf';
//$name = 'Monthly_SEO_Report_-_'.$reports["client_name"].'_('.$reports["report_month"].'_'.$reports["report_year"].')'.".pdf";

//Close and output PDF document

//$pdf->Output($name, $flag);//$flag




//============================================================+
// END OF FILE
//============================================================+
?>
