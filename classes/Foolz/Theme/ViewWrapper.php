<?php

namespace Foolz\Theme;

class ViewWrapper extends View
{
	public function toString()
	{
		extract($this->getParamManager()->getParams());

		include $include;
	}
}