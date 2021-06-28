<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.1   |
    |              on 2021-05-07 15:00:43              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
/*
* Copyright (C) Incevio Systems, Inc - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Munna Khan <help.zcart@gmail.com>, September 2018
*/
 namespace App\Http\Controllers\Installer; use App\Http\Requests; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\PermissionsChecker; class PermissionsController extends Controller { protected $permissions; public function __construct(PermissionsChecker $checker) { $this->permissions = $checker; } public function permissions() { $permissions = $this->permissions->check(config("\151\x6e\163\x74\x61\154\x6c\x65\x72\x2e\x70\145\162\x6d\x69\163\x73\151\157\x6e\163")); return view("\151\x6e\x73\164\x61\154\154\x65\x72\56\x70\x65\162\155\151\163\x73\x69\x6f\x6e\163", compact("\160\x65\x72\x6d\151\163\163\x69\157\x6e\163")); } }
