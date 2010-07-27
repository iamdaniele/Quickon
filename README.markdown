## What is Quickon ##

Quickon is a Fango plugin that allows you to have multiple configurations on per-host basis. You can manage database settings and dispatcher rules, but you can also store your own settings in case you need it.

Read the [Wiki](http://wiki.github.com/taniele/Quickon) to get started.

## How does it work ##
Simply put your plugin in the `plugins/` directory. Pick the `conf/` directory and place it at the same level of your `application/` or `public/`. The configuration dir is already filled with some examples along with the default file. If there's no file inside the `conf/` directory, Quickon will simply not run.

Change your app's index.php to include a new instance of Quickon, like this example:

`
$fango = new Fango();
$config = QuickonPlugin::init()->getConfig();
$fango->run($config["rules"]);
`

If you have a database, remove `FangoDB::connect`. Quickon will take care of this, too.

## About the configuration files ##

Your configuration is stored in JSON, and you can find a sample configurations in the `default` and `rules` files that you'll find in the `conf/` directory. Those two files hold the default configuration, such as dispatcher rules.
Then you have host-specific configuration. Just duplicate `default` and rename it after your app's full hostname (e.g. myapp.example.com) and this file will take priority over the default settings and rules. As you might expect, you can have a host-specific set of dispatcher rules, or simply use the `rules` file to setup global rules. You can also use no rules at all and in this case you'll use the default Fango rules.

## It's important to know that... ##

Since you're using JSON, notice that literal backslashes (\\) and quotes need to be escaped.
