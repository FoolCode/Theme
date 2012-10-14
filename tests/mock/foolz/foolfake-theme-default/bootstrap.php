<?php

\Foolz\Plugin\Event::forge('the.bootstrap.was.loaded')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('foolz\theme\theme.execute.foolz/foolfake-theme-default')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('foolz\theme\theme.install.foolz/foolfake-theme-default')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('foolz\theme\theme.uninstall.foolz/foolfake-theme-default')
	->setCall(function($result) {
		$result->set('success');
	});

\Foolz\Plugin\Event::forge('foolz\theme\theme.upgrade.foolz/foolfake-theme-default')
	->setCall(function($result) {
		$result->set('success');
	});