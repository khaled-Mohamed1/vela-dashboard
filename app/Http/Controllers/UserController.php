<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Participant;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {

//        $cn = Conversation::where('company_NO', auth()->user()->company_NO)->orWhere('company_group', auth()->user()->company_NO)->get();

        if(auth()->user()->role_id == 1){
            $users = User::where('role_id',2)->orderBy('id','DESC')->paginate(5);

        }else{
            $users = User::where('id', auth()->user()->id)->paginate(5);
        }
        return view('dashboard/src/admins',compact('users'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard/src/add-admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        App::setLocale($request->input('locale'));

        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'job' => 'required',
                'phone_NO' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'company_name' => 'required',
            ]
            );
        } elseif ($language == 'ar') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'job' => 'required',
                'phone_NO' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'company_name' => 'required',
            ],
                [
                    'full_name.required' => 'يجب ادخال اسم المشرف!',
                    'email.required' => 'يجب ادخال البريد الإلكتروني!',
                    'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                    'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                    'password.required' => 'يجب ادخال كلمة السر!',
                    'job.required' => 'يجب ادخال وظيفة المشرف!',
                    'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
                    'company_name.required' => 'يجب ادخال اسم الشركة!',
                    'image.required' => 'يجب ادخال صورة المشرف!',

                ]);
        }



        DB::beginTransaction();
        try {


            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

            $randomString  = mt_rand(100000,999999);


            $user = User::create([
                'full_name' => $request->full_name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'job'    => $request->job,
                'phone_NO'    => $request->phone_NO,
                'company_name'    => $request->company_name,
                'company_NO'    => $randomString,
                'image' => 'https://velatest.pal-lady.com/storage/app/public/admins/' .$imageName,
                'role_id' => '2',
                'private_status' => true
            ]);

            $user->assignRole(2);
            DB::commit();
            Storage::disk('public')->put('admins/' . $imageName, file_get_contents($request->image));

            $details = [
                'title' => 'Mail from vela app',
                'body' => ['email' => $user->email,
                    'phone' => $user->phone_NO,
                    'password' => $request->password,
                    'company_NO' => $user->company_NO]
            ];

            \Mail::to($user->email)->send(new \App\Mail\RegisterUserMail($details));

            $conversation = Conversation::create([
                'name' => $request->company_name,
                'image' => $user->image,
                'type' => 'group',
                'admin_id' => $user->id,
                'company_group' => $randomString,
            ]);

            //add the admin in Participant table
            $participant = Participant::create([
                'conversations_id' => $conversation->id,
                'user_id' => $user->id
            ]);



            // Commit And Redirected To Listing

            if ($language == 'en') {
                return redirect()->route('admins')->with('success','Admin created successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('admins')->with('success','تم انشاء المشرف بنجاح');
            }

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('dashboard/src/profile_admin',
            ['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard/src/edit-admin')->with([
            'user'  => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {

        App::setLocale($request->input('locale'));

        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id.',id',
                'job' => 'required',
                'phone_NO' => 'required|numeric',
                'company_name' => 'required',
            ],
                );
        } elseif ($language == 'ar') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id.',id',
                'job' => 'required',
                'phone_NO' => 'required|numeric',
                'company_name' => 'required',
            ],
                [
                    'full_name.required' => 'يجب ادخال اسم المشرف!',
                    'email.required' => 'يجب ادخال البريد الإلكتروني!',
                    'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                    'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                    'job.required' => 'يجب ادخال وظيفة المشرف!',
                    'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
                    'phone_NO.numeric' => 'يجب ادخال رقم الجوال بالأرقام!',
//                    'phone_NO.digits' => 'رقم الجوال يتكون من 10 ارقام!',
                    'company_name.required' => 'يجب ادخال اسم الشركة!',
                ]);        }



        DB::beginTransaction();
        try {

            $user = User::find($user->id);

            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->phone_NO = $request->phone_NO;
            $user->job = $request->job;
            $user->company_name = $request->company_name;

            if ($request->image) {
                // Public storage
                $storage = Storage::disk('public');

                // Old iamge delete
                if ($storage->exists('admins/' . $user->image))
                    $storage->delete('admins/' . $user->image);

                // Image name
                $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

//                $user->image = $imageName;
                $user->image = 'https://velatest.pal-lady.com/storage/app/public/admins/' .$imageName;

                // Image save in public folder
                $storage->put('admins/' . $imageName, file_get_contents($request->image));
            }

            // Update category
            $user->save();

            // Commit And Redirected To Listing
            DB::commit();

            if ($language == 'en') {
                return redirect()->route('admins')->with('success','Admin Updated successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('admins')->with('success','تم تعديل المشرف بنجاح');
            }


        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,User $user)
    {
        DB::beginTransaction();
        try {

            App::setLocale($request->input('locale'));

            // Determine the current language
            $language = App::getLocale();

            // Detail
            $user_deleted = User::find($user->id);
            // Public storage
//            $storage = Storage::disk('public');
//            // Iamge delete
//            if ($storage->exists('stores/' . $store_deleted->logo))
//                $storage->delete('stores/' . $store_deleted->logo);

            // Delete Store
            $user_deleted->delete();


            // Commit And Redirected To Listing
            DB::commit();
            if ($language == 'en') {
                return redirect()->back()->with('success','Admin Updated successfully');
            } elseif ($language == 'ar') {
                return redirect()->back()->with('success','تم حذف المشرف بنجاح');
            }

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function conversation()
    {
        $conversations = Conversation::where('type', 'peer')->where('company_NO', auth()->user()->company_NO)
            ->latest()->get();
        return view('dashboard/src/p2p-conversation',['conversations' => $conversations]);
    }

    public function showConversation(Conversation $conversation)
    {
        return view('dashboard/src/show_p2p_conversation',[
            'conversation' => $conversation
        ]);
    }

    public function groupsConversation()
    {
        $conversations = Conversation::where('type', 'group')->where('company_NO', auth()->user()->company_NO)
//            ->orWhere('company_group', auth()->user()->company_NO)
            ->latest()->get();

        return view('dashboard/src/groups-conversations',['conversations' => $conversations]);
    }

    public function showGroupsConversation(Conversation $conversation)
    {
        return view('dashboard/src/show_groups-conversations',[
            'conversation' => $conversation
        ]);
    }

}
