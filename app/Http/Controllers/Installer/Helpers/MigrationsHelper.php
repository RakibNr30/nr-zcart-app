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
 namespace App\Http\Controllers\Installer\Helpers; use Illuminate\Support\Facades\DB; trait MigrationsHelper { public function getMigrations() { $migrations = glob(database_path() . DIRECTORY_SEPARATOR . "\x6d\151\147\162\x61\164\x69\x6f\x6e\163" . DIRECTORY_SEPARATOR . "\x2a\x2e\160\x68\x70"); return str_replace("\x2e\x70\150\x70", '', $migrations); } public function getExecutedMigrations() { return DB::table("\x6d\151\x67\x72\x61\x74\x69\157\x6e\163")->get()->pluck("\155\151\x67\162\x61\164\x69\x6f\156"); } }
