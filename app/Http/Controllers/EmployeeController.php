<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
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
        App::setLocale($request->input('locale'));

        // Determine the current language
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



            $user->assignRole(3);

            Storage::disk('public')->put('employee/' . $imageName, file_get_contents($request->image));

            // Commit And Redirected To Listing
            DB::commit();

            $details = [
                'title' => 'Mail from vela app',
                'body' => ['email' => $user->email,
                    'phone' => $user->phone_NO,
                    'password' => $request->password]
            ];

//            \Mail::to($user->email)->send(new \App\Mail\RegisterUserMail($details));

//            $this->create_user_for_connect_cube($user->id);
//
//            $this->get_user_from_connect_cube_updated($user->id);


//            //add the employee to group
            $conversation = Conversation::where('company_group', auth()->user()->company_NO)->first();

            $participant = Participant::create([
                'conversations_id' => $conversation->id,
                'user_id' => $user->id
            ]);

            // create QrCode
            try {
                $userInfo = [
                    "id" => $user->id,
                    "full_name" => $user->full_name,
                    "email" => $user->email,
                    "phone_NO" => $user->phone_NO,
                    // add other user information
                ];

                $data = json_encode($userInfo);

                $qrCode = QrCode::format('png')->size(200)
                    ->errorCorrection('H')->generate($data);

                $output_file = 'qr-code/img-' . time() . '.png';
                Storage::disk('public')->put($output_file, $qrCode);

                $user_update = User::find($user->id);
                $user_update->qr_code_path = 'https://velatest.pal-lady.com/storage/app/public/' . $output_file;
                $user_update->save();

            } catch (\Throwable $th) {
                dd('error', $th->getMessage());
            }

            // Encode user information as a string

            if ($language == 'en') {
                return redirect()->route('users')->with('success','Employee Created Successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('users')->with('success','تم انشاء الموظف بنجاح');
            }

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function storeTo(Request $request)
    {

        App::setLocale($request->input('locale'));

        // Determine the current language
        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'job' => 'required',
                'phone_NO' => 'required',
                'user_id' => 'required',
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
                'user_id' => 'required',
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
                    'user_id.required' => 'يجب اضافة المشرف الى المشرف!',
                    'image.required' => 'يجب ادخال صورة المشرف!',
                ]);        }



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

//            $this->create_user_for_connect_cube($user->id);
//
//            $this->get_user_from_connect_cube_updated($user->id);

            $details = [
                'title' => 'Mail from vela app',
                'body' => ['Email:' => $user->email,
                    'Phone' => $user->phone_NO,
                    'Password' => $request->password]
            ];

//            \Mail::to($user->email)->send(new \App\Mail\RegisterUserMail($details));


            //add the employee to group
            $conversation = Conversation::where('company_group', $admin->company_NO)->first();

            $participant = Participant::create([
                'conversations_id' => $conversation->id,
                'user_id' => $user->id
            ]);

            // create QrCode
            try {
                $userInfo = [
                    "id" => $user->id,
                    "full_name" => $user->full_name,
                    "email" => $user->email,
                    "phone_NO" => $user->phone_NO,
                    // add other user information
                ];

                $data = json_encode($userInfo);

                $qrCode = QrCode::format('png')->size(200)
                    ->errorCorrection('H')->generate($data);

                $output_file = 'qr-code/img-' . time() . '.png';
                Storage::disk('public')->put($output_file, $qrCode);

                $user_update = User::find($user->id);
                $user_update->qr_code_path = 'https://velatest.pal-lady.com/storage/app/public/' . $output_file;
                $user_update->save();

            } catch (\Throwable $th) {
                dd('error', $th->getMessage());
            }

            // Encode user information as a string

            if ($language == 'en') {
                return redirect()->route('users')->with('success','Employee Created Successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('users')->with('success','تم انشاء الموظف بنجاح');
            }

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


        App::setLocale($request->input('locale'));

        // Determine the current language
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

            if($request->private_status == null){
                $private_status = false;
            }else{
                $private_status = true;
            }

            $user = User::find($user->id);

            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->phone_NO = $request->phone_NO;
            $user->job = $request->job;
            $user->private_status = $private_status;

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
            if ($language == 'en') {
                return redirect()->route('users')->with('success','Employee Updated Successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('users')->with('success','تم تعديل الموظف بنجاح');
            }

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

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

            // Delete user
            $user_deleted->delete();


            // Commit And Redirected To Listing
            DB::commit();
            if ($language == 'en') {
                return redirect()->back()->with('success','Employee Deleted Successfully');
            } elseif ($language == 'ar') {
                return redirect()->back()->with('success','تم حذف الموظف بنجاح');
            }


        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


}
