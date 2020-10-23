<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Country , City, Project, PropertyType, Property, Amenity, Membership, PropertyFor, File, Photo};

class ProjectController extends Controller {
    public function index(Request $request) {
        /*$project = Project::find($request->project_id)->append('condition_label'); 

        if(!$project) return '{}';
    
        return Response($project);*/

        $project = Property::where('id',$request->project_id)->where('is_project', 1)->first();
        return $project;
    }

    public function agent(Request $request) {
        $agent = Project::find($request->project_id)->getAgent();

        $user = User::find($agent->id);
        $company = $user->getAgentCompany();

        $agent_project = [
            'firstname' => $agent->firstname,
            'lastname' => $agent->lastname,
            'photo' => $agent->photo,
            'id' => $agent->id,
            'membership_name' => $user->getAgentMerbershipOnce()->name,
            'company' => $company
        ];

		//if(!$agent) return response('{}');

		//$agent->rating = $agent->getRatingAttribute($request->project_id);
		
		return response($agent_project);
    }

    public function amenities(Request $request) {
		return response(Project::find($request->project_id)->amenities()->get());
    }
    
    public function photos(Request $request) {
        return response(['cover' => Photo::with('space')->where(['property_id' => $request->project_id, 'cover' => true])->get(),
                        'photos' =>  Photo::with('space')->where(['property_id' => $request->project_id, 'cover' => false])->get()]);
    }
} 