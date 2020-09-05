<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\models\Vanne;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VanneController extends Controller
{
   public function create($id){
       $user=User::Find($id);
       if (!$user){
           return redirect('/admin/paysons')->with('error','هناك خطأ');
       }
       else
           return view('admin.vannes.create',compact('user'));

   }
    public function store(Request $request,User $user)
    {
        $messages = [
            'name.required' => ' هذا الحقل مطلوب',
            'password.confirmed' => ' كلمة العبور غير متطابقة'
        ];
        $validateData = $request->validate([

            'numero_van' => 'required|string|max:255|unique:vannes,numero_van,',
        ], $messages);

        $vanne = new Vanne();
        $vanne->user_id= $request->input('user_id');
        $vanne->numero_van = $request->input('numero_van');

        $vanne->save();
        return redirect('/admin/paysons')->with('success','تم إضافة البيانات بنجاح');
    }
    //get the numbers of vanne own by one user
/*public function getVanne($user_id){
    $vannes=Vanne::where('user_id', $user_id)->get();
   return view('admin.vannes.getVanne')->with('vannes',$vannes);

}*/
}
