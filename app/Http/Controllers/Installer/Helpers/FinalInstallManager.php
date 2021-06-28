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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Support\Facades\Artisan; use Symfony\Component\Console\Output\BufferedOutput; class FinalInstallManager { public function runFinal() { $outputLog = new BufferedOutput(); $this->generateKey($outputLog); $this->publishVendorAssets($outputLog); return $outputLog->fetch(); } private static function generateKey($outputLog) { try { if (!config("\x69\x6e\163\x74\141\x6c\x6c\x65\162\56\x66\151\156\141\154\x2e\x6b\145\171")) { goto ID6HZ; } Artisan::call("\153\145\x79\x3a\x67\145\x6e\145\x72\141\164\x65", ["\x2d\x2d\146\x6f\x72\x63\145" => true], $outputLog); ID6HZ: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function publishVendorAssets($outputLog) { try { if (!config("\151\x6e\163\x74\141\x6c\154\x65\162\56\146\x69\156\141\x6c\56\x70\x75\142\x6c\x69\163\x68")) { goto YiunO; } Artisan::call("\166\x65\x6e\x64\157\162\72\160\165\142\154\x69\x73\x68", ["\55\55\141\x6c\154" => true], $outputLog); YiunO: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function response($message, $outputLog) { return ["\x73\164\x61\x74\x75\x73" => "\x65\x72\162\157\x72", "\155\x65\163\163\x61\x67\145" => $message, "\x64\142\x4f\x75\164\160\165\164\114\157\x67" => $outputLog->fetch()]; } }
