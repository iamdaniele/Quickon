<?
class QuickonPlugin {

	protected $configProperties = array('database' => array('driver', 'name', 'host', 'user', 'pass'));
	protected $config;
	protected $environment = null;
	
	const CONF_PATH = '../conf/';

	function __construct($fangoInstance, $configName = null) {

		if (($configName === null || !file_exists(QuickonPlugin::CONF_PATH . $configName)) && file_exists(QuickonPlugin::CONF_PATH . $_SERVER["HTTP_HOST"])) {
			
			$configName = $_SERVER["HTTP_HOST"];
		}
		else {
			$configName = "default";
		}
		
		$this->config = $this->getConfigFile(QuickonPlugin::CONF_PATH . $configName);
		$this->parseConfig();

		if (array_key_exists("database", $this->config)) {
			FangoDB::connect($this->config["database"]["driver"] . ":dbname=" . $this->config["database"]["name"] . ";host=" . $this->config["database"]["host"], $this->config["database"]["user"], $this->config["database"]["pass"]);
		}

		if (array_key_exists("rules", $this->config)) {
			$fangoInstance->run($this->config["rules"]);
		}
		elseif (file_exists(QuickonPlugin::CONF_PATH . 'rules')) {
			
			$this->config["rules"] = $this->getConfigFile(QuickonPlugin::CONF_PATH . "rules");
			$fangoInstance->run($this->config["rules"]);
		}
		else {
			$fangoInstance->run();
		}
	}
	
	function getConfigFile($filename) {
		return json_decode(file_get_contents($filename), true);
	}
	
	function parseConfig() {
	
		foreach ($this->configProperties as $key => $value) {
			
			if(!array_key_exists($key, $this->configProperties)) continue;
			$missingProperties = array_diff_assoc($this->configProperties[$key], array_keys($this->config[$key]));

			if(!empty($missingProperties)) {
				throw new Exception("The following properties are missing for section '{$key}': " . implode(',', $missingProperties));
			}
		}
		
		if (isset($this->config["environment"])) {
			
			switch (strtolower($this->config["environment"])) {
				case "development":
				case "devel":
				case "dev":
					$this->environment = "development";
				break;
				
				case "testing":
				case "staging":
					$this->environment = "staging";
				break;
				
				case "production":
				case "live":
					$this->environment = "live";
				break;
			}
		}
	}
	
	function isDevelopment() { return $this->environment == "development"; }
	function isStaging() { return $this->environment == "staging"; }
	function isLive() { return $this->environment == "live"; }
	
}
