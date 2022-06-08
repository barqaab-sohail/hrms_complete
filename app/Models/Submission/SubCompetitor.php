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

    public function subResult(){
    	return $this->hasOne('App\Models\Submission\SubResult');
    }

    public function subFinancialScore(){
    	return $this->hasMany('App\Models\Submission\SubFinancialScore');
    }

   public function getRanking(){

   		$competitors = SubCompetitor::join('sub_results','sub_results.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_results.technical_financial_score')->where('submission_id',session('submission_id'))->orderBy('technical_financial_score', 'DESC')->get();
   		if($competitors){
		   $collection = collect($competitors);
		   $data       = $collection->where('id', $this->id);
		   $value      = $data->keys()->first() + 1;
		   return $value;
		}else{
			return '';
		}
	}

	public function getFinancial(){
		$data = SubCompetitor::join('sub_financial_scores','sub_financial_scores.sub_competitor_id','sub_competitors.id')->select('sub_competitors.*','sub_financial_scores.conversion_rate','sub_financial_scores.quoted_price')->where('sub_competitors.id', $this->id)->get();

		$total=0;

		foreach ($data as $sub){
			$total += ($sub->conversion_rate * $sub->quoted_price);
		}
		return $total;
	}

}
