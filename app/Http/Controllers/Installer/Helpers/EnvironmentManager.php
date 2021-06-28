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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Http\Request; class EnvironmentManager { private $envPath; private $envExamplePath; public function __construct() { $this->envPath = base_path("\56\x65\x6e\166"); $this->envExamplePath = base_path("\56\x65\x6e\x76\56\145\170\141\x6d\160\154\145"); } public function getEnvContent() { if (file_exists($this->envPath)) { goto Kpi5c; } if (file_exists($this->envExamplePath)) { goto p58ON; } touch($this->envPath); goto cd2iX; p58ON: copy($this->envExamplePath, $this->envPath); cd2iX: Kpi5c: return file_get_contents($this->envPath); } public function getEnvPath() { return $this->envPath; } public function getEnvExamplePath() { return $this->envExamplePath; } public function saveFileClassic(Request $input) { $message = trans("\151\x6e\x73\164\x61\x6c\154\145\162\137\155\x65\x73\x73\141\x67\145\x73\56\x65\156\x76\x69\162\x6f\156\x6d\x65\156\x74\56\163\165\143\143\x65\163\163"); try { file_put_contents($this->envPath, $input->get("\145\156\x76\103\157\x6e\146\x69\147")); } catch (Exception $e) { $message = trans("\x69\x6e\163\x74\141\x6c\x6c\x65\x72\x5f\x6d\145\x73\163\x61\x67\145\163\x2e\x65\156\x76\151\162\x6f\x6e\x6d\145\x6e\x74\56\x65\162\162\157\162\163"); } return $message; } }
