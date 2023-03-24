<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use App\Jobs\SendUserEmailJob;
use App\Mail\UserMail;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role','!=', 1)->where('status','!=',2)->get();
       return response()->json(['User' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([ 
            'first_name' => 'required', 
            'middle_name' => 'required', 
            'last_name' => 'required', 
            'department_id'=>'integer',
            'designation_id'=>'integer',
            'gender'=>'integer|min:1|max:1',
            'email' => 'required|max:191|unique:users,email', 
        ]);

        $password=Str::random(10);
        $image='';
        if(isset($request->image) && !empty($request->image))
        {
            $image = time() . 'user_' . strtolower(substr($request->first_name, 0, 3)) . '.' . $request->image->extension();
            $request->image->move(public_path('images/users/profile/'), $image);
        } 
        


        $user= User::create([
            'prefix' => ($request->prefix) ? $request->prefix : null,
            'first_name' => $request->first_name, 
            'middle_name' => $request->middle_name, 
            'last_name' => $request->last_name, 
            'department_id'=>($request->department_id) ? $request->department_id : null,
            'designation_id'=>($request->designation_id) ? $request->designation_id : null,
            'phone'=>($request->phone) ? $request->phone : null,
            'image'=>$image,
            'address'=>($request->address) ? $request->address : null,
            'email' => $request->email, 
            'password' => Hash::make($password), 
            'gender'=>($request->gender) ? $request->gender : null,
            'dob'=>($request->dob) ? date('d-m-Y', strtotime($request->dob)) : null,
            'joining_date'=>($request->joining_date) ? date('d-m-Y', strtotime($request->joining_date)) : null,
            'salary'=>($request->salary) ? $request->salary : null,
            'account_number'=>($request->account_number) ? $request->account_number : null,
            'ifsc_code'=>($request->ifsc_code) ? $request->ifsc_code : null,
            'bank_name'=>($request->bank_name) ? $request->bank_name : null,
            'holder_name'=>($request->holder_name) ? $request->holder_name : null,
        ]);

        if($user)
        {
            $token = $user->createToken('myToken')->plainTextToken;

            $email_data=[
                'name'=> $request->first_name.' '. $request->middle_name .' '. $request->last_name,
                'password'=>$password,
                'email'=>$request->email,
            ];

            $email_subject= "Registration on ".config('app.name');

            dispatch(new SendUserEmailJob($email_data,$email_subject));

            return response([
                'status' => true,
                'user' =>$user,
                'token'=> $token,
                'message'=>'Add User Successfully..!',
            ]);

        }
        else
        {
           return response([
                'status' => false,
                'message'=>'Something Went Wrong! Please Try Again.',
            ]); 
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::where('id',$id)->where('status','!=',2)->where('role','!=',1)->first();
        return response([
            'user' =>$user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([ 
            'first_name' => 'required', 
            'middle_name' => 'required', 
            'last_name' => 'required', 
            'department_id'=>'integer',
            'designation_id'=>'integer',
            'gender'=>'integer|min:1|max:1',
            'email' => 'max:191|unique:users,email,'.$id, 
        ]);

       

        $user=User::find($id);
        

        if(!is_null($user))
        {
             $image='';
            if(isset($request->image) && !empty($request->image))
            {
                $image = time() . 'user_' . strtolower(substr($request->first_name, 0, 3)) . '.' . $request->image->extension();
                $request->image->move(public_path('images/users/profile/'), $image);
            } 

            $user_update=[
            'prefix' => ($request->prefix) ? $request->prefix : null,
            'first_name' => $request->first_name, 
            'middle_name' => $request->middle_name, 
            'last_name' => $request->last_name, 
            'department_id'=>($request->department_id) ? $request->department_id : null,
            'designation_id'=>($request->designation_id) ? $request->designation_id : null,
            'phone'=>($request->phone) ? $request->phone : null,
            'image'=>$image,
            'address'=>($request->address) ? $request->address : null,
            'email' => $user->email, 
            'gender'=>($request->gender) ? $request->gender : null,
            'dob'=>($request->dob) ? date('d-m-Y', strtotime($request->dob)) : null,
            'joining_date'=>($request->joining_date) ? date('d-m-Y', strtotime($request->joining_date)) : null,
            'salary'=>($request->salary) ? $request->salary : null,
            'account_number'=>($request->account_number) ? $request->account_number : null,
            'ifsc_code'=>($request->ifsc_code) ? $request->ifsc_code : null,
            'bank_name'=>($request->bank_name) ? $request->bank_name : null,
            'holder_name'=>($request->holder_name) ? $request->holder_name : null,
        ];

            $result= $user->update($user_update);

            if($result)
            {
                return response([
                'status' => true,
                'user' =>$user,
                'message'=>'Update User Successfully..!',
                ]);
            }
            else
            {
               return response([
                'status' => false,
                'message'=>'Something Went Wrong! Please Try Again.',
                ]);  
            }

        }
        else
        {
            return response([
                'status' => false,
                'message'=>'Something Went Wrong! Please Try Again.',
            ]); 
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         try {
            $user=User::where('id',$id)->first();
            if($user->role != 1)
            {
                $update['status'] = 2;
                User::where('id',$id)->update($update);;
                $response = [
                    'status' => true,
                    'message' => "User Data Deleted Successfully",
                ];
            }
            else
            {
                $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
            ];
            }
            
        }catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => "Something Went Wrong! Please Try Again.",
            ];
        }

        return $response;
    }

    public function reset_password(Request $request)
    {
        $user=User::find($request->id);

        if(Auth::user()->role == 1)
        {
            if(!is_null($user))
            {
                 $password=Str::random(10);
                $result= $user->update(['password'=> Hash::make($password)]);

                if($result)
                {

                    $email_data=[
                    'name'=> $user->first_name.' '. $user->middle_name .' '. $user->last_name,
                    'password'=>$password,
                    'email'=>$user->email,
                    ];

                    $email_subject= "Reset Password on ".config('app.name');
                    dispatch(new SendUserEmailJob($email_data,$email_subject));

                    return response([
                    'status' => true,
                    'user' =>$user,
                    'message'=>'Update User Successfully..!',
                    ]);
                }
                else
                {
                   return response([
                    'status' => false,
                    'message'=>'Something Went Wrong! Please Try Again.',
                    ]);  
                }

            }

        }
         else
        {
           return response([
            'status' => false,
            'message'=>'Something Went Wrong! Please Try Again.',
            ]);  
        }

        
    }
}
