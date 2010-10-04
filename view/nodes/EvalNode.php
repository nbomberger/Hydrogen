<?php
/*
 * Copyright (c) 2009 - 2010, Frosted Design
 * All rights reserved.
 */

namespace hydrogen\view\nodes;

use hydrogen\view\Node;
use hydrogen\view\ExpressionEvaluator;

class EvalNode implements Node {
	protected $expr;

	public function __construct($expr) {
		$this->expr = $expr;
	}

	public function render($context) {
		$php = ExpressionEvaluator::exprToPHP($this->expr);
		$result = ExpressionEvaluator::evaluate($this->expr, $context);
		echo "[[$php]] ";
		if (is_bool($result))
			echo $result ? 'true' : 'false';
		else
			echo $result;
	}
}