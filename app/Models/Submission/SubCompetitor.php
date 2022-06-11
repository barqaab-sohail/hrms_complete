<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class SubCompetitor extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['submission_id', 'name'];

   	public function subTechnicalNumber(){
    	return $this->hasOne('App\Models\Submission\SubTechnicalNumber');
    }

   
    public function subFinancialCost(){
    	return $this->hasOne('App\Models\Submission\SubFinancialCost');
    }

    public function submission(){
    	return $this->belongsTo('App\Models\Submission\Submission');
    }

    public function getFinancialMark(){
    	$financialWeightage = $this->submission->subDescription->financial_weightage;
    	$lowestCompetitor = SubCompetitor::join('sub_financial_costs','sub_financial_costs.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_financial_costs.financial_cost')->where('submission_id',session('submission_id'))->orderBy('financial_cost', 'ASC')->first();

      
      if($lowestCompetitor && $this->subFinancialCost){
    	   return round(($lowestCompetitor->financial_cost/$this->subFinancialCost->financial_cost * $financialWeightage),4);
      }
    }
    public function getTechnicalScore(){
    	$technicalWeightage = $this->submission->subDescription->technical_weightage;
    	return round(($this->subTechnicalNumber->technical_number * $technicalWeightage / 100),4);
    }

    public function getTechnicalAndFinancialMark(){
     

      if($this->getTechnicalScore() && $this->getFinancialMark()){
    	 return $this->getTechnicalScore() + $this->getFinancialMark();
      }else{
        return '';
      }
    }

    

    public function getRanking(){
   		$passingMarks = $this->submission->subDescription->passing_marks;


   		if($this->subTechnicalNumber->technical_number < $passingMarks){
   			return "Not Qualify";
   		}

      $allCompetitors = SubCompetitor::where('submission_id',session('submission_id'))->get();

    $rank=1;
      foreach($allCompetitors as $competitor){
       if($this->getTechnicalAndFinancialMark()<$competitor->getTechnicalAndFinancialMark()){
          $rank++;
        }

      }
      return $rank;


      // $results = [];
      // $allCompetitors = SubCompetitor::all();
      // foreach($allCompetitors as $competitor){
      //   array_push($results, $this->getTechnicalAndFinancialMark());
      // }
      // return $results;
      // $first = 1;
      // if(while >$this->getTechnicalAndFinancialMark())

      // return $first;

    //   $competitors = SubCompetitor::join('sub_technical_numbers','sub_technical_numbers.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_technical_numbers.technical_number')->where('submission_id',session('submission_id'))->orderBy('technical_number', 'DESC')->get();

   	// 	if($competitors){
		  //  $collection = collect($competitors);
		  //  $data       = $collection->where('id', $this->id);
		  //  $value      = $data->keys()->first() + 1;
		  //  return $value;
		  // }else{
			 // return '';
		  // }
	}



	// public function getFinancial(){
	// 	$data = SubCompetitor::join('sub_financial_costs','sub_financial_costs.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_financial_costs.conversion_rate','sub_financial_costs.financial_cost')->where('sub_competitors.id', $this->id)->get();

	// 	$total=0;

	// 	$conversionRate=1;
	// 	if($sub->conversion_rate){
	// 		$conversionRate=$sub->conversion_rate;
	// 	}

	// 	foreach ($data as $sub){
	// 		$total += ($conversionRate * $sub->financial_cost);
	// 	}
	// 	return $total;
	// }

}
