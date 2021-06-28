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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use Illuminate\Http\Request; use Illuminate\Routing\Redirector; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use Validator; class EnvironmentController extends Controller { protected $EnvironmentManager; public function __construct(EnvironmentManager $environmentManager) { $this->EnvironmentManager = $environmentManager; } public function environmentMenu() { return view("\151\x6e\x73\x74\141\x6c\154\x65\162\x2e\x65\x6e\x76\151\x72\157\156\155\x65\x6e\x74"); } public function environmentWizard() { } public function environmentClassic() { $envConfig = $this->EnvironmentManager->getEnvContent(); return view("\151\x6e\163\x74\141\154\x6c\145\x72\56\145\156\x76\151\162\157\156\155\145\x6e\x74\x2d\x63\154\141\163\163\151\143", compact("\x65\156\x76\x43\157\x6e\x66\151\147")); } public function saveClassic(Request $input, Redirector $redirect) { $message = $this->EnvironmentManager->saveFileClassic($input); return $redirect->route("\x49\156\163\x74\141\154\x6c\x65\x72\56\x65\x6e\x76\x69\x72\157\x6e\x6d\145\156\164\103\x6c\141\163\x73\151\x63")->with(["\155\x65\x73\x73\141\147\145" => $message]); } }
