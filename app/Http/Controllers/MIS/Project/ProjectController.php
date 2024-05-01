<?php

namespace App\Http\Controllers\MIS\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDetail;
use PhpParser\Node\Expr\Cast\Double;

class ProjectController extends Controller
{

    public function projects($status = null, $division = null)
    {
        if ($status == '0') {
            $status = null;
        }
        if ($status != null && $division != null) {
            $data = PrDetail::where('id', '!=', 1)->where('pr_status_id', $status)->where('pr_division_id', $division)->get();
        } elseif ($status != null) {
            $data = PrDetail::where('id', '!=', 1)->where('pr_status_id', $status)->get();
        } elseif ($division != null) {
            $data = PrDetail::where('id', '!=', 1)->where('pr_division_id', $division)->get();
        } else {
            $data = PrDetail::all();
        }

        foreach ($data as $project) {
            $projects[] =  array(
                'id' => $project->id,
                'name' => $project->name,
                "contract_type_id" => $project->contractType->name ?? '',
                "client" => $project->client->name ?? '',
                "commencement_date" => $project->commencement_date ?? '',
                "contractual_completion_date" => $project->contractua ?? '',
                "actual_completion_date" => $project->actual_completion_date ?? '',
                "sub_projects" => $project->sub_project ?? '',
                "status" => $project->prStatus->name ?? '',
                "role" => $project->prRole->name ?? '',
                "division" => $project->prDivision->name ?? '',
                "share" => $project->share ?? '',
                "project_no" => $project->project_no ?? '',
            );
        }


        return response()->json($projects);
    }


    public function projectDocuments($projectId)
    {

        $prDocuments = PrDocument::where('pr_detail_id', $projectId)->get();
        return response()->json($prDocuments);
    }

    public function allProjectDocuments()
    {
        $prDocuments = PrDocument::select('id', 'pr_folder_name_id', 'pr_detail_id', 'reference_no', 'description', 'document_date', 'file_name', 'extension', 'path', 'size')->get();
        return response()->json($prDocuments);
    }

    public function proejctSummaryMM($projectId)
    {
        $prDetail = PrDetail::find($projectId);
        $budgetUtilized = budgetUtilization($projectId);
        $remainingBudget = $budgetUtilized != '0.0' ? 100 - rtrim($budgetUtilized, "%") : '0.0';
        $porjectSummary = [
            'total_cost' =>   $prDetail->prCost ? (int) $prDetail->prCost->total_cost ?? 0 - $prDetail->prCost->sales_tax ?? 0 : 0,
            'pending_invoices' => (int)pendingInvoicesAmount($projectId),
            'total_invoice' => (int)totalInvoicesAmount($projectId),
            'last_invoice' => lastInvoiceMonth($projectId),
            'budget_utilization' =>  rtrim($budgetUtilized, "%"),
            'remaining_budget' => "$remainingBudget",
            'current_progress' => currentProgress($projectId),
        ];
        return response()->json($porjectSummary);
    }

    public function manMonthProjectsStatus()
    {
        $projectsList = PrDetail::where('pr_status_id', 1)->where('contract_type_id', 2)->where('pr_division_id', 2)->get();
        $projects = [];
        foreach ($projectsList as $project) {
            $currProgressWithDate = currentProgress($project->id);
            $currentProgressDate = '';
            $currentProgress = '';
            if (strlen($currProgressWithDate) > 10) {
                $pieces = explode(" - ", $currProgressWithDate);
                $currentProgressDate = $pieces[0];
                $currentProgress = $pieces[1];
            }
            $budgetUtilized =  budgetUtilization($project->id);
            $color = '';
            if ((float) rtrim($budgetUtilized, "%") > (float) rtrim($currentProgress, "%")) {
                $color = 'red';
            } else {
                $color = 'green';
            }

            $projects[] = [
                'id' => $project->id,
                'projectType' => $project->contract_type_id === 2 ? 'Man Month' : 'Lumpsum',
                'projectName' => $project->project_no . " - " . $project->name,
                'lastInvoice' => lastInvoiceMonth($project->id),
                'budgetUtilization' => $budgetUtilized,
                'progress' =>   $currentProgress,
                'progressDate' => $currentProgressDate,
                'color' => $color,

            ];
        }
        return response()->json($projects);
    }
}
