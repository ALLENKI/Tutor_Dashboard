<?php

namespace Aham\Http\Controllers\Frontend;

use Aham\Jobs\SendContactRequestMail;

use Validator;
use Input;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;


use Aham\Models\SQL\StudentCredits;


class InvoiceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($invoice)
    {
        $creditModel = StudentCredits::where('invoice_no',$invoice)->first();
        
        return view('frontend.home.invoice',compact('creditModel'));
    }

    /**
     * Capture the invoice as a PDF and return the raw bytes.
     *
     * @param  array  $data
     * @return string
     */
    public function pdf($invoice)
    {
        $creditModel = StudentCredits::where('invoice_no',$invoice)->first();

        if (! defined('DOMPDF_ENABLE_AUTOLOAD')) {
            define('DOMPDF_ENABLE_AUTOLOAD', false);
        }

        define('DOMPDF_ENABLE_REMOTE', true);


        if (file_exists($configPath = base_path().'/vendor/dompdf/dompdf/dompdf_config.inc.php')) {
            require_once $configPath;
        }
        $dompdf = new Dompdf;
        $dompdf->loadHtml(view('frontend.home.invoice',compact('creditModel'))->render());
        $dompdf->render();
        return $dompdf->output();
    }
    /**
     * Create an invoice download response.
     *
     * @param  array   $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download($invoice)
    {
        $creditModel = StudentCredits::where('invoice_no',$invoice)->first();

        $filename = $creditModel->invoice_no.'.pdf';
        return new Response($this->pdf($invoice), 200, [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'application/pdf',
        ]);
    }

}