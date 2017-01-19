<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<div class="<?php echo $pluralVar; ?> view container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo "<?php echo __('{$singularHumanName}'); ?>"; ?></h1>
			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-md-3">
			<div class="actions">
				<div class="panel panel-default">
					<div class="panel-heading">Actions</div>
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
							<?php
								echo "\t\t<li><?php echo \$this->Html->link(__('<span class=\"glyphicon glyphicon-edit\"></span>&nbsp&nbsp;Edit " . $singularHumanName ."'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false)); ?> </li>\n";
								echo "\t\t<li><?php echo \$this->Form->postLink(__('<span class=\"glyphicon glyphicon-remove\"></span>&nbsp;&nbsp;Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
								echo "\t\t<li><?php echo \$this->Html->link(__('<span class=\"glyphicon glyphicon-list\"></span>&nbsp&nbsp;List " . $pluralHumanName . "'), array('action' => 'index'), array('escape' => false)); ?> </li>\n";
								echo "\t\t<li><?php echo \$this->Html->link(__('<span class=\"glyphicon glyphicon-plus\"></span>&nbsp&nbsp;New " . $singularHumanName . "'), array('action' => 'add'), array('escape' => false)); ?> </li>\n";

								$done = array();
							?>
							</ul>
						</div><!-- end body -->
				</div><!-- end panel -->
			</div><!-- end actions -->
		</div><!-- end col md 3 -->

		<div class="col-md-9">			
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<?php
				foreach ($fields as $field) {
					echo "<tr>\n";
					$isKey = false;
					if (!empty($associations['belongsTo'])) {
						foreach ($associations['belongsTo'] as $alias => $details) {
							if ($field === $details['foreignKey']) {
								$isKey = true;
								echo "\t\t<th><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></th>\n";
								echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
								break;
							}
						}
					}
					if ($isKey !== true) {
						echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
						echo "\t\t<td>\n\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
					}
					echo "</tr>\n";
				}
				?>
				</tbody>
			</table>

		</div><!-- end col md 9 -->

	</div>
</div>
