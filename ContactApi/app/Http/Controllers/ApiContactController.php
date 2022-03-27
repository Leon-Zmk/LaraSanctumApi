<?php

namespace App\Http\Controllers;

use App\ApiContact;
use App\Http\Middleware\MyAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApiContactController extends Controller
{

    public function __construct()
    {
        // $this->middleware("myauth");
        $this->middleware("auth:sanctum");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact=ApiContact::latest("id")->where("user_id",Auth::id())->get();

        return response()->json($contact,200);
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
            
            'name'=>"nullable|min:3|unique:api_contacts,name|max:20",
            'phone'=>"required|min:10|unique:api_contacts,phone|max:20",

        ]);

        $contact=new ApiContact();
        $contact->name=$request->name;
        $contact->phone=$request->phone;
        $contact->user_id=Auth::id();

        $contact->save();

        return response()->json($contact,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact=ApiContact::find($id);

       if($contact->user_id==Auth::id()){
        if(is_null($contact)){
            return response(["message"=>"No Contact With This Id"],404);
        }

        

        return response()->json($contact);
       } else{
           return response(["message"=>"Action Unauthorized"],403);
       }
       
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

        
        $contact=ApiContact::find($id);
        Gate::authorize("update",$contact);

        if(is_null($contact)){
            return response(["message"=>"No Contact With This Id"],404);
        }

        $request->validate([
            
            'name'=>"nullable|min:3|unique:api_contacts,name,$contact->id|max:20",
            'phone'=>"required|min:10|unique:api_contacts,phone,$contact->id|max:20",

        ]);

         
        echo "From-";

        echo "($contact->name)"."-";
        
        echo "($contact->phone)"."-";
        

        $contact=ApiContact::find($id);
        $contact->name=$request->name;
        $contact->phone=$request->phone;
        
        echo "-To-";

        $contact->update();

        return response($contact,200);
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact=ApiContact::find($id);
        Gate::authorize("delete",$contact);
        if($contact->delete()){
            return response(["message"=>"Contact Deleted Successfully"],200);
        }
    }
}
