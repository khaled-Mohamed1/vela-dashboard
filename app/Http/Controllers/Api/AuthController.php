<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','loginCompany','logout','getUsers']]);
    }

    public function loginCompany(Request $request)
    {

        try{
            $request->validate([
                'company_NO' => 'required|numeric|digits:6',
            ],
                [
                    'company_NO|required' => 'يجب ادخال رقم الشركة!',
                    'company_NO|numeric' => 'يجب ادخال رقم الشركة بالأرقام!',
                    'company_NO|digits' => 'رقم الشركة يتكون من 6 ارقام!',
                ]
            );

            $company = User::where('company_NO',$request->company_NO)->get()->first();
            if (!$company) {
                return response()->json([
                    'status' => 'error',
                    'message_ar' => 'رقم الشركة الذي ادخلته غير موجود!',
                    'message_en' => 'The Company Number Not Found!',
                    'error_number' => 404,
                ], 401);
            }else{
                return response()->json([
                    'status' => 'success',
                    'error_number' => 200,
                ], 200);
            }
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 401);
        }

    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_NO' => 'required',
            'password' => 'required|string',
            'company_NO' => 'required|numeric|digits:6',
        ]);

        $credentials = $request->only('password', 'phone_NO','company_NO');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'error_number' => 404,
            ], 401);
        }

//        $user = Auth::user();
        $user = User::where('phone_NO', $request->phone_NO)
            ->where('company_NO', $request->company_NO)->first();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'type' => 'bearer',
            ],
            'error_number' => 200,
        ],200);

    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم تسجيل الخروج من التطبيق.',
            'message_en' => 'Successfully logged out',
            'error_number' => 200,
        ]);
    }


    public function getUsers(Request $request)
    {
        try {
            $users = User::where('role_id', '!=', '1')->latest()->get();
            return response()->json([
                'status' => true,
                'users' => $users,
            ], 200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }

}
