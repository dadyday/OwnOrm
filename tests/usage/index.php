<?php
require_once 'cfg.php';


if ($_PATH) {
	include __DIR__.'/'.join('/', $_PATH).'.phpt';
	return;
}

include 'detailed.phpt';
include 'procedural.phpt';
include 'compact.phpt';
include 'shorten.phpt';
include 'chained.phpt';
