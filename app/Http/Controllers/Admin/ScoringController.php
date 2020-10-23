<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{ Scoring, ScoringCategory, ScoringCategoryDetail };
use App\DbViews\{ScoringPoints,ScoringCategoryPoints};
class ScoringController extends Controller
{
    //
    public function getScoringList( Request $request ){
      $scoring_list = ScoringPoints::get();
      return response([ 'records' => $scoring_list ]);

  	}
    public function getScoring( Request $request ){
      $scoring = Scoring::find($request->scoringId);
      return response([ 'scoring' => $scoring ]);
  	}
    public function create( Request $request ){
      $scoring = new Scoring();
      $scoring->name = $request->name;
      $scoring->description = $request->description;
      $scoring->save();
      return response([ "operation" => true ]);
    }
    public function update( Request $request ){
      $scoring = Scoring::find($request->scoringId);
      $scoring->name = $request->name;
      $scoring->description = $request->description;
      $scoring->save();
      return response([ "operation" => true ]);
    }
    public function getScoringCategories( Request $request ){
      $scoring_categories = ScoringCategoryPoints::where([ 'scoring_id' => $request->scoringId ])->get();
      $scoring = ScoringPoints::where([ 'id' => $request->scoringId ])->first();
      return response([ 'records' => $scoring_categories, 'scoring' => $scoring ]);
  	}
    public function getScoringCategory( Request $request ){
      $scoring_category = ScoringCategory::where([ 'scoring_id' => $request->scoringId, 'id' => $request->scoringCategoryId ])->first();
      $points_category = ScoringCategoryPoints::where([ 'scoring_id' => $request->scoringId, 'id' => $request->scoringCategoryId ])->first();
      $scoring_category->points_scoring = $points_category->points_scoring;
      return response([ 'scoring_category' => $scoring_category  ]);
  	}
    public function updateScoringCategory( Request $request ){
      $scoring_category = ScoringCategory::find($request->scoringCategoryId);
      $scoring_category->name = $request->name;
      $scoring_category->description = $request->description;
      $scoring_category->feed_back = $request->feed_back;
      $scoring_category->save();
      return response([ 'operation' => true ]);
  	}
    public function addScoringCategory( Request $request ){
      $scoring_category = new ScoringCategory();
      $scoring_category->name = $request->name;
      $scoring_category->description = $request->description;
      $scoring_category->feed_back = $request->feed_back;
      $scoring_category->scoring_id = $request->scoringId;
      $scoring_category->save();
      return response([ 'operation' => true ]);
  	}
    public function getScoringCategoryDetails( Request $request ){

      $category_details = ScoringCategoryDetail::where([ "scoring.id" => $request->scoringId , "cat_scoring.id" => $request->scoringCategoryId  ])
                        ->join('cat_scoring', 'det_scoring.cat_scoring_id', '=', 'cat_scoring.id')
                        ->join('scoring', 'scoring.id', '=', 'cat_scoring.scoring_id')
                        ->select(['det_scoring.id','det_scoring.name','det_scoring.feed_back','det_scoring.description','det_scoring.points'])
                        ->get();
      $scoring_category = ScoringCategory::where([ 'scoring_id' => $request->scoringId, 'id' => $request->scoringCategoryId ])->first();
      return response([ 'records' => $category_details, 'category' => $scoring_category ]);
  	}
    public function getScoringCategoryDetail( Request $request ){

      $category_detail = ScoringCategoryDetail::find($request->categoryDetailId);
      return response([ 'category_detail' => $category_detail ]);
  	}
    public function updateScoringCategoryDetail( Request $request ){
      $category_detail = ScoringCategoryDetail::find($request->categoryDetailId);
      $category_detail->name = $request->name;
      $category_detail->description = $request->description;
      $category_detail->feed_back = $request->feed_back;
      $category_detail->points = $request->points;
      $category_detail->save();
      return response([ "operation" => true ]);
    }
    public function addScoringCategoryDetail( Request $request ){
      $category_detail = new ScoringCategoryDetail();
      $category_detail->name = $request->name;
      $category_detail->description = $request->description;
      $category_detail->feed_back = $request->feed_back;
      $category_detail->points = $request->points;
      $category_detail->cat_scoring_id = $request->scoringCategoryId;
      $category_detail->save();
      return response([ "operation" => true ]);
    }
}
