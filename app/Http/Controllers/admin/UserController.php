<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\models\Vanne;
use Illuminate\Http\Request;
use App\User;
use App\Models\Facture;
use Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function dashboard()
    {
        return view('users.dashboard');
    }


    public function index()
    {
        $users = User::all();
        return view('admin.paysons.index', ['users' => $users]);
    }


    public function create()
    {
        return view('admin.paysons.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ' هذا الحقل مطلوب',
            'password.confirmed' => ' كلمة العبور غير متطابقة',
            'numero_van.unique' =>'نقطة الري موجودة في قاعدة البيانات',
        ];
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'numero_van' => 'required|string|max:255|unique:vannes',
            'phone' => 'required',
            'photo' => 'max:255|mimes:jpg,png,jpeg',
            'password' => 'required|string|min:8|confirmed',
        ], $messages);
        $user = new User();
        $filepath='';
        if ($request->has('photo')){
            $filepath=uploadImage('users',$request->photo);
        }
        $user->photo = $filepath;
        $user->name = $request->input('name');
        $user->prenom = $request->input('prenom');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->active = $request->input('active');
        $x=$request->get('id');
        $user->save();
        $vanne=new Vanne();
        $vanne->user_id=$user->id;
        $vanne->numero_van = $request->input('numero_van');
        $vanne->save();
        return redirect('/admin/paysons')->with('success','تم إضافة البيانات بنجاح');
    }

    public function edit($id){
        $user=User::Find($id);
        if (!$user){
            return redirect('/admin/paysons')->with('error','هناك خطأ');
        }
        else
        return view('admin.paysons.edit',compact('user'));
    }
        public function update(Request $request,$id, User $user){
            $user=User::FindOrFail($id);
            $messages = [
                'required' => ' هذا الحقل مطلوب',
                'email.unique'=>'هدا البريد الإلكتروني موجود',
                 'numero_van.unique'=>'  رقم الفانة موجود',
            ];
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'phone' => 'required',
            ], $messages);
            $filepath='';
            if ($request->hasFile('photo')){
                $filepath=uploadImage('users',$request->photo);
            }
            else
                $filepath=$request->old_photo;
            $user->update([
                'photo'=>  $filepath,
               'name'=> $request->input('name'),
                'prenom' => $request->input('prenom'),
                'phone' => $request->input('phone'),
               'email' => $request->input('email'),
            ]);

            return redirect()->route('admin.listePaysons')->with('success','تم تحديث البيانات بنجاح');
        }
    public function archive()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.paysons.archive',['users'=>$users]);
    }
    public function makeActive($id)
{
    User::withTrashed()->findOrFail($id)->restore();
    return redirect('/admin/paysons')->with('success','تم التفعيل بنجاح');
}
    public function information($id)
    {
        $user=User::Find($id);
        $vannes=Vanne::where('user_id', $id)->get();
        if (!$user){
            return redirect('/admin/paysons')->with('error','هناك خطأ');
        }
        return view('admin.paysons.information',compact('user','vannes'));
    }
    public function destroy($id)
    {
        $user=User::Find($id);
        if (!$user){
            return redirect('/admin/paysons')->with('error','هناك خطأ');
        }
        $user->delete();
        return redirect('/admin/paysons')->with('success','تم الحذف بنجاح');

    }

    public function listeArchive()
    {
        $users = User::onlyTrashed()->get();
        return view('users.listeArchive', ['users' => $users]);
    }

    public function show($id)
    {
        $factures = Facture::where('user_id', '=', $id)->get();
        $user = User::FindOrFail($id);
        return view('users.show', ['factures' => $factures], compact('user'));
    }

    public function showOne()
    {
        $factures = Facture::where('user_id', '=', Auth::user()->id)->get();
        $user = User::FindOrFail(Auth::user()->id);
        return view('users.showOne', ['factures' => $factures], compact('user'));
    }

    public function createFacture($id)
    {
        $vannes = Vanne::where('user_id', '=', $id)->get();
        $user = User::FindOrFail($id);
        return view('admin.paysons.createFacture', ['vannes' => $vannes],compact('user'));
    }
    public function createFactureToVanne($id,$vanneId,$vanneNum)
    {
        $vannes = Vanne::where('id', '=', $vanneId)->get();
        $user = User::FindOrFail($id);
        return view('admin.paysons.createFactureToVanne',compact('user','vannes'));
    }
    public function storeFacture(Request $request)
    {
        $messages = [
            'required' => 'هذا الحقل مطلوب.',
            'newComteur.gte'=>'العداد الجديد يجب أن يكون أكبر أو يساوي العداد القديم',
        ];
        $request->validate([
             'oldComteur' => 'required',
              'newComteur'=>'required|gte:oldComteur',
              'oldMontant' => 'required',
              'mois' => 'required',

        ],$messages);

        $facture=new Facture();
        $facture->user_id     = $request->input('user_id');
        $facture->vanne_id    = $request->input('vanne_id');
        $facture->oldComteur     = $request->input('oldComteur');
        $facture->newComteur     = $request->input('newComteur');
        $facture->oldMontant     = $request->input('oldMontant');
        $facture->mois     = $request->input('mois');
        $time=strtotime($request->input('mois'));
        if(date('m',$time)=="01"){
            $facture->newMontant=((($request->input('newComteur') - $request->input('oldComteur'))*120+18000)+$request->input('oldMontant'));
        }
        else{
            $facture->newMontant=((($request->input('newComteur') - $request->input('oldComteur'))*120+3000)+$request->input('oldMontant'));

        }

        $facture->save();
        return redirect()->route('admin.index')->with('success','تمت الاضافة بنجاح');
    }

}
