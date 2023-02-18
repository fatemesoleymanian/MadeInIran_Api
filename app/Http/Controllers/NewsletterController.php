<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function distinctHundred()
    {
//        return DB::table('newsletters')->distinct()->count('phone_number');
        return Newsletter::query()->select('phone_number', 'full_name')->distinct()->paginate(100);
    }
}
