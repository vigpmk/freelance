<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\User;
use Session;
use Illuminate\Support\Facades\Mail;

class SparkController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth:api');
  }

  public function companyapproval($id){

    $obj=User::find($id);
    $obj->status=1;
    $obj->save();

    Mail::send('email.company_email', ['Companyname'=>$obj->name,
                                              'emailcontent'=>'Your Company account has been approved and activated.
                                                              Please visit our site to log in with your email and password that you registered.
                                                              ',
                                               'baseurl'=>url('/login')], function ($message) use ($obj) {
        $message->to($obj->email)->subject(__('Congratulations! Your Company account has been approved by SAA.'));
    });

    Session::flash('alert-info', 'info');
    Session::flash('message', "Company has been approved.");
    return Redirect::back();

  }

  public function declineUser(Request $request){

    $delet_user=User::find($request->user_id);
    $delet_user->status=3;
    $delet_user->save();

    Mail::send('email.company_email', ['Companyname'=>$delet_user->name,
                                       'emailcontent'=>$request->Command,
                                       'baseurl'=>url('/login')], function ($message) use ($delet_user) {
    $message->to($delet_user->email)->subject(__('Your Company account has been declined.'));
    });

  }



}
