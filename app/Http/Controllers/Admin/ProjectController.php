<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Project, User};
use App\DbViews\Project as vProject;
class ProjectController extends Controller
{
    public function getProjects( Request $request ){
    	$db_projects = vProject::select('v_agent.*', 'properties.*', 'companies.name as company_name')
          ->join('properties', 'v_agent.project_id', '=', 'properties.id')
          ->join('companies', 'properties.company_id', '=', 'companies.id')
          ->get();
      $records = [];
      
      foreach ($db_projects as $p) {
        $pro = Project::find($p->project_id);
        $agent = User::find($p->agent_id);

        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "status" => $p->status,
          "company" => $p->company_name,
          "company_id" => $p->company_id,
          "agente" => $agent ? $agent->lastname.', '.$agent->firstname: '',
          "agente_id" => $agent ? $agent->id : '',
//          "updated_at" => $p->updated_at->format('d-m-Y H:i'),
          "condition_label" => $pro->getConditionLabelAttribute(),
          "owner_id" => !is_null($pro->getOwner()) ? $pro->getOwner()->id : '',
          "owner_firstname" => !is_null($pro->getOwner()) ? $pro->getOwner()->firstname : '',
          "owner_lastname" => !is_null($pro->getOwner()) ? $pro->getOwner()->lastname : ''
        ];
      }
      return response([ 'records' => $records ]);
  	}
  	public function update( Request $request ){
		$project = Project::find($request->projectId);
		$project->name = $request->name;
		$project->description = $request->description;
		$project->save();
		return response([ "project_data" => $project ]);
	}
	public function getProjectData( Request $request ){
	    $project = Project::find($request->projectId);
	    return response([ "operation" => true ,"project" => $project ]);
	}
	public function delete( Request $request ){
		$project = Project::find($request->projectId);
	    $project->delete();
	    return response([ "operation" => true ]);
	}
}
