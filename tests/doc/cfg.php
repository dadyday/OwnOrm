<?php
require_once __DIR__.'/../cfg.php';

use Tester\Assert as Is;

function createDocSnippet($docFile, $pos, $prefix = '') {
	$content = file_get_contents(__DIR__.'/../../doc/'.$docFile);
	$ok = preg_match_all('~\R```php(.*?)\R```~sexm', $content, $aMatches);
	Is::truthy($ok);
	Is::true(count($aMatches[1]) >= $pos);

	$code = "<?php\nuse OwnOrm\Model;\nuse OwnOrm\Data;\n";
	$code .= $prefix."\n";
	$code .= preg_replace('~\.\.\.~', '', $aMatches[1][$pos]);

	$temp = tempnam(__DIR__.'/temp', 'test_');
	file_put_contents($temp, $code);

	return $temp;
}
