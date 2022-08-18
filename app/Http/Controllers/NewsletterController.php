<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    private $serviceObj;

    public function __construct()
    {
      $this->serviceObj  = new NewsletterService();
    }

    public function addNewReceiver(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:11',
            'full_name' => 'required|string'
        ]);
        $parametersObject = [
            'full_name'=>$request->full_name,
            'phone_number'=>$request->phone_number,
    ];
       return $this->serviceObj->addUserToNewslettersReceivers($parametersObject);
    }
    public function showAllReceivers()
    {
        return $this->serviceObj->showNewslettersReceivers();
    }
}
