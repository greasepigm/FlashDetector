<?php

/*
 * iTXTech FlashDetector
 *
 * Copyright (C) 2018-2019 iTX Technologies
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace iTXTech\FlashDetector\FDBGen\Generator;

use iTXTech\FlashDetector\Fdb\Fdb;
use iTXTech\SimpleFramework\Util\StringUtil;

class SiliconMotionForceFlash extends Generator{
	public static function getDirName() : string{
		return "smff";
	}

	public static function merge(Fdb $fdb, string $data, string $filename) : void{
		$data = explode("\r\n", mb_convert_encoding($data, "UTF-8", "UTF-8"));
		foreach($data as $line => $d){
			if(!StringUtil::startsWith($d, "A")){
				$d = explode(",", explode("_", $d)[0]);
				if(count($d) == 6){
					for($i = 0; $i < 6; $i++){
						if(strlen($d[$i]) == 1){
							$d[$i] = "0" . $d[$i];
						}
					}
					$d = implode("", $d);//FlashID
					$index = explode("=", $data[$line + 1])[1];
					if(StringUtil::contains($index, "Page")){
						$end = $start = strpos($index, "Page");
						while($index{--$start} == "0" or ((int) $index{$start} > 0)) ;
						$start++;
						$fdb->getIddb()->getFlashId($d, true)
							->setPagesPerBlock((int) substr($index, $start, $end - $start));
					}
					foreach(["12", "16", "4", "8", "2"] as $p){
						if(StringUtil::contains($index, $p . "K")){
							$fdb->getIddb()->getFlashId($d, true)->setPageSize((int) $p);
							break;
						}
					}
					$info = explode(",", $index);
					$pn = explode("_", str_replace(" ", "", explode("(", @end($info))[0]))[0];
					$vendor = str_replace([" ", "tsb", "ss", "hy", "hynix"], ["", "toshiba", "samsung", "hynix", "skhynix"],
						strtolower(explode("_", $info[0])[0]));
					if(is_numeric($vendor{strlen($vendor) - 1})){
						$vendor = substr($vendor, 0, strlen($vendor) - 1);
					}

					$fdb->getPartNumber($vendor, $pn, true)
						->addFlashId($d);
				}
			}
		}
	}
}
