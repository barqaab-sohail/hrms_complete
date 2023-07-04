<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Cart;
use DataTables;

class TestController extends Controller
{

    public function test()
    {
        $dummy = array(
            "test.pk" => array(
                "cycle" => "2",
                "qty" => "1",
                "price" => "140",
                "plan_id" => null,
                "page" => null,
                "type" => "1",
                "addons" => "",
                "domain_cycle" => null,
                "domain_price" => "140"
            ), "test1.pk" => array(
                "cycle" => "2",
                "qty" => "1",
                "price" => "140",
                "plan_id" => null,
                "page" => null,
                "type" => "1",
                "addons" => "",
                "domain_cycle" => null,
                "domain_price" => "140"
            ), "test2.pk" => array(
                "cycle" => "2",
                "qty" => "1",
                "price" => "140",
                "plan_id" => null,
                "page" => null,
                "type" => "1",
                "addons" => "",
                "domain_cycle" => null,
                "domain_price" => "140"
            ), "test3.pk" => array(
                "cycle" => "2",
                "qty" => "1",
                "price" => "140",
                "plan_id" => null,
                "page" => null,
                "type" => "1",
                "addons" => "",
                "domain_cycle" => null,
                "domain_price" => "140"
            )
        );

        $items = [];
        foreach ($dummy as $key => $value) {
            $value["id"] = $key;
            array_push($items, $value);
        }

        return DataTables::of($items)
            ->addIndexColumn()
            ->editColumn('services', function ($row) {

                return "<b>Service Code: </b> <span class='sitecolor'>10</span><br />";
            })
            ->addColumn('setup_fee', function ($row) {

                return $row['id'];
            })

            ->addColumn('Edit', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row['id'] . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editInvoice">Edit</a>';

                return $btn;
            })
            ->addColumn('Delete', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row['id'] . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteInvoice">Delete</a>';


                return $btn;
            })
            ->make(true);
        dd($items);
        return 'ok';
    }
}
