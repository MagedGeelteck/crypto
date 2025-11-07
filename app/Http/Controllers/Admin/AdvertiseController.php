<?php

namespace App\Http\Controllers\Admin;

use App\Models\Advertise;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class AdvertiseController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Advertisements';
        $advertisements = Advertise::searchable(['name'])->paginate(getPaginate());
        return view('admin.advertise', compact('pageTitle', 'advertisements'));
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'resolution'          => 'required|in:265x135,580x240,265x330',
            'redirect_url'  => 'required_if:type,1|url',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','gif'])]
        ]);

        if (!$id) {
            $notification = 'Advertisement added successfully';
            $advertisement         = new Advertise();
        } else {
            $notification = 'Advertisement updated successfully';
            $advertisement         = Advertise::findOrFail($id);
        }

        if ($request->hasFile('image')) {
            try {
                $old = $advertisement->image;
                if ($request->image->getClientOriginalExtension() == 'gif') {
                    $advertisement->image = fileUploader($request->image, getFilePath('ads'), null, $old);
                } else {
                    $advertisement->image = fileUploader($request->image, getFilePath('ads'), getFileSize('ads'), $old);
                }
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $advertisement->redirect_url = $request->redirect_url;
        $advertisement->resolution = $request->resolution;
        $advertisement->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Advertise::changeStatus($id);
    }
}
