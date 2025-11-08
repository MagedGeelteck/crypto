<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Rating;
use App\Models\Product;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Constants\Status;
use App\Models\Subscriber;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;


class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }


    public function contactSubmit(Request $request)
    {
        // Deprecated in favor of support ticket form; keep as fallback if posted directly
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if (!auth()->check()) {
            return to_route('user.login');
        }

        $request->session()->regenerateToken();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id();
        $ticket->name = auth()->user()->fullname;
        $ticket->username = auth()->user()->username;
        $ticket->priority = Status::PRIORITY_MEDIUM;
        $ticket->ticket = getNumber();
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->id();
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        $pageTitle = 'Blogs';
        $blogElements = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'blogs')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::blog', compact('pageTitle', 'blogElements','sections', 'seoContents', 'seoImage'));
    }

    public function blogDetails($slug)
    {
        $pageTitle = 'Blog Details';
        $blog = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $blogElements = Frontend::where('data_keys', 'blog.element')->where('id', '!=', $blog->id)->limit(10)->latest()->get();
        $seoContents = $blog->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'blogElements'));
    }

    public function products(Request $request)
    {
        $pageTitle = 'Products';

        $products = Product::active()->searchable(['name','category:name','subcategory:name']);
        if (@$request->search_c > 0) {
            $products = $products->where('category_id', $request->search_c);
        }

        $products = $products->with('category', 'subcategory')->latest()->paginate(getPaginate());

        $categories   = Category::active()->with(['subcategories', 'products', 'products.subcategory'])->latest()->get();

        $min = $products->min('new_price');
        $max = $products->max('new_price');

        $sections = Page::where('tempname', activeTemplate())->where('slug', 'products')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::product', compact('pageTitle', 'products', 'categories', 'min', 'max','sections', 'seoContents', 'seoImage'));
    }

    public function productDetails($id, $slug)
    {
        $pageTitle = 'Products Details';
        $product = Product::active()->with('category.products.subcategory')->findOrFail($id);
        $relatedProducts = Product::active()->where('category_id',$product->category_id)->where('id','=!',$product->id)->with('subcategory')->latest()->limit(12)->get();
        $ratings = Rating::where('product_id', $product->id)->with('user')->limit(6)->get();
        $offerElements = getContent('home_page_offer.element', false);
        $categories  = Category::with(['subcategories', 'products', 'products.subcategory'])->latest()->get();
        return view('Template::product_details', compact('pageTitle', 'product', 'offerElements', 'ratings', 'categories','relatedProducts'));
    }


    public function loadMoreProducts(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'count' => 'required|integer|gt:0',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'sortby' => 'nullable|min:0|max:4'
        ]);

        if($validate->fails()){
            return response()->json(['error' => $validate->errors()]);
        }

        $min = $request->min;
        $max = $request->max;
        $sortBy = $request->sortby;

        $products = Product::active()->where('new_price', '>=', $min)->where('new_price', '<=' ,$max);

        if($sortBy == 0){
            $products = $products->orderBy('id');
        }elseif($sortBy == 1){
            $products = $products->latest();
        }elseif($sortBy == 2){
            $products = $products->orderBy('new_price');
        }elseif($sortBy == 3){
            $products = $products->orderBy('new_price','desc');
        }elseif($sortBy == 4){
            $products = $products->orderBy('avg_rating','desc');
        }else{
            $products = $products;
        }

        $products = $products->with('subcategory')->skip($request->count)->limit(9)->get();

        $view = view('Template::pagination_search',compact('products'))->render();
        return response()->json([
            'html' => $view,
            'productCount' => count($products),
         ]);
    }


    public function productFilter(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'min'       => 'required|numeric',
            'max'       => 'required|numeric',
            'sortby'    => 'nullable|integer|min:0|max:4',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()]);
        }

        $min = $request->min;
        $max = $request->max;
        $sortBy = $request->sortby;

        $products = Product::active()->searchable(['name','category:name','subcategory:name'])->where('new_price', '>=', $min)->where('new_price', '<=', $max);

        if ($request->search_c) {
            $products = $products->where('category_id', $request->search_c);
        }

        if ($sortBy == 0) {
            $products = $products->orderBy('id');
        } elseif ($sortBy == 1) {
            $products = $products->latest();
        } elseif ($sortBy == 2) {
            $products = $products->orderBy('new_price');
        } elseif ($sortBy == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($sortBy == 4) {
            $products = $products->orderBy('avg_rating', 'desc');
        } else {
            $products = $products;
        }
        $products = $products->with('subcategory')->limit(10)->get();

        $view = view('Template::filtered_search', compact('products'))->render();
        return response()->json([
            'html' => $view,
        ]);
    }

    public function subcategorySearch($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $pageTitle = 'Search Result For ' . $subcategory->name;

        $products = $subcategory->products()->where('status', 1)->with('subcategory')->latest()->paginate(getPaginate());

        $min = floor($products->min('new_price'));
        $max = ceil($products->max('new_price'));

        $categories   = Category::with(['subcategories', 'products', 'products.subcategory'])->latest()->get();

        return view('Template::product', compact('pageTitle', 'products', 'categories', 'min', 'max'));
    }

    public function loadMoreRating(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|gt:0',
            'id' => 'required|integer|gt:0',
        ]);
        $product = Product::where('status', 1)->findOrFail($request->id);
        $ratings = $product->ratings()->with('user')->latest()->skip($request->count)->limit(5)->get();
        $view = view('Template::ratings', compact('ratings'))->render();

        return response()->json([
            'success' => true,
            'ratings' => $ratings,
            'html' => $view,
        ]);
    }



    public function subscriberStore(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()]);
        }

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        return response()->json(['success' => 'Subscribed Successfully!']);
    }


    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }
}
