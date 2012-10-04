<?php


class ZigConnectOptionBuilder
	{
	function __construct()
		{
		if (!$this->Options = get_option('zigconnect_options')) 
			{ 
			$this->Options = array(); 
			add_option('zigconnect_options', $this->Options);
			}
		# EXAMPLE: if (!isset($this->Options['AdminPerPage'])) { $this->Options['AdminPerPage'] = 20; }

		$this->Options['DeleteOptionsNextDeactivate'] = 0; # always reset this
		$this->Options['DeleteTablesNextDeactivate'] = 0; # always reset this
		$this->Options['JustActivated'] = 1; # always SET this
		update_option("zigconnect_options", $this->Options); # re-save
		}
	}


# EOF
