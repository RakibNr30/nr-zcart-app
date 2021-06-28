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
 namespace App\Http\Controllers\Installer; use Exception; use Illuminate\Support\Facades\DB; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\DatabaseManager; class DatabaseController extends Controller { private $databaseManager; public function __construct(DatabaseManager $databaseManager) { $this->databaseManager = $databaseManager; } public function database() { if ($this->checkDatabaseConnection()) { goto sNyAI; } return redirect()->back()->withErrors(["\144\x61\x74\x61\142\x61\x73\x65\x5f\143\157\156\x6e\x65\143\x74\x69\157\156" => trans("\x69\156\163\x74\141\154\154\x65\162\137\x6d\x65\x73\163\x61\x67\145\x73\x2e\145\x6e\166\151\x72\x6f\156\x6d\x65\x6e\x74\56\167\x69\172\141\162\144\x2e\146\157\162\155\56\x64\142\137\143\157\x6e\x6e\x65\x63\x74\151\x6f\156\x5f\146\x61\x69\x6c\145\x64")]); sNyAI: ini_set("\155\141\170\x5f\x65\170\x65\143\x75\x74\x69\x6f\156\137\x74\151\x6d\145", 600); $response = $this->databaseManager->migrateAndSeed(); return redirect()->route("\111\156\163\x74\x61\154\154\x65\x72\56\146\151\156\x61\x6c")->with(["\x6d\x65\x73\x73\x61\147\145" => $response]); } private function checkDatabaseConnection() { try { DB::connection()->getPdo(); return true; } catch (Exception $e) { return false; } } }
