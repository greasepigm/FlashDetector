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

namespace iTXTech\FlashDetector\Fdb;

use iTXTech\FlashDetector\Arrayable;
use iTXTech\FlashDetector\Property\Classification;

class FlashId extends Arrayable{
	protected $id;
	protected $s;//page size
	protected $p;//pages
	protected $n = [];

	public function __construct(string $id, array $arr = null){
		$this->id = strtoupper($id);
		parent::__construct($arr);
	}

	public function addPartNumber(string $pn) : FlashId{
		if(!in_array($pn, $this->n)){
			$this->n[] = $pn;
		}
		return $this;
	}

	public function getPageSize() : int{
		return $this->s ?? Classification::UNKNOWN_PROP;
	}

	public function getPagesPerBlock() : int{
		return $this->p ?? Classification::UNKNOWN_PROP;
	}

	public function setPageSize(int $size) : FlashId{
		$this->s = $size;
		return $this;
	}

	public function setPagesPerBlock(int $pages) : FlashId{
		$this->p = $pages;
		return $this;
	}

	public function getFlashId() : string{
		return $this->id;
	}

	public function getPartNumbers() : array{
		return $this->n ?? [];
	}

	public function toArray() : array{
		$arr = parent::toArray();
		unset($arr["id"]);
		foreach($arr as $k => $v){
			if($v == -1 or $v == null){
				unset($arr[$k]);
			}
		}
		return $arr;
	}
}