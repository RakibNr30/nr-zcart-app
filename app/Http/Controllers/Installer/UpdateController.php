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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; use App\Http\Controllers\Installer\Helpers\DatabaseManager; class UpdateController extends Controller { use \App\Http\Controllers\Installer\Helpers\MigrationsHelper; public function welcome() { return view("\x69\156\x73\x74\141\x6c\154\x65\x72\56\x75\160\x64\141\164\x65\x2e\x77\145\x6c\x63\x6f\155\x65"); } public function overview() { $migrations = $this->getMigrations(); $dbMigrations = $this->getExecutedMigrations(); return view("\x69\156\x73\x74\141\x6c\154\145\x72\x2e\x75\160\144\141\164\145\x2e\157\x76\145\x72\x76\x69\x65\167", ["\x6e\165\x6d\142\145\162\x4f\146\125\x70\144\x61\164\145\x73\120\145\156\x64\151\156\147" => count($migrations) - count($dbMigrations)]); } public function database() { $databaseManager = new DatabaseManager(); $response = $databaseManager->migrateAndSeed(); return redirect()->route("\114\x61\x72\141\166\x65\x6c\x55\160\144\141\164\x65\162\72\x3a\x66\151\x6e\x61\154")->with(["\155\x65\163\x73\x61\x67\145" => $response]); } public function finish(InstalledFileManager $fileManager) { $fileManager->update(); return view("\151\156\163\164\x61\154\x6c\145\162\56\165\x70\x64\x61\164\x65\x2e\146\151\x6e\x69\163\x68\145\144"); } }
