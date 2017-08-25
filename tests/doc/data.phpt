<?php
require_once 'cfg.php';

use OwnOrm\Model;
use Tester\Assert as Is;

include createDocSnippet('usage.md', 0);
include createDocSnippet('usage.md', 1);

dump($oData);
