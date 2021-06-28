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
 namespace App\Http\Controllers\Installer\Helpers; class InstalledFileManager { public function create() { $installedLogFile = storage_path("\151\156\163\x74\141\x6c\x6c\x65\x64"); $dateStamp = date("\x59\57\155\57\x64\40\150\x3a\x69\x3a\x73\141"); if (!file_exists($installedLogFile)) { goto d2rIP; } $message = trans("\x69\156\163\x74\141\154\x6c\145\x72\137\155\x65\163\x73\141\x67\145\163\x2e\165\160\144\141\x74\x65\162\56\x6c\x6f\147\x2e\x73\x75\143\x63\145\163\163\137\x6d\x65\163\x73\141\147\145") . $dateStamp; file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX); goto JXbxh; d2rIP: $message = trans("\151\156\x73\164\x61\154\154\145\162\x5f\x6d\x65\163\x73\141\x67\145\x73\56\151\x6e\x73\x74\141\154\154\145\x64\x2e\163\165\143\x63\145\x73\163\137\154\157\147\x5f\x6d\x65\163\x73\141\147\x65") . $dateStamp . "\xa"; file_put_contents($installedLogFile, $message); JXbxh: return $message; } public function update() { return $this->create(); } }
