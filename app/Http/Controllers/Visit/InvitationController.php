<?php
namespace App\Http\Controllers\Visit;

use Log;
use DB;
use Auth;
use File;
use QrCode;
use Carbon\Carbon;
use App\Models\Visitor;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InvitationController extends Controller
{
    public function index(Request $request) {

        return view('visit.invitation');
    }

    /*
     * post check and return json
     */
    public function check_code(Request $request){

        $ret = ['status' => 0];
        $code = $request->input('code');
        $visitor = Visitor::where('code', '=', $code)->first();
        if(!empty($visitor)){
            $ret['status'] = 1;// 存在
            $ret['visitor'] = json_encode($visitor);
            $ret['url'] = action('Visit\InvitationController@visitor', ['code' => $visitor->code]);
        }

        return response()->json($ret);
    }

    public function visitor(Request $request, $code){

        $code = trim(strtoupper($code));

        $visitor = Visitor::where(['code' => $code])->first();
        if($visitor){
            $visitor->is_entered = empty($visitor->entered_at) ? 0 : 1;
            $qr_url = action('Visit\InvitationController@entry_visitor', ['code' => $code]);
            $dir = '/files/visit/visitor/'.$visitor->id;
            $qr = $dir.'/qr.png';
            $qr_file = $dir.'/invitation.jpg';
            $qr_i = $dir.'/qr_invitation.jpg';
            if(!File::exists(public_path(ltrim($dir, '/')))){
                File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
            }
            if($visitor->level == 1){
                $yqh_name = 'yaoqinghan_vip.jpg';
            }else{
                $yqh_name = 'yaoqinghan.jpg';
            }
            File::copy(public_path('/assets/img/visit.d/'.$yqh_name),
                       public_path($qr_file));
            QrCode::format('png')->margin(1)
                ->size(185)
                ->generate($qr_url, public_path($qr));
            $qr_file_open = imagecreatefromjpeg(public_path($qr_file));
            $qr_open = imagecreatefrompng(public_path($qr));
            imagecopymerge($qr_file_open, $qr_open, 230, 720, 0, 0, 185, 185, 100);
            imagejpeg($qr_file_open, public_path($qr_i), 75);
            imagedestroy($qr_file_open);
            imagedestroy($qr_open);
            return view('visit.visitor', compact(['visitor', 'qr_i']));
        }
    }

    public function entry_visitor(Request $request, $code){

        $code = trim(strtoupper($code));
        $visitor = Visitor::where(['code' => $code])->first();
        if($visitor){
            $visitor->is_entered = empty($visitor->entered_at) ? 0 : 1;

            $dir = '/files/visit/visitor/'.$visitor->id;
            $qr_i = $dir.'/qr_invitation.jpg';

            $user = Auth::guard('admin')->user();
            if($user && $user->can('visit-scan')){
                $visitor->entered_at = Carbon::now();
                $tmp = $visitor->is_entered;
                unset($visitor->is_entered);
                $visitor->update();
                $visitor->is_entered = $tmp;

                return view('visit.entry_visitor', ['visitor' => $visitor]);
            }else{

                return view('visit.visitor', compact(['visitor', 'qr_i']));
            }

        }
    }
    
}