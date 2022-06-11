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

   	public function subTechnicalScore(){
    	return $this->hasOne('App\Models\Submission\SubTechnicalScore');
    }

   
    public function subFinancialScore(){
    	return $this->hasOne('App\Models\Submission\SubFinancialScore');
    }

    public function submission(){
    	return $this->belongsTo('App\Models\Submission\Submission');
    }

    public function getFinancialMark(){
    	$financialWeightage = $this->submission->subDescription->financial_weightage;
    	$lowestCompetitor = SubCompetitor::join('sub_financial_scores','sub_financial_scores.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_financial_scores.quoted_price')->where('submission_id',session('submission_id'))->orderBy('quoted_price', 'ASC')->first();

    	return round(($lowestCompetitor->quoted_price/$this->subFinancialScore->quoted_price * $financialWeightage),4);

    }
    public function getTechnicalMark(){
    	$technicalWeightage = $this->submission->subDescription->technical_weightage;
    	return round(($this->subTechnicalScore->technical_score * $technicalWeightage / 100),4);
    }

    public function getTechnicalAndFinancialMark(){

    	return $this->getTechnicalMark() + $this->getFinancialMark();
    }

    

   public function getRanking(){
   		
   		$passingMarks = $this->submission->subDescription->passing_marks;

   		$competitors = SubCompetitor::join('sub_technical_scores','sub_technical_scores.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_technical_scores.technical_score')->where('submission_id',session('submission_id'))->orderBy('technical_score', 'DESC')->get();

   		if($this->subTechnicalScore->technical_score < $passingMarks){
   			return "Not Qualify";
   		}


   		if($competitors){
		   $collection = collect($competitors);
		   $data       = $collection->where('id', $this->id);
		   $value      = $data->keys()->first() + 1;
		   return $value;
		}else{
			return '';
		}
	}



	// public function getFinancial(){
	// 	$data = SubCompetitor::join('sub_financial_scores','sub_financial_scores.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_financial_scores.conversion_rate','sub_financial_scores.quoted_price')->where('sub_competitors.id', $this->id)->get();

	// 	$total=0;

	// 	$conversionRate=1;
	// 	if($sub->conversion_rate){
	// 		$conversionRate=$sub->conversion_rate;
	// 	}

	// 	foreach ($data as $sub){
	// 		$total += ($conversionRate * $sub->quoted_price);
	// 	}
	// 	return $total;
	// }

}
