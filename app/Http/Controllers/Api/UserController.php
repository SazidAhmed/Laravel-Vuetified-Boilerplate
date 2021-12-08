<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\User as UserResource;
use App\User;
use App\Role;

class UserController extends Controller
{
    //User List
    public function index(User $model)
    {
        $per_page = $request->per_page ? $request->per_page : 5;
        $sortBy = $request->sort_by ? $request->sort_by : 'name';
        $orderBy = $request->order_by ? 'asc' : 'desc';
        return response()->json([
            'users' => new UserCollection(User::orderBy($sortBy, $orderBy)->paginate($per_page)),
            'roles' => Role::pluck('name')->all()
        ], 200);
    }

    public function store(Request $request)
    {
        
        $this->validate($request,[
            'email'=>'required|string|unique:email',
            'password'=>'required|string|min:6|',
            'role_id'=>'required',
        ]);

        $user = new User;
        $user->role_id = $request->input('role_id');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password=Hash::make($request->input('password'));
        $user->save();
        return response()->json(['user'=>$user], 200);

    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = User::find($id);
        //password update
        if($request->password){
            $password = $request->password;
            $data['password']= bcrypt($password);
        }else{
            $password = $user->password;
            $data['password']= $password;
        }
        // dd($request->all());
        $user->update($data);
        return response()->json(['user'=>$user], 200);
    }


    public function destroy($id)
    {
        $user = User::find($id)->delete();
        return response()->json(['user'=>$user], 200);
       
    }


    public function login(Request $request){
        // echo json_encode($request->all());exit;
        if ($request->ajax()) {
            $credentials = $request->only('email', 'password');

            if(Auth::attempt($credentials)){
                $token = Str::random(80);
                Auth::user()->api_token = $token;
                Auth::user()->save();
                // $admin = Auth::user()->isAdmin();
                return response()->json(['token'=>$token], 200);
            }
        }
        return response()->json(['status'=>'Email or Password is Wrong'], 403);
    }

    //To Varify Authentication for access apis
    public function userVarification(Request $request){
        return $request->user()->only('name', 'email');
    }

    //Auth User permissions
    public function userPermissions(Request $request){
        $getToken = $request->token;
        $user = User::where('api_token', $getToken)->first();

        $permissions = json_decode($user->role->permissions);
        // dd($permissions);
        return response()->json(['permissions'=>$permissions], 200);
    }

}
 