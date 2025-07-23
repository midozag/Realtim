<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memeber;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\table;
use function PHPUnit\Framework\isEmpty;



class MemberController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
        'name' => 'required',
        'email' => 'required|email|unique:memebers,email',
       ]);
       if($validator->fails()){
        return response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ],422);
       }
       $member = Memeber::create([
        'name' => $request->name,
        'email' => $request->email,
       ]);
       return response()->json([
        'status' => true,
        'message' => 'Member created succesfully',
        'member' => $member
       ]);
    }
    public function list(Request $request)
    {
        $perPage = $request->input('per_page');
        $members = Memeber::paginate($perPage);
        return response()->json([
            'status' => true,
            'message' => 'Members returned succesfully',
            'members' => $members
        ]);
    }
    public function searchMember(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'string|nullable',
           ]);
           if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
           }
        if($request->name === " "){
            $members=Memeber::all();
            return response()->json([
                'status' => true,
             'message' => 'Member returned succesfully',
             'members' => $members,
            ]);
        };
        $members = Memeber::where('name','LIKE',"%{$request->name}%")
                    ->orderBy('created_at','desc')
                    ->get();
         if(is_null($members)){
            return response()->json([
                'status' => false,
                'message' => "No members '{$request->name}' matching",
                'members' => [],
              ],404);
           }
            return response()->json([
             'status' => true,
             'message' => 'Member returned succesfully',
             'members' => $members,

           ]);
        
    }
    public function search(Request $request){
        $perPage = $request->input('per_page');
        $page= $request->input('page');
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
           ]);
           if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
           }
            
           $members = Memeber::where('name','LIKE',"%{$request->name}%")
                             ->orderBy('created_at','desc')
                             ->paginate($perPage)
                             ;
           if($members->isEmpty()){
            return response()->json([
                'status' => false,
                'message' => "No members '{$request->name}' matching",
                'members' => [],
                'pagination' =>[
                    'total' => 0,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => 0,
                    'from' => 0,
                    'to' => 0
                ]
                
              ],404);
           }
           return response()->json([
             'status' => true,
             'message' => 'Member returned succesfully',
             'members' => $members->items(),
             'pagination' => [
                'total' => $members->total(),
            'per_page' => $members->perPage(),
            'current_page' => $members->currentPage(),
            'last_page' => $members->lastPage(),
            'from' => $members->firstItem(),
            'to' => $members->lastItem()

             ]

           ]);
    }
    public function listMembers(Request $request){
        $members = Memeber::all();
        if(count($members) === 0){
         return response()->json([
            'status' => false,
            'message' => 'Members not existed',
        ]);   
        }
        return response()->json([
            'status' => true,
            'message' => 'Members returned succesfully',
            'members' => $members
        ]);
    }
}
