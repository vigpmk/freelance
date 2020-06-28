<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\User;
use App\Dailysales;
use App\Weightedpoint;
use App\Team;
use App\TeamUser;
use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Mail;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('subscribed');

        // $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show()
    {
      if(Auth::user()->roleOn(Auth::user()->currentTeam)=='member' || Auth::user()->roleOn(Auth::user()->currentTeam)=='agent'){
        $currentYear = Carbon::now()->format('Y');
        $currentMonth = Carbon::now()->format('F');
        return redirect('home/dailysales/'.Auth::user()->id.'/'.$currentYear.'/'.$currentMonth);
      }
      return view('welcome');
    }

    public function dailysales($id,$year,$month)
    {
      $currentYear = $year;
      $currentMonth = Carbon::now()->format('F');
      $recordset = DB::select('select CAST(month AS UNSIGNED) as month from dailysalesactivityform where year='.$currentYear.' group by month order by month DESC');
      return view('dailysales',compact('currentMonth','currentYear','recordset'));
    }

    public function previoussales()
    {

      $currentYear = Carbon::now()->format('Y');
      $currentMonth = Carbon::now()->format('F');
      $NovemberMonth = Carbon::now()->subMonth(1)->format('F');
      $OctoberMonth = Carbon::now()->subMonth(2)->format('F');
      $SeptemberMonth = Carbon::now()->subMonth(3)->format('F');
      $AugestMonth = Carbon::now()->subMonth(4)->format('F');
      $JulyMonth = Carbon::now()->subMonth(5)->format('F');
      $JuneMonth = Carbon::now()->subMonth(6)->format('F');
      $MayMonth = Carbon::now()->subMonth(7)->format('F');
      $AprilMonth = Carbon::now()->subMonth(8)->format('F');
      $previousMonth = Carbon::now()->subMonth()->format('F');
      return view('previousmonth',compact('currentMonth','currentYear','previousMonth','NovemberMonth','OctoberMonth',
                                       'SeptemberMonth','AugestMonth',
                                       'JulyMonth','JuneMonth','MayMonth','AprilMonth'));

    }

    public function get_previousdaily_salesform(){

      $dt = Carbon::now();
      $obj=new Dailysales();
      $data['previousDailysales'] = $obj->getpreviousDailysales($dt);
      return $data;

    }
    public function get_daily_salesform(Request $request){

      $dt = Carbon::now();
      $obj=new Dailysales();
      $data['Dailysales'] = $obj->geDailysales($dt);
      return $data;

    }
    public function get_daily_salesform_byuser(Request $request){
      $data['Membername']='';
      $dt = Carbon::now();
      $obj=new Dailysales();
      $data['Dailysales'] = $obj->geDailysalesByuser($dt,$request);
      $data['Membername'] = User::find($request->user_id)->name;
      return $data;

    }

    public function get_previousdaily_salesform_byuser(Request $request){

      $data['Membername']='';
      $dt = Carbon::now();
      $obj=new Dailysales();
      $data['Dailysales'] = $obj->getpreviousDailysalesByuser($dt,$request);
      $data['Membername'] = User::find($request->user_id)->name;
      return $data;

    }

    public function upate_daily_salesform(Request $request){

      if ($request->session()->exists('user')) {
          echo 'session-not';
          return false;
      }

      $dt = Carbon::now();
      if(Dailysales::where('user_id',Auth::user()->id)
                        ->where('year',$dt->year)
                        ->where('month',$dt->month)
                        ->count() > 0)
      {

        Dailysales::where('user_id', Auth::user()->id)
                    ->where('year',$dt->year)
                    ->where('month',$dt->month)
                    ->update(['user_id'=>Auth::user()->id,
                              'year'=>$dt->year,
                              'month'=>$dt->month,
                              'dial'=>$request->dial1.'/'.$request->dial2.'/'.$request->dial3.'/'.$request->dial4.'/'.
                                      $request->dial5.'/'.$request->dial6.'/'.$request->dial7.'/'.$request->dial8.'/'.
                                      $request->dial9.'/'.$request->dial10.'/'.$request->dial11.'/'.$request->dial12.'/'.
                                      $request->dial13.'/'.$request->dial14.'/'.$request->dial15.'/'.$request->dial16.'/'.
                                      $request->dial17.'/'.$request->dial18.'/'.$request->dial19.'/'.$request->dial20.'/'.
                                      $request->dial21.'/'.$request->dial22.'/'.$request->dial23.'/'.$request->dial24.'/'.
                                      $request->dial25.'/'.$request->dial26.'/'.$request->dial27.'/'.$request->dial28.'/'.
                                      $request->dial29.'/'.$request->dial30.'/'.$request->dial31,

                            'speakwithsomeone'=>$request->speakwithsomeone1.'/'.$request->speakwithsomeone2.'/'.$request->speakwithsomeone3.'/'.
                                                $request->speakwithsomeone4.'/'.$request->speakwithsomeone5.'/'.$request->speakwithsomeone6.'/'.
                                                $request->speakwithsomeone7.'/'.$request->speakwithsomeone8.'/'.$request->speakwithsomeone9.'/'.
                                                $request->speakwithsomeone10.'/'.$request->speakwithsomeone11.'/'.$request->speakwithsomeone12.'/'.
                                                $request->speakwithsomeone13.'/'.$request->speakwithsomeone14.'/'.$request->speakwithsomeone15.'/'.
                                                $request->speakwithsomeone16.'/'.$request->speakwithsomeone17.'/'.$request->speakwithsomeone18.'/'.
                                                $request->speakwithsomeone19.'/'.$request->speakwithsomeone20.'/'.$request->speakwithsomeone21.'/'.
                                                $request->speakwithsomeone22.'/'.$request->speakwithsomeone23.'/'.$request->speakwithsomeone24.'/'.
                                                $request->speakwithsomeone25.'/'.$request->speakwithsomeone26.'/'.$request->speakwithsomeone27.'/'.
                                                $request->speakwithsomeone28.'/'.$request->speakwithsomeone29.'/'.$request->speakwithsomeone30.'/'.
                                                $request->speakwithsomeone31,

                            'speakwithexistingclient'=>$request->speakwithexistingclient1.'/'.$request->speakwithexistingclient2.'/'.$request->speakwithexistingclient3.'/'.
                                                       $request->speakwithexistingclient4.'/'.$request->speakwithexistingclient5.'/'.$request->speakwithexistingclient6.'/'.
                                                       $request->speakwithexistingclient7.'/'.$request->speakwithexistingclient8.'/'.$request->speakwithexistingclient9.'/'.
                                                       $request->speakwithexistingclient10.'/'.$request->speakwithexistingclient11.'/'.$request->speakwithexistingclient12.'/'.
                                                       $request->speakwithexistingclient13.'/'.$request->speakwithexistingclient14.'/'.$request->speakwithexistingclient15.'/'.
                                                       $request->speakwithexistingclient16.'/'.$request->speakwithexistingclient17.'/'.$request->speakwithexistingclient18.'/'.
                                                       $request->speakwithexistingclient19.'/'.$request->speakwithexistingclient20.'/'.$request->speakwithexistingclient21.'/'.
                                                       $request->speakwithexistingclient22.'/'.$request->speakwithexistingclient23.'/'.$request->speakwithexistingclient24.'/'.
                                                       $request->speakwithexistingclient25.'/'.$request->speakwithexistingclient26.'/'.$request->speakwithexistingclient27.'/'.
                                                       $request->speakwithexistingclient28.'/'.$request->speakwithexistingclient29.'/'.$request->speakwithexistingclient30.'/'.
                                                       $request->speakwithexistingclient31,

                             'forceno'=>$request->forceno1.'/'.$request->forceno2.'/'.$request->forceno3.'/'.$request->forceno4.'/'.$request->forceno5.'/'.$request->forceno6.'/'.
                                        $request->forceno7.'/'.$request->forceno8.'/'.$request->forceno9.'/'.$request->forceno10.'/'.$request->forceno11.'/'.$request->forceno12.'/'.
                                        $request->forceno13.'/'.$request->forceno14.'/'.$request->forceno15.'/'.$request->forceno16.'/'.$request->forceno17.'/'.$request->forceno18.'/'.
                                        $request->forceno19.'/'.$request->forceno20.'/'.$request->forceno21.'/'.$request->forceno22.'/'.$request->forceno23.'/'.$request->forceno24.'/'.
                                        $request->forceno25.'/'.$request->forceno26.'/'.$request->forceno27.'/'.$request->forceno28.'/'.$request->forceno29.'/'.$request->forceno30.'/'.
                                        $request->forceno31,

                            'setappnewprospect'=>$request->setappnewprospect1.'/'.$request->setappnewprospect2.'/'.$request->setappnewprospect3.'/'.$request->setappnewprospect4.'/'.
                                                 $request->setappnewprospect5.'/'.$request->setappnewprospect6.'/'.$request->setappnewprospect7.'/'.$request->setappnewprospect8.'/'.
                                                 $request->setappnewprospect9.'/'.$request->setappnewprospect10.'/'.$request->setappnewprospect11.'/'.$request->setappnewprospect12.'/'.
                                                 $request->setappnewprospect13.'/'.$request->setappnewprospect14.'/'.$request->setappnewprospect15.'/'.$request->setappnewprospect16.'/'.
                                                 $request->setappnewprospect17.'/'.$request->setappnewprospect18.'/'.$request->setappnewprospect19.'/'.$request->setappnewprospect20.'/'.
                                                 $request->setappnewprospect21.'/'.$request->setappnewprospect22.'/'.$request->setappnewprospect23.'/'.$request->setappnewprospect24.'/'.
                                                 $request->setappnewprospect25.'/'.$request->setappnewprospect26.'/'.$request->setappnewprospect27.'/'.$request->setappnewprospect28.'/'.
                                                 $request->setappnewprospect29.'/'.$request->setappnewprospect30.'/'.
                                                 $request->setappnewprospect31,

                           'receiveQIorreferal'=>$request->receiveQIorreferal1.'/'.$request->receiveQIorreferal2.'/'.$request->receiveQIorreferal3.'/'.$request->receiveQIorreferal4.'/'.$request->receiveQIorreferal5.'/'.
                                                 $request->receiveQIorreferal6.'/'.$request->receiveQIorreferal7.'/'.$request->receiveQIorreferal8.'/'.$request->receiveQIorreferal9.'/'.$request->receiveQIorreferal10.'/'.
                                                 $request->receiveQIorreferal11.'/'.$request->receiveQIorreferal12.'/'.$request->receiveQIorreferal13.'/'.$request->receiveQIorreferal14.'/'.$request->receiveQIorreferal15.'/'.
                                                 $request->receiveQIorreferal16.'/'.$request->receiveQIorreferal17.'/'.$request->receiveQIorreferal18.'/'.$request->receiveQIorreferal19.'/'.$request->receiveQIorreferal20.'/'.
                                                 $request->receiveQIorreferal21.'/'.$request->receiveQIorreferal22.'/'.$request->receiveQIorreferal23.'/'.$request->receiveQIorreferal24.'/'.$request->receiveQIorreferal25.'/'.
                                                 $request->receiveQIorreferal26.'/'.$request->receiveQIorreferal27.'/'.$request->receiveQIorreferal28.'/'.$request->receiveQIorreferal29.'/'.$request->receiveQIorreferal30.'/'.
                                                 $request->receiveQIorreferal31,

                            'givereferral'=>$request->givereferral1.'/'.$request->givereferral2.'/'.$request->givereferral3.'/'.$request->givereferral4.'/'.$request->givereferral5.'/'.
                                            $request->givereferral6.'/'.$request->givereferral7.'/'.$request->givereferral8.'/'.$request->givereferral9.'/'.$request->givereferral10.'/'.
                                            $request->givereferral11.'/'.$request->givereferral12.'/'.$request->givereferral13.'/'.$request->givereferral14.'/'.$request->givereferral15.'/'.
                                            $request->givereferral16.'/'.$request->givereferral17.'/'.$request->givereferral18.'/'.$request->givereferral19.'/'.$request->givereferral20.'/'.
                                            $request->givereferral21.'/'.$request->givereferral22.'/'.$request->givereferral23.'/'.$request->givereferral24.'/'.$request->givereferral25.'/'.
                                            $request->givereferral26.'/'.$request->givereferral27.'/'.$request->givereferral28.'/'.$request->givereferral29.'/'.$request->givereferral30.'/'.
                                            $request->givereferral31,

                            'giveQIfacetoface'=>$request->giveQIfacetoface1.'/'.$request->giveQIfacetoface2.'/'.$request->giveQIfacetoface3.'/'.$request->giveQIfacetoface4.'/'.$request->giveQIfacetoface5.'/'.
                                                $request->giveQIfacetoface6.'/'.$request->giveQIfacetoface7.'/'.$request->giveQIfacetoface8.'/'.$request->giveQIfacetoface9.'/'.$request->giveQIfacetoface10.'/'.
                                                $request->giveQIfacetoface11.'/'.$request->giveQIfacetoface12.'/'.$request->giveQIfacetoface13.'/'.$request->giveQIfacetoface14.'/'.$request->giveQIfacetoface15.'/'.
                                                $request->giveQIfacetoface16.'/'.$request->giveQIfacetoface17.'/'.$request->giveQIfacetoface18.'/'.$request->giveQIfacetoface19.'/'.$request->giveQIfacetoface20.'/'.
                                                $request->giveQIfacetoface21.'/'.$request->giveQIfacetoface22.'/'.$request->giveQIfacetoface23.'/'.$request->giveQIfacetoface24.'/'.$request->giveQIfacetoface25.'/'.
                                                $request->giveQIfacetoface26.'/'.$request->giveQIfacetoface27.'/'.$request->giveQIfacetoface28.'/'.$request->giveQIfacetoface29.'/'.$request->giveQIfacetoface30.'/'.
                                                $request->giveQIfacetoface31,

                            'meetingingwnewprospect'=>$request->meetingingwnewprospect1.'/'.$request->meetingingwnewprospect2.'/'.$request->meetingingwnewprospect3.'/'.$request->meetingingwnewprospect4.'/'.$request->meetingingwnewprospect5.'/'.
                                                   $request->meetingingwnewprospect6.'/'.$request->meetingingwnewprospect7.'/'.$request->meetingingwnewprospect8.'/'.$request->meetingingwnewprospect9.'/'.$request->meetingingwnewprospect10.'/'.
                                                   $request->meetingingwnewprospect11.'/'.$request->meetingingwnewprospect12.'/'.$request->meetingingwnewprospect13.'/'.$request->meetingingwnewprospect14.'/'.$request->meetingingwnewprospect15.'/'.
                                                   $request->meetingingwnewprospect16.'/'.$request->meetingingwnewprospect17.'/'.$request->meetingingwnewprospect18.'/'.$request->meetingingwnewprospect19.'/'.$request->meetingingwnewprospect20.'/'.
                                                   $request->meetingingwnewprospect21.'/'.$request->meetingingwnewprospect22.'/'.$request->meetingingwnewprospect23.'/'.$request->meetingingwnewprospect24.'/'.$request->meetingingwnewprospect25.'/'.
                                                   $request->meetingingwnewprospect26.'/'.$request->meetingingwnewprospect27.'/'.$request->meetingingwnewprospect28.'/'.$request->meetingingwnewprospect29.'/'.$request->meetingingwnewprospect30.'/'.
                                                   $request->meetingingwnewprospect31,

                           'strategicPartnerMfg'=>$request->strategicPartnerMfg1.'/'.$request->strategicPartnerMfg2.'/'.$request->strategicPartnerMfg3.'/'.$request->strategicPartnerMfg4.'/'.$request->strategicPartnerMfg5.'/'.
                                                  $request->strategicPartnerMfg6.'/'.$request->strategicPartnerMfg7.'/'.$request->strategicPartnerMfg8.'/'.$request->strategicPartnerMfg9.'/'.$request->strategicPartnerMfg10.'/'.
                                                  $request->strategicPartnerMfg11.'/'.$request->strategicPartnerMfg12.'/'.$request->strategicPartnerMfg13.'/'.$request->strategicPartnerMfg14.'/'.$request->strategicPartnerMfg15.'/'.
                                                  $request->strategicPartnerMfg16.'/'.$request->strategicPartnerMfg17.'/'.$request->strategicPartnerMfg18.'/'.$request->strategicPartnerMfg19.'/'.$request->strategicPartnerMfg20.'/'.
                                                  $request->strategicPartnerMfg21.'/'.$request->strategicPartnerMfg22.'/'.$request->strategicPartnerMfg23.'/'.$request->strategicPartnerMfg24.'/'.$request->strategicPartnerMfg25.'/'.
                                                  $request->strategicPartnerMfg26.'/'.$request->strategicPartnerMfg27.'/'.$request->strategicPartnerMfg28.'/'.$request->strategicPartnerMfg29.'/'.$request->strategicPartnerMfg30.'/'.
                                                  $request->strategicPartnerMfg31,

                            'closes'=>$request->closes1.'/'.$request->closes2.'/'.$request->closes3.'/'.$request->closes4.'/'.$request->closes5.'/'.$request->closes6.'/'.
                                      $request->closes7.'/'.$request->closes8.'/'.$request->closes9.'/'.$request->closes10.'/'.$request->closes11.'/'.$request->closes12.'/'.
                                      $request->closes13.'/'.$request->closes14.'/'.$request->closes15.'/'.$request->closes16.'/'.$request->closes17.'/'.$request->closes18.'/'.
                                      $request->closes19.'/'.$request->closes20.'/'.$request->closes21.'/'.$request->closes22.'/'.$request->closes23.'/'.$request->closes24.'/'.
                                      $request->closes25.'/'.$request->closes26.'/'.$request->closes27.'/'.$request->closes28.'/'.$request->closes29.'/'.$request->closes30.'/'.
                                      $request->closes31,

                           'totalptspermonth'=>$request->totalptspermonth1.'/'.$request->totalptspermonth2.'/'.$request->totalptspermonth3.'/'.$request->totalptspermonth4.'/'.$request->totalptspermonth5.'/'.
                                              $request->totalptspermonth6.'/'.$request->totalptspermonth7.'/'.$request->totalptspermonth8.'/'.$request->totalptspermonth9.'/'.$request->totalptspermonth10.'/'.
                                              $request->totalptspermonth11.'/'.$request->totalptspermonth12.'/'.$request->totalptspermonth13.'/'.$request->totalptspermonth14.'/'.$request->totalptspermonth15.'/'.
                                              $request->totalptspermonth16.'/'.$request->totalptspermonth17.'/'.$request->totalptspermonth18.'/'.$request->totalptspermonth19.'/'.$request->totalptspermonth20.'/'.
                                              $request->totalptspermonth21.'/'.$request->totalptspermonth22.'/'.$request->totalptspermonth23.'/'.$request->totalptspermonth24.'/'.$request->totalptspermonth25.'/'.
                                              $request->totalptspermonth26.'/'.$request->totalptspermonth27.'/'.$request->totalptspermonth28.'/'.$request->totalptspermonth29.'/'.$request->totalptspermonth30.'/'.
                                              $request->totalptspermonth31,

                          'monthtotal'=>$request->monthtotal1.'/'.$request->monthtotal2.'/'.$request->monthtotal3.'/'.$request->monthtotal4.'/'.$request->monthtotal5.'/'.
                                        $request->monthtotal6.'/'.$request->monthtotal7.'/'.$request->monthtotal8.'/'.$request->monthtotal9.'/'.$request->monthtotal10.'/'.
                                        $request->monthtotal11.'/'.$request->monthtotal12,

                          'closingEqucation'=>$request->closingEqucation1.'/'.$request->closingEqucation2.'/'.$request->closingEqucation3.'/'.$request->closingEqucation4.'/'.
                                              $request->closingEqucation5.'/'.$request->closingEqucation6.'/'.$request->closingEqucation7.'/'.$request->closingEqucation8
                            ]);

    }
    else
    {
      Dailysales::create(['user_id'=>Auth::user()->id,
                          'year'=>$dt->year,
                          'month'=>$dt->month,
                          'dial'=>$request->dial1.'/'.$request->dial2.'/'.$request->dial3.'/'.$request->dial4.'/'.
                                  $request->dial5.'/'.$request->dial6.'/'.$request->dial7.'/'.$request->dial8.'/'.
                                  $request->dial9.'/'.$request->dial10.'/'.$request->dial11.'/'.$request->dial12.'/'.
                                  $request->dial13.'/'.$request->dial14.'/'.$request->dial15.'/'.$request->dial16.'/'.
                                  $request->dial17.'/'.$request->dial18.'/'.$request->dial19.'/'.$request->dial20.'/'.
                                  $request->dial21.'/'.$request->dial22.'/'.$request->dial23.'/'.$request->dial24.'/'.
                                  $request->dial25.'/'.$request->dial26.'/'.$request->dial27.'/'.$request->dial28.'/'.
                                  $request->dial29.'/'.$request->dial30.'/'.$request->dial31,

                        'speakwithsomeone'=>$request->speakwithsomeone1.'/'.$request->speakwithsomeone2.'/'.$request->speakwithsomeone3.'/'.
                                            $request->speakwithsomeone4.'/'.$request->speakwithsomeone5.'/'.$request->speakwithsomeone6.'/'.
                                            $request->speakwithsomeone7.'/'.$request->speakwithsomeone8.'/'.$request->speakwithsomeone9.'/'.
                                            $request->speakwithsomeone10.'/'.$request->speakwithsomeone11.'/'.$request->speakwithsomeone12.'/'.
                                            $request->speakwithsomeone13.'/'.$request->speakwithsomeone14.'/'.$request->speakwithsomeone15.'/'.
                                            $request->speakwithsomeone16.'/'.$request->speakwithsomeone17.'/'.$request->speakwithsomeone18.'/'.
                                            $request->speakwithsomeone19.'/'.$request->speakwithsomeone20.'/'.$request->speakwithsomeone21.'/'.
                                            $request->speakwithsomeone22.'/'.$request->speakwithsomeone23.'/'.$request->speakwithsomeone24.'/'.
                                            $request->speakwithsomeone25.'/'.$request->speakwithsomeone26.'/'.$request->speakwithsomeone27.'/'.
                                            $request->speakwithsomeone28.'/'.$request->speakwithsomeone29.'/'.$request->speakwithsomeone30.'/'.
                                            $request->speakwithsomeone31,

                        'speakwithexistingclient'=>$request->speakwithexistingclient1.'/'.$request->speakwithexistingclient2.'/'.$request->speakwithexistingclient3.'/'.
                                                   $request->speakwithexistingclient4.'/'.$request->speakwithexistingclient5.'/'.$request->speakwithexistingclient6.'/'.
                                                   $request->speakwithexistingclient7.'/'.$request->speakwithexistingclient8.'/'.$request->speakwithexistingclient9.'/'.
                                                   $request->speakwithexistingclient10.'/'.$request->speakwithexistingclient11.'/'.$request->speakwithexistingclient12.'/'.
                                                   $request->speakwithexistingclient13.'/'.$request->speakwithexistingclient14.'/'.$request->speakwithexistingclient15.'/'.
                                                   $request->speakwithexistingclient16.'/'.$request->speakwithexistingclient17.'/'.$request->speakwithexistingclient18.'/'.
                                                   $request->speakwithexistingclient19.'/'.$request->speakwithexistingclient20.'/'.$request->speakwithexistingclient21.'/'.
                                                   $request->speakwithexistingclient22.'/'.$request->speakwithexistingclient23.'/'.$request->speakwithexistingclient24.'/'.
                                                   $request->speakwithexistingclient25.'/'.$request->speakwithexistingclient26.'/'.$request->speakwithexistingclient27.'/'.
                                                   $request->speakwithexistingclient28.'/'.$request->speakwithexistingclient29.'/'.$request->speakwithexistingclient30.'/'.
                                                   $request->speakwithexistingclient31,

                         'forceno'=>$request->forceno1.'/'.$request->forceno2.'/'.$request->forceno3.'/'.$request->forceno4.'/'.$request->forceno5.'/'.$request->forceno6.'/'.
                                    $request->forceno7.'/'.$request->forceno8.'/'.$request->forceno9.'/'.$request->forceno10.'/'.$request->forceno11.'/'.$request->forceno12.'/'.
                                    $request->forceno13.'/'.$request->forceno14.'/'.$request->forceno15.'/'.$request->forceno16.'/'.$request->forceno17.'/'.$request->forceno18.'/'.
                                    $request->forceno19.'/'.$request->forceno20.'/'.$request->forceno21.'/'.$request->forceno22.'/'.$request->forceno23.'/'.$request->forceno24.'/'.
                                    $request->forceno25.'/'.$request->forceno26.'/'.$request->forceno27.'/'.$request->forceno28.'/'.$request->forceno29.'/'.$request->forceno30.'/'.
                                    $request->forceno31,

                        'setappnewprospect'=>$request->setappnewprospect1.'/'.$request->setappnewprospect2.'/'.$request->setappnewprospect3.'/'.$request->setappnewprospect4.'/'.
                                             $request->setappnewprospect5.'/'.$request->setappnewprospect6.'/'.$request->setappnewprospect7.'/'.$request->setappnewprospect8.'/'.
                                             $request->setappnewprospect9.'/'.$request->setappnewprospect10.'/'.$request->setappnewprospect11.'/'.$request->setappnewprospect12.'/'.
                                             $request->setappnewprospect13.'/'.$request->setappnewprospect14.'/'.$request->setappnewprospect15.'/'.$request->setappnewprospect16.'/'.
                                             $request->setappnewprospect17.'/'.$request->setappnewprospect18.'/'.$request->setappnewprospect19.'/'.$request->setappnewprospect20.'/'.
                                             $request->setappnewprospect21.'/'.$request->setappnewprospect22.'/'.$request->setappnewprospect23.'/'.$request->setappnewprospect24.'/'.
                                             $request->setappnewprospect25.'/'.$request->setappnewprospect26.'/'.$request->setappnewprospect27.'/'.$request->setappnewprospect28.'/'.
                                             $request->setappnewprospect29.'/'.$request->setappnewprospect30.'/'.$request->setappnewprospect31,

                       'receiveQIorreferal'=>$request->receiveQIorreferal1.'/'.$request->receiveQIorreferal2.'/'.$request->receiveQIorreferal3.'/'.$request->receiveQIorreferal4.'/'.$request->receiveQIorreferal5.'/'.
                                             $request->receiveQIorreferal6.'/'.$request->receiveQIorreferal7.'/'.$request->receiveQIorreferal8.'/'.$request->receiveQIorreferal9.'/'.$request->receiveQIorreferal10.'/'.
                                             $request->receiveQIorreferal11.'/'.$request->receiveQIorreferal12.'/'.$request->receiveQIorreferal13.'/'.$request->receiveQIorreferal14.'/'.$request->receiveQIorreferal15.'/'.
                                             $request->receiveQIorreferal16.'/'.$request->receiveQIorreferal17.'/'.$request->receiveQIorreferal18.'/'.$request->receiveQIorreferal19.'/'.$request->receiveQIorreferal20.'/'.
                                             $request->receiveQIorreferal21.'/'.$request->receiveQIorreferal22.'/'.$request->receiveQIorreferal23.'/'.$request->receiveQIorreferal24.'/'.$request->receiveQIorreferal25.'/'.
                                             $request->receiveQIorreferal26.'/'.$request->receiveQIorreferal27.'/'.$request->receiveQIorreferal28.'/'.$request->receiveQIorreferal29.'/'.$request->receiveQIorreferal30.'/'.
                                             $request->receiveQIorreferal31,

                        'givereferral'=>$request->givereferral1.'/'.$request->givereferral2.'/'.$request->givereferral3.'/'.$request->givereferral4.'/'.$request->givereferral5.'/'.
                                        $request->givereferral6.'/'.$request->givereferral7.'/'.$request->givereferral8.'/'.$request->givereferral9.'/'.$request->givereferral10.'/'.
                                        $request->givereferral11.'/'.$request->givereferral12.'/'.$request->givereferral13.'/'.$request->givereferral14.'/'.$request->givereferral15.'/'.
                                        $request->givereferral16.'/'.$request->givereferral17.'/'.$request->givereferral18.'/'.$request->givereferral19.'/'.$request->givereferral20.'/'.
                                        $request->givereferral21.'/'.$request->givereferral22.'/'.$request->givereferral23.'/'.$request->givereferral24.'/'.$request->givereferral25.'/'.
                                        $request->givereferral26.'/'.$request->givereferral27.'/'.$request->givereferral28.'/'.$request->givereferral29.'/'.$request->givereferral30.'/'.
                                        $request->givereferral31,

                        'giveQIfacetoface'=>$request->giveQIfacetoface1.'/'.$request->giveQIfacetoface2.'/'.$request->giveQIfacetoface3.'/'.$request->giveQIfacetoface4.'/'.$request->giveQIfacetoface5.'/'.
                                            $request->giveQIfacetoface6.'/'.$request->giveQIfacetoface7.'/'.$request->giveQIfacetoface8.'/'.$request->giveQIfacetoface9.'/'.$request->giveQIfacetoface10.'/'.
                                            $request->giveQIfacetoface11.'/'.$request->giveQIfacetoface12.'/'.$request->giveQIfacetoface13.'/'.$request->giveQIfacetoface14.'/'.$request->giveQIfacetoface15.'/'.
                                            $request->giveQIfacetoface16.'/'.$request->giveQIfacetoface17.'/'.$request->giveQIfacetoface18.'/'.$request->giveQIfacetoface19.'/'.$request->giveQIfacetoface20.'/'.
                                            $request->giveQIfacetoface21.'/'.$request->giveQIfacetoface22.'/'.$request->giveQIfacetoface23.'/'.$request->giveQIfacetoface24.'/'.$request->giveQIfacetoface25.'/'.
                                            $request->giveQIfacetoface26.'/'.$request->giveQIfacetoface27.'/'.$request->giveQIfacetoface28.'/'.$request->giveQIfacetoface29.'/'.$request->giveQIfacetoface30.'/'.
                                            $request->giveQIfacetoface31,

                        'meetingingwnewprospect'=>$request->meetingingwnewprospect1.'/'.$request->meetingingwnewprospect2.'/'.$request->meetingingwnewprospect3.'/'.$request->meetingingwnewprospect4.'/'.$request->meetingingwnewprospect5.'/'.
                                               $request->meetingingwnewprospect6.'/'.$request->meetingingwnewprospect7.'/'.$request->meetingingwnewprospect8.'/'.$request->meetingingwnewprospect9.'/'.$request->meetingingwnewprospect10.'/'.
                                               $request->meetingingwnewprospect11.'/'.$request->meetingingwnewprospect12.'/'.$request->meetingingwnewprospect13.'/'.$request->meetingingwnewprospect14.'/'.$request->meetingingwnewprospect15.'/'.
                                               $request->meetingingwnewprospect16.'/'.$request->meetingingwnewprospect17.'/'.$request->meetingingwnewprospect18.'/'.$request->meetingingwnewprospect19.'/'.$request->meetingingwnewprospect20.'/'.
                                               $request->meetingingwnewprospect21.'/'.$request->meetingingwnewprospect22.'/'.$request->meetingingwnewprospect23.'/'.$request->meetingingwnewprospect24.'/'.$request->meetingingwnewprospect25.'/'.
                                               $request->meetingingwnewprospect26.'/'.$request->meetingingwnewprospect27.'/'.$request->meetingingwnewprospect28.'/'.$request->meetingingwnewprospect29.'/'.$request->meetingingwnewprospect30.'/'.
                                               $request->meetingingwnewprospect31,

                       'strategicPartnerMfg'=>$request->strategicPartnerMfg1.'/'.$request->strategicPartnerMfg2.'/'.$request->strategicPartnerMfg3.'/'.$request->strategicPartnerMfg4.'/'.$request->strategicPartnerMfg5.'/'.
                                              $request->strategicPartnerMfg6.'/'.$request->strategicPartnerMfg7.'/'.$request->strategicPartnerMfg8.'/'.$request->strategicPartnerMfg9.'/'.$request->strategicPartnerMfg10.'/'.
                                              $request->strategicPartnerMfg11.'/'.$request->strategicPartnerMfg12.'/'.$request->strategicPartnerMfg13.'/'.$request->strategicPartnerMfg14.'/'.$request->strategicPartnerMfg15.'/'.
                                              $request->strategicPartnerMfg16.'/'.$request->strategicPartnerMfg17.'/'.$request->strategicPartnerMfg18.'/'.$request->strategicPartnerMfg19.'/'.$request->strategicPartnerMfg20.'/'.
                                              $request->strategicPartnerMfg21.'/'.$request->strategicPartnerMfg22.'/'.$request->strategicPartnerMfg23.'/'.$request->strategicPartnerMfg24.'/'.$request->strategicPartnerMfg25.'/'.
                                              $request->strategicPartnerMfg26.'/'.$request->strategicPartnerMfg27.'/'.$request->strategicPartnerMfg28.'/'.$request->strategicPartnerMfg29.'/'.$request->strategicPartnerMfg30.'/'.
                                              $request->strategicPartnerMfg31,

                        'closes'=>$request->closes1.'/'.$request->closes2.'/'.$request->closes3.'/'.$request->closes4.'/'.$request->closes5.'/'.$request->closes6.'/'.
                                  $request->closes7.'/'.$request->closes8.'/'.$request->closes9.'/'.$request->closes10.'/'.$request->closes11.'/'.$request->closes12.'/'.
                                  $request->closes13.'/'.$request->closes14.'/'.$request->closes15.'/'.$request->closes16.'/'.$request->closes17.'/'.$request->closes18.'/'.
                                  $request->closes19.'/'.$request->closes20.'/'.$request->closes21.'/'.$request->closes22.'/'.$request->closes23.'/'.$request->closes24.'/'.
                                  $request->closes25.'/'.$request->closes26.'/'.$request->closes27.'/'.$request->closes28.'/'.$request->closes29.'/'.$request->closes30.'/'.
                                  $request->closes31,

                       'totalptspermonth'=>$request->totalptspermonth1.'/'.$request->totalptspermonth2.'/'.$request->totalptspermonth3.'/'.$request->totalptspermonth4.'/'.$request->totalptspermonth5.'/'.
                                          $request->totalptspermonth6.'/'.$request->totalptspermonth7.'/'.$request->totalptspermonth8.'/'.$request->totalptspermonth9.'/'.$request->totalptspermonth10.'/'.
                                          $request->totalptspermonth11.'/'.$request->totalptspermonth12.'/'.$request->totalptspermonth13.'/'.$request->totalptspermonth14.'/'.$request->totalptspermonth15.'/'.
                                          $request->totalptspermonth16.'/'.$request->totalptspermonth17.'/'.$request->totalptspermonth18.'/'.$request->totalptspermonth19.'/'.$request->totalptspermonth20.'/'.
                                          $request->totalptspermonth21.'/'.$request->totalptspermonth22.'/'.$request->totalptspermonth23.'/'.$request->totalptspermonth24.'/'.$request->totalptspermonth25.'/'.
                                          $request->totalptspermonth26.'/'.$request->totalptspermonth27.'/'.$request->totalptspermonth28.'/'.$request->totalptspermonth29.'/'.$request->totalptspermonth30.'/'.
                                          $request->totalptspermonth31,

                      'monthtotal'=>$request->monthtotal1.'/'.$request->monthtotal2.'/'.$request->monthtotal3.'/'.$request->monthtotal4.'/'.$request->monthtotal5.'/'.
                                    $request->monthtotal6.'/'.$request->monthtotal7.'/'.$request->monthtotal8.'/'.$request->monthtotal9.'/'.$request->monthtotal10.'/'.
                                    $request->monthtotal11.'/'.$request->monthtotal12,

                      'closingEqucation'=>$request->closingEqucation1.'/'.$request->closingEqucation2.'/'.$request->closingEqucation3.'/'.$request->closingEqucation4.'/'.
                                          $request->closingEqucation5.'/'.$request->closingEqucation6.'/'.$request->closingEqucation7.'/'.$request->closingEqucation8]);

    }

  }

  public function get_weighted_pointvalue(Request $request){

    $obj=new Weightedpoint();
    $data['Weightedpoint'] = $obj->getWeightedpoint();
    return $data;

  }

  public function upate_weighted_pointvalue(Request $request){

    $noofRows=Weightedpoint::count();
    if($noofRows>0){
      Weightedpoint::truncate();
      $obj = new Weightedpoint();
      $obj->fill($request->all());
      $obj->save();
    }else{
      $obj = new Weightedpoint();
      $obj->fill($request->all());
      $obj->save();
    }

  }

  public function companyregister(Request $request){

    $rules = [
      'team'=>'required',
      'name'=>'required',
      'email'=>'required|email|unique:users,email',
      'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
      'password_confirmation' => 'min:6',
    ];

    $customMessages = [
        'team.required' => 'The company name field is required.',
        'name.required' => 'The name field is required',
    ];

    $this->validate($request, $rules, $customMessages);

    $user = Spark::user();

    $user->forceFill([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'last_read_announcements_at' => Carbon::now(),
        'trial_ends_at' => Carbon::now()->addDays(Spark::trialDays()),
        'status'=>1
    ])->save();


    $Team=new Team();
    $Team->owner_id=$user['id'];
    $Team->name=$request->team;
    $Team->trial_ends_at=Carbon::now()->addDays(Spark::trialDays());
    $Team->save();

    $Teamuser = new TeamUser();
    $Teamuser->team_id=$Team->id;
    $Teamuser->user_id=$user['id'];
    $Teamuser->role='owner';
    $Teamuser->save();

    $obj = User::find($user->id);
    $obj->current_team_id=$Teamuser->id;
    $obj->save();

    Mail::send('email.add-company-email', ['Companyname'=>$request->team,
                                       'emailcontent'=>'Your company account has been created and is now active. Please visit the below URL and reset your password by using your email id.',
                                       'baseurl'=>url('/password/reset')], function ($message) use ($request) {
        $message->to($request->email)->subject(__('Your Company account has been created by Sales Acceleration Academy!!!'));
    });

  }




}
