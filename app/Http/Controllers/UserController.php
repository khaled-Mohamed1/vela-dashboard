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

    public function create_user_for_connect_cube($id)
    {
        $user=User::find($id);
        DEFINE('APPLICATION_ID', 6892);
        DEFINE('AUTH_KEY', "wJSMZHycdwNLwtW");
        DEFINE('AUTH_SECRET', "bna9zQykZW5KxCa");

        DEFINE('CB_API_ENDPOINT', "https://api.connectycube.com");
        DEFINE('CB_PATH_SESSION', "session.json");

        $nonce = rand();
        $timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
        $signature_string = "application_id=".APPLICATION_ID."&auth_key=".AUTH_KEY."&nonce=".$nonce."&timestamp=".$timestamp;
        $signature = hash_hmac('sha1', $signature_string , AUTH_SECRET);

        $post_body = http_build_query(array(
            'application_id' => APPLICATION_ID,
            'auth_key' => AUTH_KEY,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ));

        // Configure cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, CB_API_ENDPOINT . '/' . CB_PATH_SESSION); // Full path is - https://api.connectycube.com/session.json
        curl_setopt($curl, CURLOPT_POST, true); // Use POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response

        // Execute request and read responce
        $responce = curl_exec($curl);

            // Check errors
        if ($responce) {
            $data_response= json_decode($responce,true);
            $token=$data_response['session']['token'];
            try {
                $client = new \GuzzleHttp\Client();
                $create_user_in_connecty_cube= $client->request('POST', 'https://api.connectycube.com/users', [
                    'headers' => [
                        'CB-Token' =>$token,
                        'Content-Type' => 'application/json',
                    ],
                    'body' => '{
                    "user": {
                        "login": "'.$user->full_name.'",
                        "password": "12341234",
                        "email": "'.$user->email.'",
                        "full_name": "'.$user->full_name.'",
                        "phone": "'.$user->phone_NO.'"
                        }
                   }',
                ]);
                $user = json_decode($create_user_in_connecty_cube->getBody()->getContents());

                return $user;
            } catch (\Throwable $th) {
                dd('error', $th->getMessage());
            }

        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
            echo $error . "\n";
        }

        // Close connection
        curl_close($curl);

    }

    public function get_user_from_connect_cube_updated($id)
    {
        $user=User::find($id);
        DEFINE('APPLICATION_ID2', 6892);
        DEFINE('AUTH_KEY2', "wJSMZHycdwNLwtW");
        DEFINE('AUTH_SECRET2', "bna9zQykZW5KxCa");

        DEFINE('CB_API_ENDPOINT2', "https://api.connectycube.com");
        DEFINE('CB_PATH_SESSION2', "session.json");

        $nonce = rand();
        $timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
        $signature_string = "application_id=".APPLICATION_ID2."&auth_key=".AUTH_KEY2."&nonce=".$nonce."&timestamp=".$timestamp;
        $signature = hash_hmac('sha1', $signature_string , AUTH_SECRET2);
        $post_body = http_build_query(array(
            'application_id' => APPLICATION_ID2,
            'auth_key' => AUTH_KEY2,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ));

        // Configure cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, CB_API_ENDPOINT2 . '/' . CB_PATH_SESSION2); // Full path is - https://api.connectycube.com/session.json
        curl_setopt($curl, CURLOPT_POST, true); // Use POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response

        // Execute request and read responce
        $responce = curl_exec($curl);

        // Check errors
        if ($responce) {
            $data_response= json_decode($responce,true);
            $token=$data_response['session']['token'];

            try {
                $client = new \GuzzleHttp\Client();
                $get_user_from_connecty_cube= $client->request('GET', 'https://api.connectycube.com/users/by_email', [
                    'headers' => [
                        'CB-Token' =>$token,
                    ],
                    'json' => [
                        'email' => $user->email,
                    ]
                ]);
                $user_by_email = json_decode($get_user_from_connecty_cube->getBody()->getContents());
                $user->connecty_cube_id = $user_by_email->user->id;
                $user->save();
            } catch (\Throwable $th) {
                dd('error', $th->getMessage());
            }

        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
        }

// Close connection
        curl_close($curl);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4){
            $users = User::where('role_id',4)->orWhere('role_id', 2)->orderBy('id','DESC')->paginate(5);
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

    public function createAdmin()
    {
        return view('dashboard/src/add-admin-company');
    }

    public function storeAdmin(Request $request)
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
            ],
                [
                    'full_name.required' => 'يجب ادخال اسم المشرف!',
                    'email.required' => 'يجب ادخال البريد الإلكتروني!',
                    'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                    'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                    'password.required' => 'يجب ادخال كلمة السر!',
                    'job.required' => 'يجب ادخال وظيفة المشرف!',
                    'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
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
                'company_name'    => auth()->user()->company_name,
                'company_NO'    => auth()->user()->company_NO,
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

//            \Mail::to($user->email)->send(new \App\Mail\RegisterUserMail($details));
//            $this->create_user_for_connect_cube($user->id);
//            $this->get_user_from_connect_cube_updated($user->id);

            $conversation = Conversation::where('company_group', auth()->user()->company_NO)->first();

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

    public function editAdmin(User $user)
    {
        return view('dashboard/src/edit-admin-company')->with([
            'user'  => $user,
        ]);
    }

    public function updateAdmin(Request $request, User $user)
    {

        App::setLocale($request->input('locale'));

        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id.',id',
                'job' => 'required',
                'phone_NO' => 'required|numeric',
            ],
            );
        } elseif ($language == 'ar') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id.',id',
                'job' => 'required',
                'phone_NO' => 'required|numeric',
            ],
                [
                    'full_name.required' => 'يجب ادخال اسم المشرف!',
                    'email.required' => 'يجب ادخال البريد الإلكتروني!',
                    'email.email' => 'يجب ادخال البريد الإلكتروني ب "@"!',
                    'email.unique' => 'البريد الإلكتروني الذي ادخلته مستخدم!',
                    'job.required' => 'يجب ادخال وظيفة المشرف!',
                    'phone_NO.required' => 'يجب ادخال رقم جوال المشرف!',
                    'phone_NO.numeric' => 'يجب ادخال رقم الجوال بالأرقام!',
                ]);
        }



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
                'role_id' => '4',
                'private_status' => true
            ]);



            $user->assignRole(4);
            DB::commit();
            Storage::disk('public')->put('admins/' . $imageName, file_get_contents($request->image));

            $details = [
                'title' => 'Mail from vela app',
                'body' => ['email' => $user->email,
                    'phone' => $user->phone_NO,
                    'password' => $request->password,
                    'company_NO' => $user->company_NO]
            ];

//            \Mail::to($user->email)->send(new \App\Mail\RegisterUserMail($details));
//            $this->create_user_for_connect_cube($user->id);
//            $this->get_user_from_connect_cube_updated($user->id);

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
            ->orWhere('company_group', auth()->user()->company_NO)
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
