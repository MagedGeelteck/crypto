<?php

namespace App\Http\Controllers\User;

use App\Models\Sell;
use App\Models\Rating;
use App\Models\Product;
use App\Constants\Status;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Validation\Rule;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();
        $completedOrders = Sell::delivered()->where('user_id', $user->id)->groupBy('code')->get()->count();
        $processingOrders = Sell::processing()->where('user_id', $user->id)->groupBy('code')->get()->count();
        $supportTickets = SupportTicket::where('user_id', $user->id)->count();
        return view('Template::user.dashboard', compact('pageTitle', 'completedOrders', 'processingOrders', 'supportTickets'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;


        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function purchaseLog()
    {
        $pageTitle = 'Product Purchase Log';
        $products = Sell::selectRaw('*, SUM(total_price) as total_amount')
            ->where('user_id', auth()->user()->id)
            ->groupBy('code')->latest()
            ->paginate(getPaginate());
        return view('Template::user.purchase', compact('pageTitle', 'products'));
    }

    public function orderDetails($code)
    {
        $orders = Sell::where('code', $code)->with(['product', 'user'])->paginate(getPaginate());
        $pageTitle = 'Order Code - [' . $code . ']';

        return view('Template::user.order_details', compact('pageTitle', 'orders'));
    }

    public function productDownload($id)
    {
        $product = Product::findOrFail(Crypt::decrypt($id));

        $file = $product->product_file;
        $full_path = getFilePath('productFile') . '/' . $file;
        $title = str_replace(' ', '_', strtolower($product->name));
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function rating(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|gt:0|max:5',
            'product_id' => 'required|integer|gt:0',
            'review' => 'required',
        ]);

        $product = Sell::where('product_id', $request->product_id)->where('user_id', auth()->user()->id)->first();
        $user = auth()->user();

        if ($product == null) {
            $notify[] = ['error', 'Something went wrong'];
            return back()->withNotify($notify);
        }

        $rating = new Rating();
        $rating->product_id = $request->product_id;
        $rating->user_id = $user->id;
        $rating->rating = $request->rating;
        $rating->review = $request->review;
        $rating->save();

        $totalRatingProduct = $product->product->total_rating + $request->rating;
        $totalResponseProduct = $product->product->total_response + 1;
        $avgRatingProduct = round($totalRatingProduct / $totalResponseProduct);

        $product->product->total_rating = $totalRatingProduct;
        $product->product->total_response = $totalResponseProduct;
        $product->product->avg_rating = $avgRatingProduct;
        $product->product->save();

        $notify[] = ['success', 'Thanks for your review'];
        return back()->withNotify($notify);
    }
}
