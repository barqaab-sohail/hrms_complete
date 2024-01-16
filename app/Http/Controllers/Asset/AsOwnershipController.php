<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Client;
use App\Models\Asset\AsOwnership;
use App\Http\Requests\Asset\AsOwnershipStore;
use App\Models\Asset\AsProject;
use App\Models\Project\PrDetail;
use DataTables;
use DB;

class AsOwnershipController extends Controller
{
    public function index(){
    
    	$asOwnerships = Client::all();
    	$projects = PrDetail::all();

       	$view =  view('asset.ownership.create',compact('asOwnerships','projects'))->render();
        return response()->json($view);
    }

    public function create(Request $request){

        
    	if ($request->ajax()) {

    		 // $posts = Post::join('users', 'posts.user_id', '=', 'users.id')
       //      ->select(['posts.id', 'posts.title', 'users.name', 'users.email', 'posts.created_at', 'posts.updated_at']);
         
    		$data = AsOwnership::where('asset_id',session('asset_id'))->latest()->get();

            return  DataTables::of($data)
                    ->addIndexColumn()
                    
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editOwnership">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteOwnership">Delete</a>';
                            
                            return $btn;
                    })
                
                    ->addColumn('ownership', function($row){                
                        
                        $btn = $row->asOwnership->name;
                                              
                        return $btn;  
                           
                    })
                    ->addColumn('date', function($row){                
                        
                        $btn = $row->date;
                            
                        return $btn;  
                           
                    })
                   
                    
                    ->rawColumns(['Edit','Delete','ownership','date','projectName'])
                    ->make(true);
          
        }
        
        $view =  view('asset.ownership.create')->render();
        return response()->json($view);

    } 

    public function store (AsOwnershipStore $request){

    	$input = $request->all();
    	if($request->filled('date')){
            $input ['date']= \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input, $request) {  
        	
            $prDetail=null;
            if($request->filled('pr_detail_id')){
                $prDetail = $input ['pr_detail_id'];
            }

            AsOwnership::updateOrCreate(['id' => $input['as_ownership_id']],
                ['date'=> $input['date'],
                'client_id'=> $input['client_id'],
                'pr_detail_id'=> $prDetail,
                'asset_id'=> session('asset_id')]); 

        }); // end transcation      
       return response()->json(['success'=>"Data saved successfully."]);

    }


    public function edit($id)
    {
        $asOwnership = AsOwnership::find($id);
        return response()->json($asOwnership);
    }

    public function destroy ($id){       

    	DB::transaction(function () use ($id) {  
    		AsOwnership::find($id)->delete();  		   	
    	}); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }






}
