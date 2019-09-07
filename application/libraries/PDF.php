<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
/*class PDF extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}*/
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;

require_once dirname(__FILE__) . '/dompdf/autoload.inc.php';
class PDF extends DOMPDF
{
    function __construct()
    {
        parent::__construct();
    }
}
/*Author:Tutsway.com */  
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */
?>