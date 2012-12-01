<?php

\Foolz\Plugin\Event::forge('the.bootstrap.was.loaded')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('Foolz\Theme\Theme.execute.foolz/foolfake-theme-fake')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('Foolz\Theme\Theme.install.foolz/foolfake-theme-fake')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('Foolz\Theme\Theme.uninstall.foolz/foolfake-theme-fake')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('Foolz\Theme\Theme.upgrade.foolz/foolfake-theme-fake')
	->setCall(function($result) {
		$result->set('success');
	});