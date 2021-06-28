<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.1   |
    |              on 2021-05-07 15:01:34              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
/*
* Copyright (C) Incevio Systems, Inc - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Munna Khan <help.zcart@gmail.com>, September 2018
*/
 namespace App\Services; use DB; use App\Package; use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Support\Facades\Artisan; class PackageInstaller { public $package; public $slug; public $namespace; public $path; public $migrations; public function __construct(Request $request, array $installable) { $this->package = array_merge($installable, $request->all()); $this->slug = $installable["\x73\154\165\147"]; $this->namespace = "\134\111\156\x63\145\x76\151\x6f\x5c\120\141\143\x6b\x61\147\x65\134" . Str::studly($this->slug); $this->path = base_path("\x70\141\143\x6b\141\147\x65\x73") . DIRECTORY_SEPARATOR . $this->slug; $this->migrations = str_replace(base_path(), '', $this->path . "\57\x64\141\x74\x61\142\141\x73\x65\x2f\155\151\x67\162\x61\164\x69\x6f\156\x73"); } public function install() { \Log::info("\x49\x6e\x73\164\x61\x6c\154\x69\x6e\x67\40\120\141\143\x6b\x61\147\x65\40" . $this->slug); Package::create(array_merge($this->package, preparePackageInstallation($this->package))); $this->migrate()->seed(); return True; } private function migrate() { \Log::info("\x4d\151\x67\x72\x61\164\151\156\x67\40\x73\x74\x61\162\164\145\144\72\40" . $this->slug); Artisan::call("\155\x69\x67\x72\x61\x74\145", ["\55\x2d\160\141\x74\x68" => $this->migrations, "\x2d\55\x66\x6f\x72\x63\145" => true]); \Log::info(Artisan::output()); return $this; } private function seed() { \Log::info("\x53\145\x65\144\x69\156\x67\x20\163\x74\x61\x72\x74\145\144\x3a\x20" . $this->slug); foreach (glob($this->path . "\x2f\x64\x61\x74\x61\142\141\x73\145\57\x73\x65\145\x64\163\x2f\x2a\56\160\150\x70") as $filename) { include $filename; $classes = get_declared_classes(); $migration = end($classes); Artisan::call("\144\x62\72\x73\145\x65\144", ["\55\x2d\x63\x6c\141\163\163" => $migration, "\x2d\x2d\x66\x6f\162\x63\x65" => true]); \Log::info(Artisan::output()); S33GE: } KYBF1: return $this; } public function uninstall() { \Log::info("\x55\156\x69\x6e\x73\x74\141\154\154\151\156\x67\40\x50\x61\143\153\x61\x67\x65\72\40" . $this->slug); $class = $this->namespace . "\x5c\125\x6e\151\156\163\164\x61\154\x6c\145\x72"; if (class_exists($class)) { goto QB8h0; } return true; QB8h0: (new $class())->cleanDatabase(); return $this->rollback(); } private function rollback() { \Log::info("\122\x6f\x6c\x6c\151\x6e\x67\x20\x62\141\143\153\40\x2e\56\56\x20" . $this->slug); foreach (array_reverse(glob($this->path . "\x2f\x64\141\164\x61\142\x61\163\x65\57\155\151\147\x72\x61\164\x69\157\156\163\57\52\x5f\52\x2e\x70\150\160")) as $filename) { include $filename; $migration = str_replace("\56\x70\x68\160", '', basename($filename)); \Log::info("\122\x6f\x6c\x6c\x69\x6e\147\40\142\141\143\153\x3a\x20" . $migration); $class = Str::studly(implode("\x5f", array_slice(explode("\137", $migration), 4))); (new $class())->down(); if (DB::table("\x6d\x69\147\162\141\x74\151\x6f\x6e\163")->where("\x6d\x69\x67\x72\x61\x74\151\x6f\156", $migration)->delete()) { goto RJcmH; } \Log::info("\x52\x6f\x6c\x6c\x62\x61\143\x6b\x20\x46\x41\x49\x4c\105\x44\x3a\x20" . $migration); goto BRTz6; RJcmH: \Log::info("\122\x6f\x6c\x6c\145\x64\x20\142\141\143\153\72\40" . $migration); BRTz6: MfgRJ: } qTVSx: return $this; } }