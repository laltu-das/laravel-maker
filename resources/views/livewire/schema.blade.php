<div>
    <div class="col-md-10 col-md-offset-1">
        <section class="content">
            <div id="info" style="display: none"></div>
            <div class="box box-primary col-lg-12">
                <div class="box-header" style="margin-top: 10px">
                    <h1 class="box-title" style="font-size: 30px">InfyOm Laravel Generator Builder</h1>
                </div>
                <div class="box-body">
                    <form id="form">
                        <input type="hidden" name="_token" id="token" value="{!! csrf_token() !!}"/>

                        <div class="form-group col-md-4">
                            <label for="txtModelName">Model Name<span class="required">*</span></label>
                            <input type="text" class="form-control" required id="txtModelName" placeholder="Enter name">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="drdCommandType">Command Type</label>
                            <select id="drdCommandType" class="form-control" style="width: 100%">
                                <option value="infyom:api_scaffold">API Scaffold Generator</option>
                                <option value="infyom:api">API Generator</option>
                                <option value="infyom:scaffold">Scaffold Generator</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtCustomTblName">Custom Table Name</label>
                            <input type="text" class="form-control" id="txtCustomTblName" placeholder="Enter table name">
                        </div>
                        <div class="form-group col-md-8">
                            <label for="txtModelName">Options</label>

                            <div class="form-inline form-group" style="border-color: transparent">
                                <div class="checkbox chk-align">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkDelete"><span
                                                class="chk-label-margin"> Soft Delete </span>
                                    </label>
                                </div>
                                <div class="checkbox chk-align">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkSave"> <span
                                                class="chk-label-margin">Save Schema</span>
                                    </label>
                                </div>
                                <div class="checkbox chk-align" id="chSwag">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkSwagger"> <span
                                                class="chk-label-margin">Swagger</span>
                                    </label>
                                </div>
                                <div class="checkbox chk-align" id="chTest">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkTestCases"> <span
                                                class="chk-label-margin">Test Cases</span>
                                    </label>
                                </div>
                                <div class="checkbox chk-align" id="chDataTable">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkDataTable"> <span
                                                class="chk-label-margin">Datatables</span>
                                    </label>
                                </div>
                                <div class="checkbox chk-align" id="chMigration">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkMigration"> <span
                                                class="chk-label-margin">Migration</span>
                                    </label>
                                </div>
                                <div class="checkbox chk-align" id="chForceMigrate">
                                    <label>
                                        <input type="checkbox" class="flat-red" id="chkForceMigrate"> <span
                                                class="chk-label-margin">Force Migrate</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="txtPrefix">Prefix</label>
                            <input type="text" class="form-control" id="txtPrefix" placeholder="Enter prefix">
                        </div>

                        <div class="form-group col-md-1">
                            <label for="txtPaginate">Paginate</label>
                            <input type="number" class="form-control" value="10" id="txtPaginate" placeholder="">
                        </div>

                        <div class="form-group col-md-12" style="margin-top: 7px">
                            <div class="form-control" style="border-color: transparent;padding-left: 0px">
                                <label style="font-size: 18px">Fields</label>
                            </div>
                        </div>

                        <div class="table-responsive col-md-12">
                            <x-laravel-maker::table class="table table-striped table-bordered" id="table">
                                <x-laravel-maker::thead class="no-border">
                                    <x-laravel-maker::tr>
                                        <x-laravel-maker::th>Field Name</x-laravel-maker::th>
                                        <x-laravel-maker::th>DB Type</x-laravel-maker::th>
                                        <x-laravel-maker::th>Validations</x-laravel-maker::th>
                                        <x-laravel-maker::th>Html Type</x-laravel-maker::th>
                                        <x-laravel-maker::th style="width: 68px">Primary</x-laravel-maker::th>
                                        <x-laravel-maker::th style="width: 80px">Is Foreign</x-laravel-maker::th>
                                        <x-laravel-maker::th style="width: 87px">Searchable</x-laravel-maker::th>
                                        <x-laravel-maker::th style="width: 63px">Fillable</x-laravel-maker::th>
                                        <x-laravel-maker::th style="width: 65px">In Form</x-laravel-maker::th>
                                        <x-laravel-maker::th style="width: 67px">In Index</x-laravel-maker::th>
                                        <x-laravel-maker::th></x-laravel-maker::th>
                                    </x-laravel-maker::tr>
                                </x-laravel-maker::thead>
                                <x-laravel-maker::tbody class="no-border-x no-border-y ui-sortable">
                                    @foreach ($rows as $index => $row)
                                        <x-laravel-maker::tr>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <input type="text" style="width: 100%" wire:model="rows.{{ $index }}.name" placeholder="Name" required class="form-control txtFieldName"/>
                                                <input type="text"  style="display: none" wire:model="rows.{{ $index }}.email" placeholder="Email" class="form-control foreignTable txtForeignTable"
                                                       placeholder="Foreign table,Primary key"/>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <select class="form-control txtdbType" style="width: 100%">
                                                    <option value="increments">Increments</option>
                                                    <option value="integer">Integer</option>
                                                    <option value="smallInteger">SmallInteger</option>
                                                    <option value="longText">LongText</option>
                                                    <option value="bigInteger">BigInteger</option>
                                                    <option value="double">Double</option>
                                                    <option value="float">Float</option>
                                                    <option value="decimal">Decimal</option>
                                                    <option value="boolean">Boolean</option>
                                                    <option value="string">String</option>
                                                    <option value="char">Char</option>
                                                    <option value="text">Text</option>
                                                    <option value="mediumText">MediumText</option>
                                                    <option value="longText">LongText</option>
                                                    <option value="enum">Enum</option>
                                                    <option value="binary">Binary</option>
                                                    <option value="dateTime">DateTime</option>
                                                    <option value="date">Date</option>
                                                    <option value="timestamp">Timestamp</option>
                                                </select>

                                                <input type="text" class="form-control dbValue txtDbValue" style="display: none"
                                                       placeholder=""/>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <input type="text" class="form-control txtValidation"/>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <select class="form-control drdHtmlType" style="width: 100%">
                                                    <option value="text">Text</option>
                                                    <option value="email">Email</option>
                                                    <option value="number">Number</option>
                                                    <option value="date">Date</option>
                                                    <option value="file">File</option>
                                                    <option value="password">Password</option>
                                                    <option value="select">Select</option>
                                                    <option value="radio">Radio</option>
                                                    <option value="checkbox">Checkbox</option>
                                                    <option value="textarea">TextArea</option>
                                                    <option value="toggle-switch">Toggle</option>
                                                </select>
                                                <input type="text" class="form-control htmlValue txtHtmlValue" style="display: none"
                                                       placeholder=""/>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <div class="checkbox" style="text-align: center">
                                                    <label style="padding-left: 0px">
                                                        <input type="checkbox" style="margin-left: 0px!important;" class="flat-red chkPrimary"/>
                                                    </label>
                                                </div>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <div class="checkbox" style="text-align: center">
                                                    <label style="padding-left: 0px">
                                                        <input type="checkbox" style="margin-left: 0px!important;" class="flat-red chkForeign"/>
                                                    </label>
                                                </div>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <div class="checkbox" style="text-align: center">
                                                    <label style="padding-left: 0px">
                                                        <input type="checkbox" style="margin-left: 0px!important;" class="flat-red chkSearchable" checked/>
                                                    </label>
                                                </div>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <div class="checkbox" style="text-align: center">
                                                    <label style="padding-left: 0px">
                                                        <input type="checkbox" style="margin-left: 0px!important;" class="flat-red chkFillable" checked/>
                                                    </label>
                                                </div>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <div class="checkbox" style="text-align: center">
                                                    <label style="padding-left: 0px">
                                                        <input type="checkbox" style="margin-left: 0px!important;" class="flat-red chkInForm" checked/>
                                                    </label>
                                                </div>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="vertical-align: middle">
                                                <div class="checkbox" style="text-align: center">
                                                    <label style="padding-left: 0px">
                                                        <input type="checkbox" style="margin-left: 0px!important;" class="flat-red chkInIndex" checked/>
                                                    </label>
                                                </div>
                                            </x-laravel-maker::td>
                                            <x-laravel-maker::td style="text-align: center;vertical-align: middle">
                                                <button type="button" wire:click="removeRow({{ $index }})">Remove</button>
                                            </x-laravel-maker::td>
                                        </x-laravel-maker::tr>
                                    @endforeach
                                </x-laravel-maker::tbody>
                            </x-laravel-maker::table>
                        </div>

                        <div class="form-inline col-md-12" style="padding-top: 10px">
                            <div class="form-group chk-align" style="border-color: transparent;">
                                <button type="button" class="btn btn-success btn-flat btn-green" wire:click="addRow"> Add Field
                                </button>
                            </div>
                            <div class="form-group chk-align" style="border-color: transparent;">
                                <button type="button" class="btn btn-success btn-flat btn-green" id="btnPrimary"> Add
                                    Primary
                                </button>
                            </div>
                            <div class="form-group chk-align" style="border-color: transparent;">
                                <button type="button" class="btn btn-success btn-flat btn-green" id="btnTimeStamps"> Add
                                    Timestamps
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive col-md-12" id="relationShip" style="margin-top:35px;display: none">
                            <table class="table table-striped table-bordered" id="table">
                                <thead class="no-border">
                                <tr>
                                    <th>Relation Type</th>
                                    <th>Foreign Model<span class="required">*</span></th>
                                    <th>Foreign Key</th>
                                    <th>Local Key</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="no-border-x no-border-y ui-sortable">

                                </tbody>
                            </table>
                        </div>
                        <div class="form-inline col-md-12" style="padding-top: 10px">
                            <div class="form-group" style="border-color: transparent;">
                                <button type="button" class="btn btn-success btn-flat btn-green" id="btnRelationShip"> Add
                                    RelationShip
                                </button>
                            </div>
                        </div>

                        <div class="form-inline col-md-12" style="padding:15px 15px;text-align: right">
                            <div class="form-group" style="border-color: transparent;padding-left: 10px">
                                <button type="submit" class="btn btn-flat btn-primary btn-blue" id="btnGenerate">Generate
                                </button>
                            </div>
                            <div class="form-group" style="border-color: transparent;padding-left: 10px">
                                <button type="button" class="btn btn-default btn-flat" id="btnReset" data-toggle="modal"
                                        data-target="#confirm-delete"> Reset
                                </button>
                            </div>
                        </div>


                        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Confirm Reset</h4>
                                    </div>

                                    <div class="modal-body">
                                        <p style="font-size: 16px">This will reset all of your fields. Do you want to
                                            proceed?</p>

                                        <p class="debug-url"></p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">No
                                        </button>
                                        <a id="btnModelReset" class="btn btn-flat btn-danger btn-ok" data-dismiss="modal">Yes</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <section class="content">
            <div id="rollbackInfo" style="display: none"></div>
            <div class="box box-primary col-lg-12">
                <div class="box-header" style="margin-top: 10px">
                    <h1 class="box-title" style="font-size: 30px">Rollback</h1>
                </div>
                <div class="box-body">
                    <form id="rollbackForm">
                        <input type="hidden" name="_token" id="rbToken" value="{!! csrf_token() !!}"/>

                        <div class="form-group col-md-4">
                            <label for="txtRBModelName">Model Name<span class="required">*</span></label>
                            <input type="text" class="form-control" required id="txtRBModelName" placeholder="Enter name">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="drdRBCommandType">Command Type</label>
                            <select id="drdRBCommandType" class="form-control" style="width: 100%">
                                <option value="api_scaffold">API Scaffold Generator</option>
                                <option value="api">API Generator</option>
                                <option value="scaffold">Scaffold Generator</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtRBPrefix">Prefix</label>
                            <input type="text" class="form-control" id="txtRBPrefix" placeholder="Enter prefix">
                        </div>
                        <div class="form-inline col-md-12" style="padding:15px 15px;text-align: right">
                            <div class="form-group" style="border-color: transparent;padding-left: 10px">
                                <button type="submit" class="btn btn-flat btn-primary btn-blue" id="btnRollback">Rollback
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <section class="content">
            <div id="schemaInfo" style="display: none"></div>
            <div class="box box-primary col-lg-12">
                <div class="box-header" style="margin-top: 10px">
                    <h1 class="box-title" style="font-size: 30px">Generate CRUD From Schema</h1>
                </div>
                <div class="box-body">
                    <form method="post" id="schemaForm" enctype="multipart/form-data">
                        <div class="form-group col-md-4">
                            <label for="txtSmModelName">Model Name<span class="required">*</span></label>
                            <input type="text" name="modelName" class="form-control" id="txtSmModelName" placeholder="Enter Model Name">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="schemaFile">Schema File<span class="required">*</span></label>
                            <input type="file" name="schemaFile" class="form-control" required id="schemaFile">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="drdSmCommandType">Command Type</label>
                            <select name="commandType" id="drdSmCommandType" class="form-control" style="width: 100%">
                                <option value="infyom:api_scaffold">API Scaffold Generator</option>
                                <option value="infyom:api">API Generator</option>
                                <option value="infyom:scaffold">Scaffold Generator</option>
                            </select>
                        </div>
                        <div class="form-inline col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-flat btn-primary btn-blue" id="btnSmGenerate">Generate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>