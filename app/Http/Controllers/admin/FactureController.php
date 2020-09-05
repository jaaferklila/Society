<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Facture;
use App\Models\Vanne;
use Illuminate\Support\Facades\Validator;
class FactureController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        }

    public function index(){
        $facture=Facture::all();
        return view('admin.factures.index',['factures'=>$facture]);

    }
    public function factureUser($idUser){
        $user=User::Find($idUser);
        $vannes=Vanne::where('user_id', $idUser)->get();
        if (!$user){
            return redirect('/admin/paysons')->with('error','هناك خطأ');
        }
        return view('admin.factures.facture',compact('user'));

    }

    public function create(){
         $user=User::all();
        $vanne=Vanne::all();
        return view('admin.factures.create',['users'=>$user,'vannes'=>$vanne]);
    }
    public function store(Request $request){
        $facture=new Facture();
        $messages = [
            'newComteur.required' => 'The newComteur field is required.',
        ];
        $request->validate([
            'user' => 'required',
            'oldComteur' => 'required',
            'newComteur'=>'required|gte:oldComteur',
            'oldMontant' => 'required',
            'mois' => 'required',

        ],$messages);
        $facture->user_id=$request->input('user');
        $facture->newComteur=$request->input('newComteur');
        $facture->oldComteur=$request->input('oldComteur');
        $facture->oldMontant=$request->input('oldMontant');
        $facture->mois=$request->input('mois');
        $time=strtotime($request->input('mois'));

        if(date('m',$time)=="01"){
            $facture->newMontant=((($request->input('newComteur') - $request->input('oldComteur'))*120+18000)+$request->input('oldMontant'));
        }
        else{
            $facture->newMontant=((($request->input('newComteur') - $request->input('oldComteur'))*120+3000)+$request->input('oldMontant'));

        }


        $facture->save();
        return redirect('facture');
    }

    public function show($id){
        $facture=Facture::FindOrFail($id);
        return view("factures.show",compact('facture'));
    }
    public function edit($id){
        $facture=Facture::FindOrFail($id);
        return view('factures.edit',compact('facture'));


    }
}
