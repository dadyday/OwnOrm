<?php
require_once 'cfg.php';


if ($_PATH) {
	include __DIR__.'/'.join('/', $_PATH).'.phpt';
	return;
}

include 'conventional.phpt';
include 'chained.phpt';
include 'mixed.phpt';
