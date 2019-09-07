<?php
//$_SERVER['DOCUMENT_ROOT']
// add a page
//ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
if(!empty($city_details))
{
  $pdf_array = array();
foreach ($city_details as $key => $value) 
{
  
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$data = $reports;
  $pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$value->city_name, $tc=array(0,0,0), $lc=array(0,0,0));
$pdf->setECM($reports=array());

$pdf->SetTitle($name='');
//$pdf->header('as');

$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true);



/*here*/
//$pdf->AddPage();

    // set a bookmark for the current position
    
/*End here*/


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}




$fontname = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/Lato-Bold.ttf', 'TrueTypeUnicode', '', 32);

$fontname_text = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/CormorantGaramond-Regular.ttf', 'TrueTypeUnicode', '', 32);

$fontname_text_italic = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/CormorantGaramond-Italic.ttf', 'TrueTypeUnicode', '', 32);
// set font
//$fontname = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/Calibri.ttf', 'TrueTypeUnicode', '', 32);
//$fontname = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/calibrib.ttf', 'TrueTypeUnicode', '', 32);

//$pdf->AddFont($fontname, '', 'system/helpers/tcpdf/fonts/Lato-Bold.ttf', 'TrueTypeUnicode');
$pdf->SetFont('helvetica', 'B', 16);
//$pdf->SetFont($fontname, '', 16);
   //$pdf->AddPage();
    $count = 1;
    $html = '<div class="col-sm-12" style="font-family:'.$fontname.';text-align:justify;"> ';
    //print_r($html);die;
    if($value->city_geographical_location!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Geographical location', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();

        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;font-size: 18px;"><b>'.$count.'. Geographical location'.'</b></p>
            <p style="font-family:'.$fontname_text.';text-align:justify;font-size: 17px;
line-height: 1.5;">'.$value->city_geographical_location.'</p>';
          //print_r($html);die;
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_neighbours!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Neighbouring cities/countries', 0, 0, '', 'B', array(0,64,128));
        //$pdf->SetFont($fontname, '', 16);
        $pdf->SetFont('helvetica', 'B', 16);
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Neighbouring cities/countries'.'</b></p>
            <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_neighbours.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_significance!="")
    {
       
       /* $html .='<p><b>'.$count.'. Significance'.'</b></p>
        <p>'.$value->city_significance.'</p>';*/

        $pdf->AddPage();
        $pdf->Bookmark('Significance', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Significance'.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_significance.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_history!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('History', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. History'.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_history.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_cultural_identity!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Cultural identity', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Cultural identity'.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_cultural_identity.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_natural_resources!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Natural resources', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Natural resources'.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_natural_resources.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if(!empty($attraction_details[$value->id]))
    {
      /*foreach (range('a', 'z') as $i)
      {
        $letters[] = $i;
      }*/
      

        $count_inner = $count;
        $pdf->AddPage();
        $pdf->Bookmark('Key site seeing places (paid and free)', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Key site seeing places (paid and free) '.'</b></p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        if(!empty($attraction_details[$value->id]['attraction_details']['paid']))
        {
          $count_inner +=0.1;
          $paid_count = 0;
          //echo $count_inner;die;
          //$pdf->AddPage();
          $pdf->Bookmark('- Paid', 1, 0, '', 'B', array(0,64,128));
          $index_link = $pdf->AddLink();
          $pdf->SetLink($index_link, 0, '*1');
          //$pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
          $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count_inner.'. Paid '.'</b></p>';
          $pdf->writeHTML($html, true, false, true, false, '');
          //echo '<pre>';print_r($attraction_details[$value->id]['attraction_details']);die;
           foreach ($attraction_details[$value->id]['attraction_details']['paid'] as $key1 => $value1) 
           {
               $paid_count +=1;
             //$count_inner
              //$pdf->AddPage();
              $pdf->Bookmark("-- ".$value1->attraction_name, 2, 0, '', '', array(0,64,128));
              $index_link = $pdf->AddLink();
              $pdf->SetLink($index_link, 0, '*1');
              //$pdf->Cell(0, 10, $value1->attraction_name, 0, 1, 'L');

               $html= '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count_inner.'.'.$paid_count.'. '.$value1->attraction_name.'</b><br/><span style="font-family:'.$fontname_text.';text-align:justify;">';
               $html.=$value1->attraction_details.'</span></p>';
               //print_r($html);die;
                $pdf->writeHTML($html, true, false, true, false, '');
               if($value1->attraction_known_for!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Known For : ';
                $html.= $value1->attraction_known_for.'</p>';
                 $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_address!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Address : ';
                $html.= $value1->attraction_address.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->zipcode!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Zipcode : ';
                $html.= $value1->zipcode.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_lat!='' && $value1->attraction_long!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Location : ';
                $html.= $value1->attraction_lat.', '.$value1->attraction_long.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_contact!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Contact : ';
                $html.= $value1->attraction_contact.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_website!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Website : ';
                $html.= $value1->attraction_website.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_public_transport!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Nearest Public Transport : ';
                $html.= $value1->attraction_public_transport.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_admissionfee!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Entry Fee : ';
                $html.= $value1->attraction_admissionfee.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_timing!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Open & Close Timing : ';
                $html.= $value1->attraction_timing.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_time_required!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Time Required : ';
                $html.= $value1->attraction_time_required.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_wait_time!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Expected Wait Time : ';
                $html.= $value1->attraction_wait_time.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_buy_ticket!='')
               {
                $value1->attraction_buy_ticket = ($value1->attraction_buy_ticket == 1) ? 'Yes' : 'No';
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Buy Ticket : ';
                $html.= $value1->attraction_buy_ticket.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               /*if($value1->attraction_getyourguid!='')
               {
                $value1->attraction_getyourguid = ($value1->attraction_getyourguid == 1) ? 'Yes' : 'No';
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Get Your Guide : ';
                $html.= $value1->attraction_getyourguid.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->tag_star!='')
               {
                $value1->tag_star = ($value1->tag_star == 1) ? 'Yes' : 'No';
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Starting Point & Star : ';
                $html.= $value1->tag_star.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }*/
               $html= '<br/>';
               $pdf->writeHTML($html, true, false, true, false, '');  
           } 
        }
        if(!empty($attraction_details[$value->id]['attraction_details']['free']))
        {
          $count_inner +=0.1;
          $paid_count = 0;
          //echo $count_inner;die;
          $pdf->AddPage();
          $pdf->Bookmark(' - Free', 1, 0, '', 'B', array(0,64,128));
          $index_link = $pdf->AddLink();
          $pdf->SetLink($index_link, 0, '*1');
          $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
          $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count_inner.'. Free '.'</b></p>';
          $pdf->writeHTML($html, true, false, true, false, '');
          //echo '<pre>';print_r($attraction_details[$value->id]['attraction_details']);die;
           foreach ($attraction_details[$value->id]['attraction_details']['free'] as $key1 => $value1) 
           {
               $paid_count +=1;
             //$count_inner
              //$pdf->AddPage();
              $pdf->Bookmark(" -- ".$value1->attraction_name, 2, 0, '', '', array(0,64,128));
              $index_link = $pdf->AddLink();
              $pdf->SetLink($index_link, 0, '*1');
              //$pdf->Cell(0, 10, $value1->attraction_name, 0, 1, 'L');

               $html= '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count_inner.'.'.$paid_count.'. '.$value1->attraction_name.'</b><br/><span style="font-family:'.$fontname_text.';text-align:justify;">';
               $html.= $value1->attraction_details.'</span></p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               if($value1->attraction_known_for!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Known For : ';
                $html.= $value1->attraction_known_for.'</p>';
                 $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_address!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Address : ';
                $html.= $value1->attraction_address.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->zipcode!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Zipcode : ';
                $html.= $value1->zipcode.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_lat!='' && $value1->attraction_long!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Location : ';
                $html.= $value1->attraction_lat.', '.$value1->attraction_long.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_contact!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Contact : ';
                $html.= $value1->attraction_contact.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_website!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Website : ';
                $html.= $value1->attraction_website.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_public_transport!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Nearest Public Transport : ';
                $html.= $value1->attraction_public_transport.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_admissionfee!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Entry Fee : ';
                $html.= $value1->attraction_admissionfee.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_timing!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Open & Close Timing : ';
                $html.= $value1->attraction_timing.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_time_required!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Time Required : ';
                $html.= $value1->attraction_time_required.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_wait_time!='')
               {
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Expected Wait Time : ';
                $html.= $value1->attraction_wait_time.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->attraction_buy_ticket!='')
               {
                $value1->attraction_buy_ticket = ($value1->attraction_buy_ticket == 1) ? 'Yes' : 'No';
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Buy Ticket : ';
                $html.= $value1->attraction_buy_ticket.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               /*if($value1->attraction_getyourguid!='')
               {
                $value1->attraction_getyourguid = ($value1->attraction_getyourguid == 1) ? 'Yes' : 'No';
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Get Your Guide : ';
                $html.= $value1->attraction_getyourguid.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }
               if($value1->tag_star!='')
               {
                $value1->tag_star = ($value1->tag_star == 1) ? 'Yes' : 'No';
                $html= '<p style="font-family:'.$fontname_text.';text-align:justify;">Starting Point & Star : ';
                $html.= $value1->tag_star.'</p>';
                $pdf->writeHTML($html, true, false, true, false, '');
               }*/
               $html= '<br/>';
               $pdf->writeHTML($html, true, false, true, false, '');    
           } 
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if(!empty($adventures_details[$value->id]))
    {
        $pdf->AddPage();
        $pdf->Bookmark('Key sites sports & adventure', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Key sites sports & adventure'.'</b></p>';
        if($value->city_adventure_sports!="" && $value->city_adventure_sports!="NA")
        {  
           $html .= '<p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_adventure_sports.'</p>';   
        }


        

        $html .='<ul>';
        foreach ($adventures_details[$value->id] as $key_ad => $value_ad) 
        {
           $html .='<li>';
           $html .= $value_ad->adventure_name." - ";
           $html .= $value_ad->adventure_details;
           $html.= '<p style="font-family:'.$fontname_text.';text-align:justify;">';
          if($value_ad->adventure_address!='')
           {
            $html.= 'Address : ';
            $html.= $value_ad->adventure_address.'<br/>';
           }
           if($value_ad->adventure_lat!='' && $value_ad->adventure_long!='')
           {
            $html.= 'Location : ';
            $html.= $value_ad->adventure_lat.','.$value_ad->adventure_long.'<br/>';
           }
           if($value_ad->adventure_contact!='')
           {
            $html.= 'Contact No : ';
            $html.= $value_ad->adventure_contact.'<br/>';
           }
           if($value_ad->adventure_website!='')
           {
            $html.= 'Website : ';
            $html.= $value_ad->adventure_website.'<br/>';
           }
           if($value_ad->adventure_nearest_public_transport!='')
           {
            $html.= 'Nearest Public Transport : ';
            $html.= $value_ad->adventure_nearest_public_transport.'<br/>';
           }
           if($value_ad->adventure_admissionfee!='')
           {
            $html.= 'Entry Fees : ';
            $html.= $value_ad->adventure_admissionfee.'<br/>';
           }
           if($value_ad->adventure_open_close_timing!='')
           {
            $html.= 'Open / Close Time : ';
            $html.= $value_ad->adventure_open_close_timing.'<br/>';
           }
           if($value_ad->adventure_time_required!='')
           {
            $html.= 'Time Required : ';
            $html.= $value_ad->adventure_time_required.'<br/>';
           }
           if($value_ad->adventure_wait_time!='')
           {
            $html.= 'Wait Time : ';
            $html.= $value_ad->adventure_wait_time.'<br/>';
           }
            $html.= '</p>';
            
           $html .='</li>';

           
        }
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;

        //$count++;
    }
    if($value->city_local_sports_stadium!="" || !empty($stadium_details[$value->id]))
    {
        $pdf->AddPage();
        $pdf->Bookmark('Adventure sports, Local sports, stadiums', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Adventure sports, Local sports, stadiums'.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_local_sports_stadium.'</p>';
        if(!empty($stadium_details[$value->id]))
        {
            
            $html .='<ul>';
            foreach ($stadium_details[$value->id] as $key_stdium => $value_stdium) 
            {
               $html .='<li>';
               $html .= $value_stdium->stadium_name.": ";
               $html .= $value_stdium->stadium_description;
               $html.= '<p style="font-family:'.$fontname_text.';text-align:justify;">';
               if($value_stdium->stadium_address!='')
               {
                $html.= 'Address : ';
                $html.= $value_stdium->stadium_address.'<br/>';
               }
               if($value_stdium->stadium_lat!='' && $value_stdium->stadium_long!='')
               {
                $html.= 'Location : ';
                $html.= $value_stdium->stadium_lat.','.$value_stdium->stadium_long.'<br/>';
               }
               if($value_stdium->stadium_contact!='')
               {
                $html.= 'Contact No : ';
                $html.= $value_stdium->stadium_contact.'<br/>';
               }
               if($value_stdium->stadium_website!='')
               {
                $html.= 'Website : ';
                $html.= $value_stdium->stadium_website.'<br/>';
               }
               if($value_stdium->stadium_timing!='')
               {
                $html.= 'Open/Close time : ';
                $html.= $value_stdium->stadium_timing.'<br/>';
               }
               
               $html.= '</p>'; 
               $html .='</li>';
                
            }
            $html .='</ul>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if(!empty($spa_details[$value->id]))
    {
        $pdf->AddPage();
        $pdf->Bookmark('Spas and relaxation', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Spas and relaxation '.'</b></p><p style="font-family:'.$fontname_text.';text-align:justify;">';
        $html .=$value->relaxation_spa.'</p>';
        $html .='<ul>';
        foreach ($spa_details[$value->id] as $key_spa => $value_spa) 
        {
           $html .='<li>';
           $html .= $value_spa->ras_name.": ";
           $html .= $value_spa->ras_description;
           $html.= '<p style="font-family:'.$fontname_text.';text-align:justify;">';
           if($value_spa->ras_address!='')
           {
            $html.= 'Address : ';
            $html.= $value_spa->ras_address.'<br/>';
           }
           if($value_spa->ras_lat!='' && $value_spa->ras_long!='')
           {
            $html.= 'Location : ';
            $html.= $value_spa->ras_lat.','.$value_spa->ras_long.'<br/>';
           }
           if($value_spa->ras_contact!='')
           {
            $html.= 'Contact No : ';
            $html.= $value_spa->ras_contact.'<br/>';
           }
           if($value_spa->ras_website!='')
           {
            $html.= 'Website : ';
            $html.= $value_spa->ras_website.'<br/>';
           }
           
           $html.= '</p>'; 
           $html .='</li>';
            
        }
        $html .='</ul>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if(!empty($events_details[$value->id]))
    {
        $pdf->AddPage();
        $pdf->Bookmark('Events calendar', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Events calendar '.'</b></p>';
        $html .='<table border="1">';
        $html .='<tr><td><b>Month</b></td><td><b>Events</b></td></tr>';
        foreach ($events_details[$value->id] as $key_events => $value_events) 
        {
           $html .='<tr><td><b>';
           $html .=$value_events->month_name;
           $html .='</b></td><td>';
           $html .='<p><b>'.$value_events->event_name.'</b></p>';
           $html .='<p>'.$value_events->event_description.'</p>';
            $html .='</td></tr>';
        }
        $html .='<table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
        
    }
    if($value->city_guides_tours!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Key local guides and tours', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Key local guides and tours '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_guides_tours.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_transportation_hubs!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Transportation hubs', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Transportation hubs '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_transportation_hubs.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_transportation_costs!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Transportation cost', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Transportation cost '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_transportation_costs.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_baggage_allowance!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Baggage allowance', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Baggage allowance '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_baggage_allowance.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_food!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Must try food and local savouries', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Must try food and local savouries  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_food.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->restaurant_nightlife!="" || !empty($restaurants_details[$value->id]))
    {
        $pdf->AddPage();
        $pdf->Bookmark('Restaurant & Nightlife', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Restaurant & Nightlife  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->restaurant_nightlife.'</p>';
        if(!empty($restaurants_details[$value->id]))
        {
            
            $html .='<p style="font-family:'.$fontname.';text-align:justify;">Food - </p>
            <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->restaurant_nightlife.'</p>';
            $html .='<ul>';
            foreach ($restaurants_details[$value->id] as $key_restro => $value_restro) 
            {
               $html .='<li>';
               $html .= $value_restro->ran_name.": ";
               $html .= $value_restro->ran_description;
               $html.= '<p style="font-family:'.$fontname_text.';text-align:justify;">';
               if($value_restro->ran_address!='')
               {
                $html.= 'Address : ';
                $html.= $value_restro->ran_address.'<br/>';
               }
               if($value_restro->ran_lat!='' && $value_restro->ran_long!='')
               {
                $html.= 'Location : ';
                $html.= $value_restro->ran_lat.','.$value_restro->ran_long.'<br/>';
               }
               if($value_restro->ran_contact!='')
               {
                $html.= 'Contact No : ';
                $html.= $value_restro->ran_contact.'<br/>';
               }
               if($value_restro->ran_website!='')
               {
                $html.= 'Website : ';
                $html.= $value_restro->ran_website.'<br/>';
               }
               if($value_restro->ran_timing!='')
               {
                $html.= 'Open / Close Time : ';
                $html.= $value_restro->ran_timing.'<br/>';
               }
               
              $html .='</p>';
               $html .='</li>';
            }
            $html .='</ul>';
        }
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_avg_cost_meal_drink!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Average meal cost', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Average meal cost  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_avg_cost_meal_drink.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_shopping!="")
    {
        $pdf->AddPage();
        $pdf->Bookmark('Shopping', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $html = '<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Shopping  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_shopping.'</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_toursit_benefits!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Tourist benefits  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_toursit_benefits.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Tourist benefits', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_essential_local_apps!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Essential local apps  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_essential_local_apps.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Essential local apps', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_driving!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Driving  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_driving.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Driving', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_travel_essentials!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Travel essentials  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_travel_essentials.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Travel essentials', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    //Doubts here
    if($value->city_travel_essentials!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Essential clothes  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_travel_essentials.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Essential clothes', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_local_currency!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Local currency  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_local_currency.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Local currency', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_vendor_atm_commission!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Change vendor’s commission, ATM charges  '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_vendor_atm_commission.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Change vendor’s commission, ATM charges', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_staying_connected!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Staying connected   '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_staying_connected.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Staying connected', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_time_zone!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Time Zone    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_time_zone.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Time Zone', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_language_spoken!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Languages   '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_language_spoken.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Languages', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_political_scenario!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Political Scenario   '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_political_scenario.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Political Scenario', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_economic_scenario!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Economic Scenario   '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_economic_scenario.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Economic Scenario', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_religion_belief!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Religious belief    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_religion_belief.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Religious belief', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_visa_requirements!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Visa requirements    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_visa_requirements.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Visa requirements', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_essential_vaccination!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Essential vaccination    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_essential_vaccination.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Essential vaccination', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_safety!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Theft and Safety    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_safety.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Theft and Safety', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_embassies_consulates!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Embassy/Consulate    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_embassies_consulates.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Embassy/Consulate', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_restricted_accessibility!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Accessibility    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_restricted_accessibility.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Accessibility', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_emergencies!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Emergencies    '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_emergencies.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Emergencies', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_dos_donts!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'. Do’s and Don’ts     '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_dos_donts.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Do’s and Don’ts', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_tipping!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'.Tipping basics      '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_tipping.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Tipping basics', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    
    if($value->city_pet_imp_policies!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>'.$count.'.Pet info      '.'</b></p>
        <p style="font-family:'.$fontname_text.';text-align:justify;">'.$value->city_pet_imp_policies.'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Pet info', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    if($value->city_conclusion!="")
    {
       
        $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>Conclusion – Why to select this destination</b></p>
        <p style="font-family:'.$fontname_text_italic.';text-align:justify;"><i>'.$value->city_conclusion.'</i></p>';

        $pdf->AddPage();
        $pdf->Bookmark('Conclusion', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        //print_r($html);die;
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }
    $html ='<p style="font-family:'.$fontname.';text-align:justify;"><b>Disclaimer'.'</b></p>
    <p style="font-family:'.$fontname_text.';text-align:justify;">All rights reserved. This Travel Guide (inclusive of all content – text, graphics, images, illustrations, designs, etc.) is an Intellectual Property of Taxidio Travel India Pvt. Ltd. Copying, republishing, uploading, reproducing, downloading, distributing, posting, transmitting, or duplicating any of the material is prohibited.
All the information contained in this Travel Guide is purely a work of research. The information may have undergone changes from the time it has been compiled. Taxidio Travel India Pvt. Ltd. Cannot be held liable for any alteration, inaccuracies or omissions.</p>';
    $pdf->AddPage();
    $pdf->Bookmark('Disclaimer', 0, 0, '', 'B', array(0,64,128));
    $index_link = $pdf->AddLink();
    $pdf->SetLink($index_link, 0, '*1');
    $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
    $pdf->writeHTML($html, true, false, true, false, '');
   
    //$pdf->setECM($index_link);
    /*if(!empty($tag_array[$value->id]))
    {
        $html ='<p><b>Tags for the city - Known for      '.'</b></p>
        <p>'.implode(", ", $tag_array[$value->id]).'</p>';
        $pdf->AddPage();
        $pdf->Bookmark('Tags for the city - Known for', 0, 0, '', 'B', array(0,64,128));
        $index_link = $pdf->AddLink();
        $pdf->SetLink($index_link, 0, '*1');
        $pdf->Cell(0, 10, 'Index', 0, 1, 'R', false, $index_link);
        $pdf->writeHTML($html, true, false, true, false, '');
        $count++;
    }*/

    $pdf->addTOCPage();
    $pdf->SetFont('helvetica', 'B', 16);
    //$pdf->SetFont($fontname, '', 12);
    $pdf->MultiCell(0, 0, 'Table Of Content', 0, 'C', 0, 1, '', '', true, 0);
    $pdf->Ln();
    $pdf->SetFont('helvetica', 'B', 16);
    //$pdf->SetFont($fontname, '', 12);
    $pdf->addTOC(1, 'courier', '.', 'INDEX', 'B', array(128,0,0));
    $pdf->endTOCPage();
    /*
    //echo "dd";die;
    */
//restaurant_nightlife
   // print_r($tag_array[$value->id]);die;
    //echo '<pre>';print_r($restaurants_details[$value->id]);die;

        

        

        $count++;
    
    $html .='</div>';
    //print_r($html);die;
    //$pdf->writeHTML($html, true, false, true, false, '');
    $pdf->SetPrintHeader(true);
    // add a page
    //$pdf->AddPage();

    $pdf->SetPrintFooter(true);


    // add a page
    

    $folder_path = date('Y')."/".date('m');
    if (!is_dir($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/".$folder_path)) 
    {
          mkdir($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/".$folder_path, 0777, TRUE);

    }
    $pdf->Output($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/".$folder_path."/".$value->slug.".pdf", 'F');//$flag

    //$pdf->Output($_SERVER['DOCUMENT_ROOT']."uploads/city_pdfs/".$value->slug.".pdf", 'F');

    //$pdf->Output($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/".$value->slug.".pdf", 'F');//$flag
    //$pdf->Output($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/pdf1.pdf", 'F');//$flag
   
    

    $pdf_array[$key]['id'] = $value->id;
    $pdf_array[$key]['travelguide'] = $folder_path."/".$value->slug.".pdf";
 
    /*$CI =& get_instance();
    $CI->load->model('Account_fm');
    $CI->Account_fm->updateCityPdf($pdf_array);
    $CI->Account_fm->sendCronMail($pdf_array);*/
    /*if($key == 1)
    {
        break;
    }*/
    


}

    $CI =& get_instance();
    $CI->load->model('Account_fm');
    $CI->Account_fm->updateCityPdf($pdf_array);

    $CI->Account_fm->sendCronMail($email = '', $pdf_array);
    //echo "hello";die;
    /*print_r($pdf_array);die;
    if($key == 0)
    {
        echo "ff";die;
    }*/
}
//print_r($html);die;


// create some HTML content


// output the HTML content


// reset pointer to the last page
//$pdf->lastPage();


//$pdf->lastPage();
?>
