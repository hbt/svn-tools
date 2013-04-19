<?php
interface vbFormatterActions {

	public function beforeRendering($attributes, $vbForm);

	public function afterRendering($attributes, $vbForm);

	public function renderAllErrors($vbForm);

}