<?php

use yii\apidoc\helpers\ApiMarkdown;
use yii\apidoc\models\ClassDoc;
use yii\apidoc\models\TraitDoc;
use yii\helpers\ArrayHelper;

/**
 * @var ClassDoc|TraitDoc $type
 * @var yii\web\View $this
 * @var \yii\apidoc\templates\html\ApiRenderer $renderer
 */

$renderer = $this->context;

$methods = $type->getNativeMethods();
if (empty($methods)) {
	return;
}
ArrayHelper::multisort($methods, 'name');
?>
<h2>Method Details</h2>

<?php foreach ($methods as $method): ?>

	<div class="detailHeader h3" id="<?= $method->name . '()-detail' ?>">
		<?= $method->name ?>()
		<span class="detailHeaderTag small">
			<?= $method->visibility ?>
			method
			<?php if (!empty($method->since)): ?>
				(available since version <?= $method->since ?>)
			<?php endif; ?>
		</span>
	</div>

	<table class="detailTable table table-striped table-bordered table-hover">
		<tr><td colspan="3">
			<div class="signature2"><?= $renderer->renderMethodSignature($method) ?></div>
		</td></tr>
		<?php if (!empty($method->params) || !empty($method->return) || !empty($method->exceptions)): ?>
			<?php foreach ($method->params as $param): ?>
				<tr>
				  <td class="paramNameCol"><?= ApiMarkdown::highlight($param->name, 'php') ?></td>
				  <td class="paramTypeCol"><?= $renderer->createTypeLink($param->types) ?></td>
				  <td class="paramDescCol"><?= ApiMarkdown::process($param->description, $type) ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (!empty($method->return)): ?>
				<tr>
				  <th class="paramNameCol"><?= 'return' ?></th>
				  <td class="paramTypeCol"><?= $renderer->createTypeLink($method->returnTypes) ?></td>
				  <td class="paramDescCol"><?= ApiMarkdown::process($method->return, $type) ?></td>
				</tr>
			<?php endif; ?>
			<?php foreach ($method->exceptions as $exception => $description): ?>
				<tr>
				  <th class="paramNameCol"><?= 'throws' ?></th>
				  <td class="paramTypeCol"><?= $renderer->createTypeLink($exception) ?></td>
				  <td class="paramDescCol"><?= ApiMarkdown::process($description, $type) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (($sourceUrl = $renderer->getSourceUrl($method->definedBy, $method->startLine)) !== null): ?>
			<tr>
				<td colspan="3">Source Code: <a href="<?= $sourceUrl ?>"><?= $sourceUrl ?></a></td>
			</tr>
		<?php endif; ?>
	</table>

<!--	--><?php //$this->renderPartial('sourceCode',array('object'=>$method)); ?>

	<p><strong><?= ApiMarkdown::process($method->shortDescription, $type, true) ?></strong></p>
	<?= ApiMarkdown::process($method->description, $type) ?>

	<?= $this->render('seeAlso', ['object' => $method]) ?>

<?php endforeach; ?>
