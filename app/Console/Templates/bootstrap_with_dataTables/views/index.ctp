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

<?php
echo "<?php echo \$this->Html->css('dataTables.bootstrap'); ?>\n";
echo "<?php echo \$this->Html->script('jquery.dataTables.min'); ?>\n";
echo "<?php echo \$this->Html->script('dataTables.bootstrap'); ?>\n";
echo "<?php echo \$this->Html->script('paginated.jquery.datatable.setup'); ?>\n"
?>

<div class="<?php echo $pluralVar; ?> index container">

    <div class="row">
	<div class="col-md-12">
	    <div class="page-header">
		<h1><?php echo "<?php echo __('{$pluralHumanName}'); ?>"; ?></h1>
	    </div>
	</div><!-- end col md 12 -->
    </div><!-- end row -->



    <div class="row">

	<div class="col-md-3">
	    <div class="actions">
		<div class="panel panel-default">
		    <div class="panel-heading">Actions</div>
		    <div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
			    <li><?php echo "<?php echo \$this->Html->link(__('<span class=\"glyphicon glyphicon-plus\"></span>&nbsp;&nbsp;New " . $singularHumanName . "'), array('action' => 'add'), array('escape' => false)); ?>"; ?></li>			    
			</ul>
		    </div><!-- end body -->
		</div><!-- end panel -->
	    </div><!-- end actions -->
	</div><!-- end col md 3 -->

	<div class="col-md-9">
	    <table cellpadding="0" cellspacing="0" class="paginated_jquery_table table table-striped">
		<thead>
		    <tr>
			<?php foreach ($fields as $field): ?>
    			<th><?php echo "<?php echo __('" . Inflector::humanize($field) . "'); ?>"; ?></th>
			<?php endforeach; ?>
			<th class="actions"></th>
		    </tr>
		</thead>
		<tbody>
		    <?php
		    echo "\t<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
		    echo "\t\t\t\t\t<tr>\n";
		    foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
			    foreach ($associations['belongsTo'] as $alias => $details) {
				if ($field === $details['foreignKey']) {
				    $isKey = true;
				    echo "\t\t\t\t\t\t\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
				    break;
				}
			    }
			}
			if ($isKey !== true) {
			    echo "\t\t\t\t\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
			}
		    }

		    echo "\t\t\t\t\t\t<td class=\"actions\">\n";
		    echo "\t\t\t\t\t\t\t<?php echo \$this->Html->link('<span class=\"glyphicon glyphicon-search\"></span>', array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false)); ?>\n";
		    echo "\t\t\t\t\t\t\t<?php echo \$this->Html->link('<span class=\"glyphicon glyphicon-edit\"></span>', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false)); ?>\n";
		    echo "\t\t\t\t\t\t\t<?php echo \$this->Form->postLink('<span class=\"glyphicon glyphicon-remove\"></span>', array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		    echo "\t\t\t\t\t\t</td>\n";
		    echo "\t\t\t\t\t</tr>\n";

		    echo "\t\t\t\t<?php endforeach; ?>\n";
		    ?>
		</tbody>
	    </table>

	</div> <!-- end col md 9 -->
    </div><!-- end row -->


</div><!-- end containing of content -->