<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash,Redirect;
use App\Models\Slot;
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->to('dashboard');
        }
        else{
            return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
        }
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'mobile_no'=> 'required|min:10',
            'user_type'=>'required'
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
        if($check->role==1)
        {
            $this->make_slot($check);
        }
         
        return redirect("login")->withSuccess('Great! You have Successfully Register');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
  
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'mobile_no' =>  $data['mobile_no'],
        'role' =>  $data['user_type'],
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    public function make_slot($data)
    {
        $slots=[
            ['dr_id'=>$data->id,'slot'=>'10:00'],
            ['dr_id'=>$data->id,'slot'=>'10:30'],
            ['dr_id'=>$data->id,'slot'=>'11:00'],
            ['dr_id'=>$data->id,'slot'=>'11:30'],
            ['dr_id'=>$data->id,'slot'=>'12:00'],
            ['dr_id'=>$data->id,'slot'=>'12:30'],
            ['dr_id'=>$data->id,'slot'=>'18:00'],
            ['dr_id'=>$data->id,'slot'=>'18:30'],
            ['dr_id'=>$data->id,'slot'=>'19:00'],
            ['dr_id'=>$data->id,'slot'=>'19:30'],
            ['dr_id'=>$data->id,'slot'=>'20:00'],
            ['dr_id'=>$data->id,'slot'=>'20:30'],
            ['dr_id'=>$data->id,'slot'=>'21:00'],
        ];
      return  $slot=Slot::insert($slots);
    }
}