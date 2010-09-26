<?php
/*
 * Copyright (c) 2009 - 2010, Frosted Design
 * All rights reserved.
 */

namespace hydrogen\view\nodes;

use hydrogen\view\Node;
use hydrogen\view\exceptions\NoSuchVariableException;
use hydrogen\config\Config;

class VariableNode implements Node {
	protected $variable;
	protected $drilldowns;
	protected $filters;
	protected $origin;
	
	public function __construct($variable, $drilldowns, $filters, $origin) {
		$this->variable = $variable;
		$this->drilldowns = $drilldowns ?: array();
		$this->filters = $filters ?: array();
		$this->origin = $origin;
	}
	
	public function render($context) {
		try {
			$var = $context->get($this->variable);
		}
		catch (NoSuchVariableException $e) {
			$this->reportMissing($this->variable);
			return;
		}
		$level = 0;
		foreach ($this->drilldowns as $dd) {
			if (isset($var[$dd]))
				$var = $var[$dd];
			else if (isset($var->$dd))
				$var = $var->$dd;
			else {
				$varName = $this->variable;
				for ($i = 0; $i <= $level; $i++)
					$varName .= '.' . $this->drilldowns[$i];
				$this->reportMissing($varName);
			}
			$level++;
		}
		echo $var;
	}
	
	protected function reportMissing($varString) {
		if (Config::getVal("view", "print_missing_var"))
			echo "?? " . $varString . " ??";
		else
			throw new NoSuchVariableException('Variable "' .
				$varString . '" does not exist in template "'.
				$this->origin . '".');
	}
}