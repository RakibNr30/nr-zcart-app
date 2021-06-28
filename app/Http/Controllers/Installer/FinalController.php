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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use App\Http\Controllers\Installer\Helpers\FinalInstallManager; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; class FinalController extends Controller { public function final(FinalInstallManager $finalInstall, EnvironmentManager $environment) { $finalMessages = $finalInstall->runFinal(); $finalEnvFile = $environment->getEnvContent(); return view("\151\x6e\x73\164\141\154\x6c\145\x72\56\x66\x69\x6e\x69\163\x68\x65\x64", compact("\x66\x69\156\141\x6c\x4d\x65\x73\x73\141\147\x65\163", "\x66\x69\156\x61\x6c\105\156\166\x46\151\x6c\x65")); } public function seedDemo(DatabaseManager $databaseManager) { $response = $databaseManager->seedDemoData(); return redirect()->route("\x49\156\x73\x74\x61\154\x6c\x65\162\56\146\x69\x6e\x69\163\x68"); } public function finish(InstalledFileManager $fileManager) { $finalStatusMessage = $fileManager->update(); return redirect()->to(config("\151\x6e\163\x74\x61\x6c\154\145\162\56\x72\x65\144\151\x72\145\x63\164\x55\x72\x6c"))->with("\x6d\x65\163\x73\141\x67\145", $finalStatusMessage); } }
