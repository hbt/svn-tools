<?php
class sfWidgetFormSchemaFormatterVbTable extends sfWidgetFormSchemaFormatter implements vbFormatterActions {
	protected $rowFormat = "<tr>\n  <th>%label%</th>\n  <td>%error%%field%%help%%hidden_fields%</td>\n</tr>\n";
	protected $errorRowFormat = "<tr><td colspan=\"2\">\n%errors%</td></tr>\n";
	protected $helpFormat = ' * %help%';
	protected $decoratorFormat = "<table>\n  %content%</table>";

	public function getHelpFormat() {
		//		return image_tag('test.png') . $this->helpFormat;
		return parent :: getHelpFormat();
	}

	public function beforeRendering($attributes, $vbForm) {

		return '<table>';
	}

	public function afterRendering($attributes, $vbForm) {
		return '</table>';
	}

	public function renderAllErrors($vbForm) {
		$content = '<ul>';
		$formFields = $vbForm->getFormFieldSchema();

		foreach ($formFields as $formField) {
			if ($formField->hasError()) {
				$content .= '<li class="form_error">';
				$content .= $this->generateTopErrorMessage($formField);

				$content .= '</li>';
			}
		}

		//		if ($vbForm->hasGlobalErrors()) {
		//			foreach ($vbForm->getGlobalErrors() as $globalError) {
		//				$content .= '<li class="form_error">';
		//				$content .= ucfirst($globalError->getMessage());
		//				$content .= '</li>';
		//			}
		//		}

		$content .= '</ul>';

		return $content;
	}

	public function generateTopErrorMessage($formField) {
		return $formField->renderLabelName() . ' ' . $formField->getError();
	}

}