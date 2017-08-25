<?php
require_once 'cfg.php';


if ($_PATH) {
	include __DIR__.'/'.join('/', $_PATH).'.phpt';
	return;
}

include 'model.phpt';
include 'data.phpt';
