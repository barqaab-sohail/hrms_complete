<div>
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" wire:click="" class="btn btn-success float-right" data-toggle="tooltip" title="New Asset">Add New Asset</button>
    </div>
    <div class="table-responsive m-t-40">
        <table id="myTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Asset Code</th>
                    <th>Description</th>
                    <th>Ownership</th>
                    <th>Location/Allocation</th>
                    <th>Image</th>
                    <th class="text-center" style="width:5%">Edit</th>
                    @can('asset delete record')
                    <th class="text-center" style="width:5%">Delete</th>
                    @endcan
                </tr>
            </thead>

        </table>
    </div>
</div>