<select <?= t("element_attributes.tpl.php", array('attributes' => $element->getAttributes())) ?> >

<?php $options = $element->getOptions();
$selectValue = $element->getValue();
foreach($options as $value => $label):?>
<option value='<?= $value ?>' <?=($selectValue == $value ? "selected='selected'":"") ?> ><?= $label ?></option>
<?php endforeach; ?>
</select>