<?php
/**
 * @version sf 1.1
 */
class MailUtils {

	/**
	 * @param $to = to email
	 * @param $to_name = name
	 * @param $from
	 * @param $from_name
	 * @param $reply
	 * @param $reply_name
	 * @param $subject
	 * @param $message
	 * @param options
	 *   'render' => 'true', // render a template
		'decorate' => 'true', // decorate with a layout
	  'layout' => 'layout', // the layout you want

	  'module' => 'room', // module name
	  'action' => 'changePassword', // action name
	  'vars' => array ('var1' => $foobar), // variable to send to the template for rendering
	  'helpers' => array ('Object', 'Javascript'), // list of helpers to load for rendering
	  'type' => 'text/html', // content-type text/plain or text/html
	  'charset' => 'UTF-8',
	  'smtp_auth' => 'true' // for authentification using app_smtp_server_name, app_smtp_server_port, app_smtp_server_auth_username, app_smtp_server_auth_passwd
	  'batch' => 'true'

	  @return array
	 */
	public static function sendMail($to, $to_name, $from, $from_name, $reply, $reply_name, $subject, $message, $options = array (
		'render' => false,
		'type' => 'text/html',
		'charset' => 'UTF-8',
	)) {


		if (isset ($options['render'])) {
			if ($options['render']) {
				$context = sfContext :: getInstance();
				$view = new sfPHPView($context, $options['module'], '', sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR . sfConfig::get('sf_app') . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $options['module'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $options['action'] . 'Success');

				if ($options['render']) {
					$vars = $options['vars'];
					$helpers = $options['helpers'];

					if (isset ($options['decorate'])) {
						$view->setDecorator($options['decorate']);
						if ($options['decorate']) {
							$view->setDecoratorTemplate($options['layout']);
						}
					}


					if (isset ($options['helpers'])) {
						sfLoader :: loadHelpers($options['helpers']);
					}

					foreach ($vars as $key=>$value) {
						$view->setAttribute($key, $value);
					}

					$message = $view->render();
				}
			}
		}



		if (isset ($options['smtp_auth'])) {
			$smtp = new Swift_Connection_SMTP(sfConfig :: get('app_smtp_server_name'), sfConfig::get ('app_smtp_server_port'));
			$smtp->setUsername (sfConfig::get ('app_smtp_server_auth_username'));
			$smtp->setPassword (sfConfig::get ('app_smtp_server_auth_passwd'));

			$swift = new Swift($smtp);
		} else {
			$swift = new Swift(new Swift_Connection_SMTP(sfConfig :: get('app_smtp_server_name')));
		}

		if (isset ($options['log_enabled'])) {
			$swift->log->enable();
		}

		//Create the message
		$swiftMsg = new Swift_Message($subject, $message, $options['type']);

		//Now check if Swift actually sends it
		$swift->send($swiftMsg, new Swift_Address($to, $to_name), new Swift_Address($from, $from_name));

		$swift->disconnect();
	}
}
?>