<?php

require_once('tests/php/base.php');

class CASHSystemTests extends UnitTestCase {

	function test_formatTimeAgo() {
		$return_date = CASHSystem::formatTimeAgo(time() - 90000);
		$return_hours = CASHSystem::formatTimeAgo(time() - 25000);
		$return_1hour = CASHSystem::formatTimeAgo(time() - 5000);
		$return_minutes = CASHSystem::formatTimeAgo(time() - 2500);
		$return_1minute = CASHSystem::formatTimeAgo(time() - 90);
		$return_seconds = CASHSystem::formatTimeAgo(time() - 45);
		$give_string_return = CASHSystem::formatTimeAgo(date('d M Y h:i:s A', (time() - 5000)));
		
		$this->assertPattern('/^[0-9]{2} [A-Za-z]{3}/', $return_date); // > 1 day returns a 'd M' formatted date
		$this->assertPattern('/hours/', $return_hours); // between 2 and 24 hours
		$this->assertEqual('1 hour ago',$return_1hour); // 1 hour ago (fuzzy)
		$this->assertPattern('/minutes/', $return_minutes); // between 2 and 59 minutes
		$this->assertEqual('1 minute ago',$return_1minute); // 1 minute ago (fuzzy)
		$this->assertPattern('/seconds/', $return_seconds); // between 1 and 59 seconds ago
		$this->assertEqual('1 hour ago',$give_string_return); // give a string, parse, get a string
	}

	function test_linkifyText() {
		$test_str = 'First add an anchor for http://cashmusic.org and mailto for info@cashmusic.org, second a twitter link for @cashmusic';
		$linkified = CASHSystem::linkifyText($test_str);
		$this->assertPattern('/href=\"http:\/\/cashmusic.org\"\>http:\/\/cashmusic.org/', $linkified); // test www link
		$this->assertPattern('/href=\"mailto:info@cashmusic.org\"\>info@cashmusic.org/', $linkified); // test mailto
		$linkified = CASHSystem::linkifyText($test_str,true);
		$this->assertPattern('/href=\"http:\/\/www.twitter.com\/cashmusic\" target=\"_blank\">@cashmusic/', $linkified); // test twitter
	}

	function test_getURLContents() {
		$return = CASHSystem::getURLContents(CASH_API_URL);
		$this->assertPattern('/"greeting":"hi."/',$return); // using local API URL as firewalls could mess with an external test
	}

	function test_getDefaultEmail() {
		$cash_settings = parse_ini_file(CASH_PLATFORM_ROOT.'/settings/cashmusic.ini.php');
		$parsed_default_email = $cash_settings['systememail'];
		$this->assertEqual(CASHSystem::getDefaultEmail(),$parsed_default_email);
	}

}
?>
