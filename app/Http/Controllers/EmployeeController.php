<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->role_id == 1){
            $users = User::where('role_id',3)->orderBy('id','DESC')->paginate(5);
        }else{
            $users = User::where('company_NO', auth()->user()->company_NO)->where('role_id',3)->orderBy('id','DESC')->paginate(5);
        }
        return view('dashboard/src/users',compact('users'));
    }

    public function create()
    {
        return view('dashboard/src/add-user');
    }

    public function createTo()
    {
        $users = User::where('role_id',2)->get();

        return view('dashboard/src/add-user_to',['users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'job' => 'required',
            'phone_NO' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'roles' => 'required'
        ],
            [
                'full_name.required' => 'يجب ادخال اسم المشرف!',
                'email.required' => 'يجب ادخال البريد الإلكتروني!',
                'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                'password.required' => 'يجب ادخال كلمة السر!',
                'job.required' => 'يجب ادخال وظيفة المشرف!',
                'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
//            'phone_NO.numeric' => 'يجب ادخال رقم الجوال بالأرقام!',
                'image.required' => 'يجب ادخال صورة المشرف!',
            ]);

        DB::beginTransaction();
        try {

            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

            $user = User::create([
                'full_name' => $request->full_name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'job'    => $request->job,
                'phone_NO'    => $request->phone_NO,
                'company_name'    => auth()->user()->company_name,
                'company_NO'    => auth()->user()->company_NO,
                'image' => 'https://velatest.pal-lady.com/storage/app/public/employee/' . $imageName,
                'role_id' => '3'
            ]);

            $user->assignRole(2);

            Storage::disk('public')->put('employee/' . $imageName, file_get_contents($request->image));

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users')->with('success','تم انشاء الموظف بنجاح');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function storeTo(Request $request)
    {

        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'job' => 'required',
            'phone_NO' => 'required',
            'user_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'roles' => 'required'
        ],
            [
                'full_name.required' => 'يجب ادخال اسم المشرف!',
                'email.required' => 'يجب ادخال البريد الإلكتروني!',
                'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                'password.required' => 'يجب ادخال كلمة السر!',
                'job.required' => 'يجب ادخال وظيفة المشرف!',
                'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
                'user_id.required' => 'يجب اضافة المشرف الى المشرف!',
//            'phone_NO.numeric' => 'يجب ادخال رقم الجوال بالأرقام!',
                'image.required' => 'يجب ادخال صورة المشرف!',
            ]);

        DB::beginTransaction();
        try {

            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            $admin = User::where('id',$request->user_id)->first();
            $user = User::create([
                'full_name' => $request->full_name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'job'    => $request->job,
                'phone_NO'    => $request->phone_NO,
                'company_name'    => $admin->company_name,
                'company_NO'    => $admin->company_NO,
                'image' => 'https://velatest.pal-lady.com/storage/app/public/employee/' .$imageName,
                'role_id' => '3'
            ]);

            $user->assignRole(3);

            Storage::disk('public')->put('employee/' . $imageName, file_get_contents($request->image));

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users')->with('success','تم انشاء الموظف بنجاح');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('dashboard/src/edit-user')->with([
            'user'  => $user,
        ]);

    }

    public function show(User $user)
    {
        return view('dashboard/src/profile_user',
            ['user'=>$user]);
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id.',id',
            'job' => 'required',
            'phone_NO' => 'required|numeric|digits:10',
        ],
            [
                'full_name.required' => 'يجب ادخال اسم المشرف!',
                'email.required' => 'يجب ادخال البريد الإلكتروني!',
                'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                'job.required' => 'يجب ادخال وظيفة المشرف!',
                'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
                'phone_NO.numeric' => 'يجب ادخال رقم الجوال بالأرقام!',
                'phone_NO.digits' => 'رقم الجوال يتكون من 10 ارقام!',
            ]);

        DB::beginTransaction();
        try {

            $user = User::find($user->id);

            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->phone_NO = $request->phone_NO;
            $user->job = $request->job;

            if ($request->image) {
                // Public storage
                $storage = Storage::disk('public');

                // Old iamge delete
                if ($storage->exists('admins/' . $user->image))
                    $storage->delete('admins/' . $user->image);

                // Image name
                $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

                $user->image = $imageName;

                // Image save in public folder
                $storage->put('admins/' . $imageName, file_get_contents($request->image));
            }

            // Update category
            $user->save();

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users')->with('success','تم تعديل الموظف بنجاح');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {

            // Detail
            $user_deleted = User::find($user->id);
            // Public storage
//            $storage = Storage::disk('public');
//            // Iamge delete
//            if ($storage->exists('stores/' . $store_deleted->logo))
//                $storage->delete('stores/' . $store_deleted->logo);

            // Delete user
            $user_deleted->delete();


            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->back()->with('success','تم حذف الموظف بنجاح');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


}
