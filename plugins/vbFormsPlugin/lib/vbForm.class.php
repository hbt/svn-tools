<?php
class vbForm extends sfForm {

	public function __construct($defaults = array (), $options = array (), $CSRFSecret = null) {
		parent :: __construct($defaults, $options, sfConfig :: get('app_csrf_secret'));

		// load basic helpers
		sfLoader :: loadHelpers(array (
			'Form'
		));
	}

	public function renderFormTag($url_for_options = '', $options = array ()) {
		return form_tag($url_for_options, $options);
	}

	public function renderSubmitTag($value = 'Submit', $options = array ()) {
		return submit_tag($value, $options);
	}

	/**
	 * renders a formatted form with a decoration around the fields
	 *
	 * @param attributes array attributes taked by the formatter
	 * @return result string
	 */
	public function renderFormatted($attributes = array ()) {
		$result = $this->getWidgetSchema()->getFormFormatter()->beforeRendering($attributes, $this);
		$result .= $this->render();
		$result .= $this->getWidgetSchema()->getFormFormatter()->afterRendering($attributes, $this);

		return $result;
	}

	public function renderAllErrors() {
		return $this->widgetSchema->getFormFormatter()->renderAllErrors($this);

	}

	/**
	 * checks for functions preValidate and postValidate
	 */
	public function isValid() {
		$valid = parent :: isValid();
		if (is_callable(array (
				$this,
				'preValidate'
			))) {

			if (!$this->preValidate()) {

				return false;
			}
		}

		if (!$valid) {

			return $valid;
		}

		if (is_callable(array (
				$this,
				'postValidate'
			))) {

			if (!$this->postValidate()) {

				return false;
			}
		}

		return true;
	}

	/**
	 * adds error message to errorSchema
	 * @param string message
	 * @return error sfValidatorError
	 */
	public function addErrorMessage($message) {
		$error = new sfValidatorError(new sfValidatorPass(), ucfirst($message));
		$this->getErrorSchema()->addError($error);

		return $error;
	}

	/**
	 * unset everything except the fields names passed in the array
	 * @param $fieldNames array
	 *
	 */
	public function unsetAllFieldNamesExcept(array $fieldNames) {
		$this->unsetAllValidatorSchemaFieldNamesExcept($fieldNames);
		$this->unsetAllWidgetSchemaFieldNamesExcept($fieldNames);
	}

	public function unsetAllValidatorSchemaFieldNamesExcept(array $fieldNames) {
		$keys = array_keys($this->validatorSchema->getFields());

		foreach ($keys as $key) {

			if (array_search($key, $fieldNames, true) === false) {
				unset ($this->validatorSchema[$key]);
			}
		}
	}

	public function unsetAllWidgetSchemaFieldNamesExcept(array $fieldNames) {
		$keys = array_keys($this->widgetSchema->getFields());
		foreach ($keys as $key) {
			if (array_search($key, $fieldNames, true) === false) {
				unset ($this->widgetSchema[$key]);
			}
		}

	}

	public function get_param($parameterName, $namespace = null) {
		return vbRequestUtils :: get_param($parameterName, $namespace);
	}

}
?>